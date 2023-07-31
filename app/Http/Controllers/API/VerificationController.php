<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['send']);
        $this->middleware('throttle:6,1')->only('verify', 'send');
    }

    public function send()
    {
        try {
            $user = auth()->user();
            if ($user->email_verified_at) {
                return responseFail('this account verified');
            }

            event(new Registered(auth()->user()));

            return responseSuccess([], 'Verify email sent successfully');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function verify($id, $hash)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return responseFail('no user with this id');
            }

            $user->email_verified_at = now();
            $user->save();

            return responseSuccess($user, 'Verify email successfully');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
