<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class UpdateProfileController extends Controller
{
    public function __invoke(Request $request)
    {
        $currentUser = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'username' => ['nullable', 'string', 'between:2,100', Rule::unique('users')->ignore($currentUser)],
            'address' => 'nullable|string|between:2,100',
            'phonenumber' => 'nullable|string|between:2,14',
            'name' => 'nullable|string|between:2,100',
            'email' => ['nullable', 'string', 'email', 'max:100', Rule::unique('users')->ignore($currentUser)],
            'profile_photo_path' => ['nullable', 'image', 'mimes:png,jpeg,jpg'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

            $profile = User::find($currentUser);
            $email = $request->email;
            $name = $request->name;
            $username = $request->username;
            $address = $request->address;
            $phonenumber = $request->phonenumber;

            if ($email != null) {
                if (!($profile->email === $email)) {
                    $profile->forceFill(
                        [
                            'email' => $email,
                            'email_verified_at' => null
                        ]
                    )->save();
                }
            }
            if ($request->hasFile('profile_photo_path')) {
                if($profile->profile_photo_path != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $profile->profile_photo_path));
                }
                $filename = 'foto_profil-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->profile_photo_path->extension();
                $request->file('profile_photo_path')->move('storage/foto-profil/', $filename);
                $profile->forceFill(
                    [
                        'profile_photo_path' => env('APP_URL') . '/storage/foto-profil/' . $filename,
                    ]
                )->save();
            }

            if ($name != null) {
                $profile->forceFill(
                    [
                        'name' => $name,
                    ]
                )->save();
            }
            if ($username != null) {
                $profile->forceFill(
                    [
                        'username' => $username,
                    ]
                )->save();
            }
            if ($address != null) {
                $profile->forceFill(
                    [
                        'address' => $address,
                    ]
                )->save();
            }
            if ($phonenumber != null) {
                $profile->forceFill(
                    [
                        'phonenumber' => $phonenumber,
                    ]
                )->save();
            }

            return response()->json([
                'message' => 'Update profile success.',
                'data' => $profile
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, user profile cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
