<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\diseases_categories;
use App\Models\diseases_symptoms;
use App\Models\SymptomReg;
use App\Models\User;
use App\Models\fertilizer_reg;
use App\Models\FarmerField;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.adminhome');
    }
    public function category(Request $request)
{
    $application = new diseases_categories();
    $application->diseases_name = $request->diseases_name;
    $application->save();

    return back()->with('success', 'Category added successfully.');
}
public function symptoms()
{
    return view('admin.symptoms');
}

public function symptom_insert(Request $request)
{
    $request->validate([
        'symptom_name' => ['required', 'string', 'max:255'],
        'symptom_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    $symptom = new diseases_symptoms();

    $symptom->symptom_name = $request->symptom_name;

    if ($request->hasFile('symptom_image')) {
        $symptom->symptom_image = $request
            ->file('symptom_image')
            ->store('symptoms', 'public');
    }

    $symptom->save();

    return back()->with('success', 'Symptom added successfully.');
}

public function symptomsview()
{
    $symptoms = diseases_symptoms::orderBy('symptom_id', 'asc')->get();

    return view('admin.symptomsview', compact('symptoms'));
}

public function symptom_edit($id)
{
    $symptom = diseases_symptoms::findOrFail($id);

    return view('admin.symptomedit', compact('symptom'));
}

public function symptom_update(Request $request, $id)
{
    $request->validate([
        'symptom_name' => ['required', 'string', 'max:255'],
        'symptom_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    $symptom = diseases_symptoms::findOrFail($id);

    $symptom->symptom_name = $request->symptom_name;

    if ($request->hasFile('symptom_image')) {

        // Delete old image if one exists
        if ($symptom->symptom_image) {
            \Illuminate\Support\Facades\Storage::disk('public')
                ->delete($symptom->symptom_image);
        }

        // Store new image
        $symptom->symptom_image = $request
            ->file('symptom_image')
            ->store('symptoms', 'public');
    }

    $symptom->save();

    return redirect()
        ->route('symptomsview')
        ->with('success', 'Symptom updated successfully.');
}

public function symptom_delete($id)
{
    $symptom = diseases_symptoms::findOrFail($id);

    // Delete symptom image before deleting the record
    if ($symptom->symptom_image) {
        \Illuminate\Support\Facades\Storage::disk('public')
            ->delete($symptom->symptom_image);
    }

    $symptom->delete();

    return redirect()
        ->route('symptomsview')
        ->with('success', 'Symptom deleted successfully.');
}



public function indexSymptomReg()
{
    $symptomRegs = SymptomReg::with(['category', 'symptom'])->get();
   return view('admin.symptomregview', compact('symptomRegs'));

}  
public function createSymptomReg()
{
    $categories = diseases_categories::all();
    $symptoms = diseases_symptoms::all();
    return view('admin.symptomreg', compact('categories', 'symptoms'));
}

public function storeSymptomReg(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:diseases_categories,category_id',
        'symptom_id' => 'required|array',
        'symptom_id.*' => 'exists:diseases_symptoms,symptom_id',
    ]);

    foreach ($request->symptom_id as $symptomId) {

        SymptomReg::firstOrCreate([
            'category_id' => $request->category_id,
            'symptom_id' => $symptomId,
        ]);

    }

    return redirect()->route('symptom_regs')
                     ->with('success', 'Symptoms registered successfully.');
}

public function editSymptomReg($id)
{
    $category = diseases_categories::findOrFail($id);
    $categories = diseases_categories::all();
    $symptoms = diseases_symptoms::all();
    $selectedSymptomIds = SymptomReg::where('category_id', $id)->pluck('symptom_id')->toArray();

    return view('admin.symptomregedit', compact('category', 'categories', 'symptoms', 'selectedSymptomIds'));
}

public function updateSymptomReg(Request $request, $id)
{
    $request->validate([
        'category_id' => 'required|exists:diseases_categories,category_id',
        'symptom_id' => 'required|array',
        'symptom_id.*' => 'exists:diseases_symptoms,symptom_id',
    ]);

    SymptomReg::where('category_id', $id)->delete();

    foreach ($request->symptom_id as $symptomId) {
        SymptomReg::firstOrCreate([
            'category_id' => $request->category_id,
            'symptom_id' => $symptomId,
        ]);
    }

    return redirect()->route('symptom_regs')
                     ->with('success', 'Registration updated successfully.');
}

public function deleteSymptomReg($id)
{
    SymptomReg::where('category_id', $id)->delete();

    return redirect()->route('symptom_regs')->with('success', 'Symptom registration deleted successfully.');
}

public function users()
{
    $users = User::orderBy('created_at', 'desc')->get();

    return view('admin.users', compact('users'));
}


public function user_update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $id],
        'role' => ['required', 'in:user,admin'],
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;

    $user->save();

    return redirect()
        ->route('users')
        ->with('success', 'User updated successfully.');
}


public function user_delete($id)
{
    $user = User::findOrFail($id);

    if (auth()->id() == $user->id) {
        return redirect()
            ->route('users')
            ->with('error', 'You cannot delete your own account.');
    }

    $user->delete();

    return redirect()
        ->route('users')
        ->with('success', 'User deleted successfully.');
}

// fertilizer management methods

public function fertilizer()
{
    $fertilizers = fertilizer_reg::orderBy('id', 'desc')->get();

    return view('admin.fertilizer', compact('fertilizers'));
}


