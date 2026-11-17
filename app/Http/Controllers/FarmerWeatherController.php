<?php

namespace App\Http\Controllers;

use App\Models\FarmerField;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Throwable;

class FarmerWeatherController extends Controller
{
    /**
     * Show a location-based weather forecast and simple, rule-based
     * weather planning tips for the logged-in farmer.
     *
     * Weather data is provided by Open-Meteo. Place search uses Photon/OpenStreetMap first, then Open-Meteo Geocoding as a fallback.
     * The locality filter accepts place=hamlet/locality and tolerates missing or
     * differently named administrative metadata (for example, a taluk in `county`).
     * No AI-generated advice is used.
     */
    public function index()
    {
        $field = FarmerField::where('user_id', Auth::id())->first();
        $weather = null;
        $tomorrow = null;
        $forecastDays = [];
        $advisories = [];
        $matchedLocation = null;
        $error = null;

        if (!$field || blank($field->place)) {
            $error = 'Add your farm location in Field Management, then save it using a suggestion or the map picker.';
        } elseif (
            !is_numeric($field->latitude ?? null) ||
            !is_numeric($field->longitude ?? null)
        ) {
            $error = 'Your saved field does not have map coordinates yet. Open Field Management, enter your local place name, pin the farm on the map, and save again.';
        } else {
            $latitude = (float) $field->latitude;
            $longitude = (float) $field->longitude;

            if (!$this->isInsideIdukkiSearchBounds($latitude, $longitude)) {
                $error = 'The saved point is outside the supported Idukki search area. Select the farm point again on the Field Management map.';
            } else {
                try {
                    $forecastKey = 'cardofer_weather_' . sha1(
                        number_format($latitude, 5, '.', '') . ',' . number_format($longitude, 5, '.', '')
                    );

                    $forecast = Cache::remember($forecastKey, now()->addMinutes(30), function () use ($latitude, $longitude) {
                        return Http::acceptJson()
                            ->connectTimeout(4)
                            ->timeout(10)
                            ->get('https://api.open-meteo.com/v1/forecast', [
                                'latitude' => $latitude,
                                'longitude' => $longitude,
                                'daily' => implode(',', [
                                    'weather_code',
                                    'temperature_2m_max',
                                    'temperature_2m_min',
                                    'precipitation_sum',
                                    'precipitation_probability_max',
                                    'wind_speed_10m_max',
                                ]),
                                'timezone' => 'auto',
                                'forecast_days' => 6,
                                'temperature_unit' => 'celsius',
                                'precipitation_unit' => 'mm',
                                'wind_speed_unit' => 'kmh',
                            ])
                            ->throw()
                            ->json();
                    });

                    $daily = $forecast['daily'] ?? [];
                    $dates = $daily['time'] ?? [];
                    $timezone = $forecast['timezone'] ?? 'Asia/Kolkata';
                    $todayDate = Carbon::now($timezone)->toDateString();
                    $tomorrowDate = Carbon::now($timezone)->addDay()->toDateString();
                    $tomorrowIndex = array_search($tomorrowDate, $dates, true);

                    if ($tomorrowIndex === false) {
                        throw new \RuntimeException('The weather service did not return tomorrow’s forecast.');
                    }

                    $tomorrow = $this->formatDay($daily, $tomorrowIndex, $tomorrowDate, $timezone);
                    $advisories = $this->makeAdvisories($tomorrow);

                    foreach ($dates as $index => $date) {
                        if ($date < $todayDate) {
                            continue;
                        }

                        $forecastDays[] = $this->formatDay($daily, $index, $date, $timezone);
                        if (count($forecastDays) >= 6) {
                            break;
                        }
                    }

                    $matchedLocation = trim((string) $field->place);
                    $weather = [
                        'requested_place' => trim((string) $field->place),
                        'timezone' => $timezone,
                        'updated_at' => now()->format('d M Y, h:i A'),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ];
                } catch (Throwable $exception) {
                    report($exception);
                    $error = 'The weather service is temporarily unavailable. Please refresh the page in a little while.';
                }
            }
        }

        return view('farmer.weather', compact(
            'field', 'weather', 'tomorrow', 'forecastDays',
            'advisories', 'matchedLocation', 'error'
        ));
    }

