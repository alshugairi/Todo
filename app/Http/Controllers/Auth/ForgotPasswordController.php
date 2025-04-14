<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        if (!get_setting('mail_host') ||
            !get_setting('mail_port') ||
            !get_setting('mail_username') ||
            !get_setting('mail_password') ||
            !get_setting('mail_from_email')) {
            return back()->withErrors(['email' => __('admin.email_config_missing')]);
        }

        $config = [
            'default' => 'smtp',
            'mailers' => [
                'smtp' => [
                    'transport' => 'smtp',
                    'host' => get_setting('mail_host'),
                    'port' => get_setting('mail_port'),
                    'encryption' => get_setting('mail_encryption'),
                    'username' => get_setting('mail_username'),
                    'password' => get_setting('mail_password'),
                    'timeout' => null,
                    'local_domain' => null,
                ],
            ],
            'from' => [
                'address' => get_setting('mail_from_email'),
                'name' => get_setting('mail_from_name'),
            ],
        ];

        try {
            Config::set('mail', $config);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);

        } catch (\Exception $e) {
            return back()->withErrors(['email' => __('admin.password_reset_email_error')]);
        }
    }
}