public function fertilizer_insert(Request $request)
{
    $request->validate([
        'fertilizer_name' => 'required|string|max:255',
        'description' => 'required|string',
        'category' => 'required|in:Organic,Inorganic',

        'dosage_amount' => 'required|numeric|min:0.01',
        'dosage_unit' => 'required|in:g,kg,ml,L',

        'reference_quantity' => 'nullable|numeric|min:0.01',
        'reference_unit' => 'nullable|in:L,ml',
    ]);

    // Additional validation based on fertilizer category
    if ($request->category === 'Organic') {

        $request->validate([
            'dosage_unit' => 'required|in:g,kg',
        ]);

    } elseif ($request->category === 'Inorganic') {

        $request->validate([
            'dosage_unit' => 'required|in:ml,L',
            'reference_quantity' => 'required|numeric|min:0.01',
            'reference_unit' => 'required|in:L,ml',
        ]);
    }

    $fertilizer = new fertilizer_reg();

    $fertilizer->fertilizer_name = $request->fertilizer_name;
    $fertilizer->description = $request->description;
    $fertilizer->category = $request->category;

    $fertilizer->dosage_amount = $request->dosage_amount;
    $fertilizer->dosage_unit = $request->dosage_unit;

    // Only inorganic fertilizers use reference quantity/unit
    if ($request->category === 'Inorganic') {
        $fertilizer->reference_quantity = $request->reference_quantity;
        $fertilizer->reference_unit = $request->reference_unit;
    } else {
        $fertilizer->reference_quantity = null;
        $fertilizer->reference_unit = null;
    }

    $fertilizer->save();

    return back()->with('success', 'Fertilizer added successfully.');
}


public function fertilizer_edit($id)
{
    $fertilizer = fertilizer_reg::findOrFail($id);

    return view('admin.fertilizeredit', compact('fertilizer'));
}


public function fertilizer_update(Request $request, $id)
{
    $request->validate([
        'fertilizer_name' => 'required|string|max:255',
        'description' => 'required|string',
        'category' => 'required|in:Organic,Inorganic',

        'dosage_amount' => 'required|numeric|min:0.01',
        'dosage_unit' => 'required|in:g,kg,ml,L',

        'reference_quantity' => 'nullable|numeric|min:0.01',
        'reference_unit' => 'nullable|in:L,ml',
    ]);

    // Additional validation based on fertilizer category
    if ($request->category === 'Organic') {

        $request->validate([
            'dosage_unit' => 'required|in:g,kg',
        ]);

    } elseif ($request->category === 'Inorganic') {

        $request->validate([
            'dosage_unit' => 'required|in:ml,L',
            'reference_quantity' => 'required|numeric|min:0.01',
            'reference_unit' => 'required|in:L,ml',
        ]);
    }

    $fertilizer = fertilizer_reg::findOrFail($id);

    $fertilizer->fertilizer_name = $request->fertilizer_name;
    $fertilizer->description = $request->description;
    $fertilizer->category = $request->category;

    $fertilizer->dosage_amount = $request->dosage_amount;
    $fertilizer->dosage_unit = $request->dosage_unit;

    // Only inorganic fertilizers use reference quantity/unit
    if ($request->category === 'Inorganic') {
        $fertilizer->reference_quantity = $request->reference_quantity;
        $fertilizer->reference_unit = $request->reference_unit;
    } else {
        $fertilizer->reference_quantity = null;
        $fertilizer->reference_unit = null;
    }

    $fertilizer->save();

    return redirect()
        ->route('fertilizer')
        ->with('success', 'Fertilizer updated successfully.');
}


public function fertilizer_delete($id)
{
    $fertilizer = fertilizer_reg::findOrFail($id);

    $fertilizer->delete();

    return redirect()
        ->route('fertilizer')
        ->with('success', 'Fertilizer deleted successfully.');
}


    // Field Management

    public function fieldManagement(Request $request)
    {
        $query = FarmerField::with('user');

        // Search by farmer name or email
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by area unit
        if ($request->filled('unit')) {
            $query->where('area_unit', $request->unit);
        }

        // Sorting
        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->oldest();
                break;

            case 'largest':
                $query->orderByDesc('area');
                break;

            case 'plants':
                $query->orderByDesc('plant_count');
                break;

            default:
                $query->latest();
                break;
        }

        $fields = $query->paginate(10);

        // Dashboard statistics
        $totalFields = FarmerField::count();

        $totalFarmers = FarmerField::distinct('user_id')
            ->count('user_id');

        $totalPlants = FarmerField::sum('plant_count');

        $totalAreaInAcres =
            (float) FarmerField::where('area_unit', 'acre')->sum('area')
            + (
                (float) FarmerField::where('area_unit', 'cent')->sum('area')
                / 100
            );

        return view('admin.fieldmanagement', compact(
            'fields',
            'totalFields',
            'totalFarmers',
            'totalAreaInAcres',
            'totalPlants'
        ));
    }

    // public function fieldUpdate(Request $request, $id)
    // {
    //     $request->validate([
    //         'area' => ['required', 'numeric', 'min:0.01'],
    //         'area_unit' => ['required', 'in:acre,cent'],
    //         'plant_count' => ['required', 'integer', 'min:1'],
    //     ]);

    //     $field = FarmerField::findOrFail($id);

    //     $field->update([
    //         'area' => $request->area,
    //         'area_unit' => $request->area_unit,
    //         'plant_count' => $request->plant_count,
    //     ]);

    //     return redirect()
    //         ->route('admin.field-management')
    //         ->with('success', 'Field details updated successfully.');
    // }

    public function fieldDelete($id)
    {
        $field = FarmerField::findOrFail($id);

        $field->delete();

        return redirect()
            ->route('admin.field-management')
            ->with('success', 'Field record deleted successfully.');
    }

}