    /**
     * Autocomplete endpoint restricted to Idukki district, Kerala.
     * Results from other Kerala districts are intentionally excluded.
     */
    /**
     * Fast, cached, locality-only autocomplete.
     * It intentionally makes one Photon request per settled query; when no exact
     * locality is indexed, the field page offers a map pin instead of guessing.
     */
    public function searchLocations(Request $request)
    {
        $query = trim(preg_replace('/\s+/', ' ', (string) $request->query('q', '')));

        if (mb_strlen($query) < 2) {
            return response()->json(['results' => [], 'search_available' => true]);
        }

        $cacheKey = 'cardofer_photon_idukki_quick_localities_v1_' . sha1(mb_strtolower($query));

        try {
            $results = Cache::remember($cacheKey, now()->addHours(12), function () use ($query) {
                $response = Http::acceptJson()
                    ->connectTimeout(2)
                    ->timeout(4)
                    ->withHeaders([
                        'User-Agent' => 'CARDO-FER farmer weather field-location search',
                    ])
                    ->get('https://photon.komoot.io/api/', [
                        'q' => $query,
                        'lat' => 9.75,
                        'lon' => 77.13,
                        'zoom' => 10,
                        'location_bias_scale' => 0.2,
                        'bbox' => '76.50,9.30,77.55,10.50',
                        'layer' => 'locality',
                        'limit' => 10,
                        'lang' => 'en',
                        'dedupe' => 1,
                    ])
                    ->throw()
                    ->json('features', []);

                $allowedSettlementTypes = [
                    'city', 'town', 'village', 'hamlet', 'isolated_dwelling',
                    'locality', 'suburb', 'neighbourhood', 'neighborhood',
                    'quarter', 'municipality', 'borough', 'ward',
                ];
                $items = [];
                $seen = [];

                foreach (is_array($response) ? $response : [] as $feature) {
                    $properties = $feature['properties'] ?? [];
                    $coordinates = $feature['geometry']['coordinates'] ?? [];
                    if (!is_array($properties) || !is_array($coordinates) || count($coordinates) < 2) {
                        continue;
                    }

                    $longitude = $coordinates[0] ?? null;
                    $latitude = $coordinates[1] ?? null;
                    if (!is_numeric($latitude) || !is_numeric($longitude)) {
                        continue;
                    }
                    $latitude = (float) $latitude;
                    $longitude = (float) $longitude;

                    if (!$this->isInsideIdukkiSearchBounds($latitude, $longitude)) {
                        continue;
                    }

                    $countryCode = strtoupper(trim((string) ($properties['countrycode'] ?? '')));
                    $country = trim((string) ($properties['country'] ?? ''));
                    $state = trim((string) ($properties['state'] ?? ''));
                    if ($countryCode !== '' && $countryCode !== 'IN') {
                        continue;
                    }
                    if ($countryCode === '' && $country !== '' && stripos($country, 'India') === false) {
                        continue;
                    }
                    if ($state !== '' && stripos($state, 'Kerala') === false) {
                        continue;
                    }

                    $districtFields = array_filter([
                        trim((string) ($properties['district'] ?? '')),
                        trim((string) ($properties['state_district'] ?? '')),
                        trim((string) ($properties['locality_district'] ?? '')),
                    ]);
                    if (!empty($districtFields) && !collect($districtFields)->contains(
                        static fn ($value) => stripos($value, 'Idukki') !== false
                    )) {
                        continue;
                    }

                    $osmKey = mb_strtolower(trim((string) ($properties['osm_key'] ?? '')));
                    $osmValue = mb_strtolower(trim((string) ($properties['osm_value'] ?? '')));
                    if ($osmKey !== 'place' || !in_array($osmValue, $allowedSettlementTypes, true)) {
                        continue;
                    }

                    $name = trim((string) ($properties['name'] ?? $properties['locality'] ?? $properties['city'] ?? ''));
                    if ($name === '') {
                        continue;
                    }

                    $labelParts = collect([
                        $name,
                        $properties['locality'] ?? null,
                        $properties['city'] ?? null,
                        $properties['county'] ?? null,
                        'Idukki', 'Kerala', 'India',
                    ])
                        ->filter(fn ($part) => is_string($part) && trim($part) !== '')
                        ->map(fn ($part) => trim($part))
                        ->unique(fn ($part) => mb_strtolower($part))
                        ->values();

                    $item = [
                        'name' => $name,
                        'label' => $labelParts->implode(', '),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ];
                    $key = mb_strtolower(preg_replace('/[^\pL\pN]+/u', '', $item['label']));
                    if (!isset($seen[$key])) {
                        $seen[$key] = true;
                        $items[] = $item;
                    }
                }

                $normalizedQuery = mb_strtolower(preg_replace('/[^\pL\pN]+/u', '', $query));
                usort($items, static function (array $a, array $b) use ($normalizedQuery): int {
                    $normalize = static fn ($value) => mb_strtolower(preg_replace('/[^\pL\pN]+/u', '', (string) $value));
                    $aName = $normalize($a['name'] ?? '');
                    $bName = $normalize($b['name'] ?? '');
                    $rank = static function (string $name) use ($normalizedQuery): int {
                        if ($name === $normalizedQuery) return 0;
                        if (str_starts_with($name, $normalizedQuery)) return 1;
                        if (str_contains($name, $normalizedQuery)) return 2;
                        return 3;
                    };
                    return ($rank($aName) <=> $rank($bName)) ?: strcasecmp($aName, $bName);
                });

                return array_slice($items, 0, 5);
            });

            return response()->json([
                'results' => $results,
                'search_available' => true,
            ]);
        } catch (Throwable $exception) {
            // The map picker is the deterministic fallback when public search is down.
            report($exception);
            return response()->json([
                'results' => [],
                'search_available' => false,
                'message' => 'Place search is temporarily unavailable. Select the farm on the map instead.',
            ]);
        }
    }

