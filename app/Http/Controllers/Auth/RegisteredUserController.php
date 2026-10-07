<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $safeName = htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8');

        $emailHtml = <<<HTML
        <!doctype html>
        <html>
        <body style="margin:0;padding:24px;background-color:#081711;font-family:Arial,Helvetica,sans-serif;color:#e8efe8;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" bgcolor="#081711" style="background-color:#081711;">
                <tr>
                    <td align="center" style="padding:12px;">
                        <table role="presentation" width="600" cellspacing="0" cellpadding="0" bgcolor="#10261d" style="max-width:600px;width:100%;background-color:#10261d;border:1px solid #294435;border-radius:16px;overflow:hidden;">
                            <tr>
                                <td bgcolor="#0c2118" style="padding:24px 30px;background-color:#0c2118;border-bottom:1px solid #294435;">
                                    <div style="font-family:Georgia,serif;font-size:25px;font-weight:bold;color:#a3b99f;">Cardo-Fer</div>
                                    <div style="margin-top:6px;font-size:13px;color:#91aa97;">Western Ghats · Idukki · Wayanad</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="https://images.pexels.com/photos/34716137/pexels-photo-34716137.jpeg?cs=srgb&amp;dl=pexels-davisonjoseph-34716137.jpg&amp;fm=jpg" alt="Cardamom plantation in Idukki, Kerala" width="600" style="display:block;width:100%;height:auto;max-height:250px;object-fit:cover;">
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor="#10261d" style="padding:30px;background-color:#10261d;">
                                    <span style="display:inline-block;padding:7px 11px;background-color:#203b2c;border:1px solid #385744;border-radius:20px;color:#9eb69b;font-size:11px;font-weight:bold;letter-spacing:1px;">WELCOME TO CARDO-FER</span>
                                    <h1 style="margin:16px 0 12px;color:#f2f4ee;font-family:Georgia,serif;font-size:30px;">Welcome, <span style="color:#a3b99f;font-style:italic;">{$safeName}</span>!</h1>
                                    <p style="font-size:15px;line-height:1.7;color:#c0cbc2;">Your account is ready. We're glad to have you with us. Cardo-Fer brings useful farm information together to support smarter day-to-day decisions.</p>
                                    <h2 style="margin:26px 0 12px;color:#c4d1c5;font-size:15px;">Explore what Cardo-Fer offers</h2>
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" bgcolor="#173327" style="margin:9px 0;background-color:#173327;border:1px solid #2d4d3b;border-radius:9px;">
                                        <tr><td style="padding:16px;"><strong style="color:#e3ebe3;font-size:14px;">🌿 &nbsp; Plant health</strong><p style="margin:8px 0 0;color:#aebdb0;font-size:13px;line-height:1.55;">Explore symptom information and plant health guidance.</p></td></tr>
                                    </table>
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" bgcolor="#173327" style="margin:9px 0;background-color:#173327;border:1px solid #2d4d3b;border-radius:9px;">
                                        <tr><td style="padding:16px;"><strong style="color:#e3ebe3;font-size:14px;">🧪 &nbsp; Fertilizer guidance</strong><p style="margin:8px 0 0;color:#aebdb0;font-size:13px;line-height:1.55;">Find fertilizer details and dosage information.</p></td></tr>
                                    </table>
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" bgcolor="#173327" style="margin:9px 0;background-color:#173327;border:1px solid #2d4d3b;border-radius:9px;">
                                        <tr><td style="padding:16px;"><strong style="color:#e3ebe3;font-size:14px;">🌱 &nbsp; Field tools</strong><p style="margin:8px 0 0;color:#aebdb0;font-size:13px;line-height:1.55;">Keep your field and plant information organized.</p></td></tr>
                                    </table>
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" bgcolor="#c77e37" style="margin:24px 0 18px;background-color:#c77e37;border-radius:9px;">
                                        <tr><td align="center" style="padding:16px;color:#10261d;font-family:Georgia,serif;font-size:16px;font-weight:bold;line-height:1.5;">Grow with knowledge. Farm with confidence.</td></tr>
                                    </table>
                                    <p style="font-size:12px;line-height:1.6;color:#91a195;">If you did not create this account, you can ignore this email.</p>
                                    <p style="font-size:10px;color:#809487;">Photo: Davison Joseph / Pexels</p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" bgcolor="#0c2118" style="padding:20px;background-color:#0c2118;border-top:1px solid #294435;color:#91a195;font-size:12px;line-height:1.6;">Cardo-Fer<br>Helping farmers make informed decisions</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        HTML;

        Mail::html($emailHtml, function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Welcome to Cardo-Fer');
        });

        Auth::login($user);

        return redirect(route('farmerhome', absolute: false));
    }
}
