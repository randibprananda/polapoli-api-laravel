<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailUser;
use App\Models\InvitationUser;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'custom_url' => 'required|url'
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user == null) {
            return response()->json(['error' => 'Email not registered'], 401);
        }
        try {

            DB::beginTransaction();
            do {
                $token = Str::uuid();
            } while (PasswordReset::where('token', $token)->first());
            $user = User::where('email', $request->email)->first();

            $tokenPassword = PasswordReset::create([
                'token' => $token,
                'email' => $user->email,
            ]);

            $inv = InvitationUser::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'token' => $tokenPassword->token,
            ]);

            $url = $request->custom_url . '/new-password?token=' . $token;
            $notification = Notification::send($inv, new ResetPasswordNotification($url));
            DB::commit();

            return [
                'status' => "We have emailed your password reset link!"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'email' => "Password reset link cannot sent to your email",
            ]);
        }
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        try {
            DB::beginTransaction();
            $updatePassword = DB::table('password_resets')
                ->where([
                    'token' => $request->token
                ])
                ->first();

            if (!$updatePassword) {
                return response([
                    'message' => 'Invalid token!'
                ], 500);
            }

            $user = User::with('detailUser')->where('email', $updatePassword->email)->first();
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            $checkRole = $user->userRoleTim;
            $isNotPM = true;
            $isKonsultan = false;

            for ($i=0; $i < count($checkRole); $i++) {
                if ($checkRole[$i]->role_id == 1) {
                    $isNotPM = false;
                }else if ($checkRole[$i]->role_id == 2) {
                    $isKonsultan = true;
                }
            }
            if ($isNotPM) {
                if ($isKonsultan) {
                    DetailUser::create([
                        'user_id' => $user->id,
                        'no_hp' => '000000000000',
                        'jenis_kelamin' => 'L',
                        'status_invitation' => 'active'
                    ]);
                }else{
                    $user->detailUser->update([
                        'status_invitation' => 'active'
                    ]);
                }
            }
            DB::table('password_resets')->where(['email' => $request->email])->delete();
            DB::commit();
            return response([
                'message' => 'Your password has been changed!'
            ], 200);
        } catch (\Exception $e) {
            return response([
                'message' => 'Invalid token!'
            ], 500);
        }
    }
}