    /**
     * Approximate Idukki-area guard shared by search and map coordinates.
     * This is a broad bounding box, not an official district boundary polygon.
     */
    private function isInsideIdukkiSearchBounds(float $latitude, float $longitude): bool
    {
        return $latitude >= 9.30
            && $latitude <= 10.50
            && $longitude >= 76.50
            && $longitude <= 77.55;
    }

    private function formatDay(array $daily, int $index, string $date, string $timezone): array
    {
        $code = (int) ($daily['weather_code'][$index] ?? -1);
        $condition = $this->weatherCondition($code);
        $rain = $daily['precipitation_sum'][$index] ?? null;
        $rainProbability = $daily['precipitation_probability_max'][$index] ?? null;
        $maxTemp = $daily['temperature_2m_max'][$index] ?? null;
        $minTemp = $daily['temperature_2m_min'][$index] ?? null;
        $wind = $daily['wind_speed_10m_max'][$index] ?? null;

        return [
            'date' => $date,
            'date_label' => Carbon::parse($date, $timezone)->format('D, d M'),
            'weather_code' => $code,
            'condition' => $condition['label'],
            'icon' => $condition['icon'],
            'max_temp' => is_numeric($maxTemp) ? round((float) $maxTemp) : null,
            'min_temp' => is_numeric($minTemp) ? round((float) $minTemp) : null,
            'rain_mm' => is_numeric($rain) ? round((float) $rain, 1) : null,
            'rain_probability' => is_numeric($rainProbability) ? (int) round((float) $rainProbability) : null,
            'wind_kmh' => is_numeric($wind) ? (int) round((float) $wind) : null,
        ];
    }

    /** WMO weather-code labels used by Open-Meteo. */
    private function weatherCondition(int $code): array
    {
        return match ($code) {
            0 => ['label' => 'Clear sky', 'icon' => '☀️'],
            1 => ['label' => 'Mainly clear', 'icon' => '🌤️'],
            2 => ['label' => 'Partly cloudy', 'icon' => '⛅'],
            3 => ['label' => 'Overcast', 'icon' => '☁️'],
            45, 48 => ['label' => 'Foggy', 'icon' => '🌫️'],
            51, 53, 55, 56, 57 => ['label' => 'Drizzle', 'icon' => '🌦️'],
            61, 63, 65, 66, 67 => ['label' => 'Rain', 'icon' => '🌧️'],
            71, 73, 75, 77, 85, 86 => ['label' => 'Snow', 'icon' => '🌨️'],
            80, 81, 82 => ['label' => 'Rain showers', 'icon' => '🌧️'],
            95, 96, 99 => ['label' => 'Thunderstorm', 'icon' => '⛈️'],
            default => ['label' => 'Conditions unavailable', 'icon' => '🌥️'],
        };
    }

    /** General weather-based planning suggestions, not disease diagnosis or dosage advice. */
    private function makeAdvisories(array $day): array
    {
        $advisories = [];
        $rainMm = $day['rain_mm'] ?? 0;
        $rainChance = $day['rain_probability'];
        $maxTemp = $day['max_temp'];
        $isThunderstorm = in_array($day['weather_code'], [95, 96, 99], true);

        if ($isThunderstorm || $rainMm >= 15 || ($rainChance !== null && $rainChance >= 70)) {
            $advisories[] = [
                'type' => 'warning',
                'title' => 'Plan around likely rain',
                'message' => 'Rain is likely or the forecast rainfall amount is notable. Avoid applying fertilizer immediately before or during heavy rain, since nutrients may run off or leach away. Check drains and low-lying areas for waterlogging.',
            ];
        } elseif ($rainMm >= 3 || ($rainChance !== null && $rainChance >= 40)) {
            $advisories[] = [
                'type' => 'watch',
                'title' => 'Keep an eye on rainfall',
                'message' => 'Showers are possible. Check the latest local forecast before field work and avoid timing fertilizer application just ahead of substantial rain.',
            ];
        } else {
            $advisories[] = [
                'type' => 'good',
                'title' => 'A relatively drier window is forecast',
                'message' => 'The forecast shows limited rainfall. Before any fertilizer application, check that the soil has suitable moisture and follow the product label and your local agricultural guidance; a dry forecast alone is not a reason to fertilize.',
            ];
        }

        if ($maxTemp !== null && $maxTemp >= 32) {
            $advisories[] = [
                'type' => 'watch',
                'title' => 'Warmer conditions',
                'message' => 'The predicted maximum temperature is high. Where practical, do field work during cooler morning or late-afternoon hours, and avoid stressing plants or workers during the hottest part of the day.',
            ];
        }

        if ($isThunderstorm) {
            $advisories[] = [
                'type' => 'warning',
                'title' => 'Use caution during thunderstorms',
                'message' => 'Postpone exposed outdoor work while thunder or lightning is nearby and wait until conditions are safe.',
            ];
        }

        return $advisories;
    }
}
