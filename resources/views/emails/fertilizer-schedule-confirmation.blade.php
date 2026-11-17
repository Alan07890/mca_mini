<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Fertilizer Schedule Confirmed</title>
</head>

<body style="margin:0; padding:24px; background:#f1f8e9; font-family:Arial,sans-serif; color:#263b2c;">

    <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #dce8d8;">

        <div style="background:#1b5e20; padding:24px; text-align:center;">
            <h1 style="margin:0; color:#ffffff;">CARDO-FER 🌿</h1>
            <p style="margin:8px 0 0; color:#e8f5e9;">
                Fertilizer Schedule Confirmation
            </p>
        </div>

        <div style="padding:28px;">

            <h2 style="color:#1b5e20;">
                Hello {{ $userName }}!
            </h2>

            <p>
                Your fertilizer application schedule has been recorded successfully.
            </p>

            <table width="100%" cellpadding="12" cellspacing="0"
                   style="border-collapse:collapse; background:#f8fbf6;">

                <tr>
                    <td style="border-bottom:1px solid #dce8d8;">
                        <strong>Fertilizer</strong>
                    </td>
                    <td style="border-bottom:1px solid #dce8d8;">
                        {{ $application->fertilizer?->fertilizer_name ?? 'Fertilizer' }}
                    </td>
                </tr>

                <tr>
                    <td style="border-bottom:1px solid #dce8d8;">
                        <strong>Quantity</strong>
                    </td>
                    <td style="border-bottom:1px solid #dce8d8;">
                        {{ $application->quantity }}
                        {{ $application->quantity_unit }}
                    </td>
                </tr>

                <tr>
                    <td style="border-bottom:1px solid #dce8d8;">
                        <strong>Applied Date</strong>
                    </td>
                    <td style="border-bottom:1px solid #dce8d8;">
                        {{ \Illuminate\Support\Carbon::parse($application->applied_date)->format('d M Y') }}
                    </td>
                </tr>

                @if($application->next_application_date)
                    <tr>
                        <td>
                            <strong>Next Application</strong>
                        </td>
                        <td>
                            {{ \Illuminate\Support\Carbon::parse($application->next_application_date)->format('d M Y') }}
                        </td>
                    </tr>
                @endif

            </table>

            <p style="margin-top:24px;">
                We will send you a reminder on your scheduled next application date,
                provided that a next application date has been entered.
            </p>

            <p>
                Please follow the fertilizer product instructions and appropriate
                agricultural guidance when applying fertilizers.
            </p>

            <p style="margin-top:28px;">
                Regards,<br>
                <strong>CARDO-FER Team</strong>
            </p>

        </div>

        <div style="background:#f1f8e9; padding:14px; text-align:center; color:#58745b; font-size:12px;">
            Smart Farming for Cardamom Growers
        </div>

    </div>

</body>
</html>