<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InvitationUser;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmailVerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'custom_url' => 'required|url',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Already Verified'
            ];
        }
        do {
            $token = uniqid() . Str::random(30);
        } while (PasswordReset::where('token', $token)->first());

        $tokenPassword = PasswordReset::create([
            'token' => $token,
            'email' => Auth::user()->email,
        ]);

        $inv = InvitationUser::create([
            'user_id' => Auth::user()->id,
            'email' => Auth::user()->email,
            'token' => $tokenPassword->token,
        ]);

        $url = $request->custom_url . '/verify-email?token=' . $token;
        Notification::send($inv, new CustomVerifyEmail($url));

        return ['status' => 'verification-link-sent'];
    }

    public function verify(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Email already verified'
            ];
        } else {
            $findUser = User::find(Auth::user()->id);
            $findUser->forceFill([
                'email_verified_at' => now(),
                'remember_token' =>  Str::uuid()
            ])->save();
            return [
                'message' => 'Email has been verified'
            ];
        }
        return [
            'message' => 'Email already verified'
        ];
    }
}
