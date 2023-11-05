<?php

namespace App\Http\Controllers\Api\User\Konsultan;

use App\Http\Controllers\Controller;
use App\Models\InvitationUser;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserRoleTim;
use App\Notifications\InvitationKonsultanNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class KonsultanController extends Controller
{
    public function listKonsultan()
    {
        // if (!Auth::user()->customHasPermissionTo(8)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        $konsultans = User::with(
            array('userRoleTim' => function($r)
            {
                $r->where("tim_relawan_id", '=',
                Auth::user()->current_team_id)
                ->with('timRelawan','role');
            }))
            ->whereHas("userRoleTim.role", function ($q) {
                $q->where("id", '=', 2);
            })
            ->whereHas("timRelawans", function ($p) {
                $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
            })->get();
        if ($konsultans != null) {
            return response()->json([
                'message' => 'List of konsultans',
                'data' => $konsultans,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No konsultans available',
            ], Response::HTTP_OK);
        }
    }

    public function addKonsultan(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(8)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'email' => 'required|email|unique:users',
            'custom_url' => 'required|url',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $role = 2;

            $name = $request->nama;
            $email = $request->email;


            do {
                $token = uniqid() . Str::random(30);
            } while (PasswordReset::where('token', $token)->first());

            $current_team_id = Auth::user()->current_team_id;
            $checkingEmail = User::where('email', $request->email)->first();
            if ($checkingEmail != null) {

                $tokenPassword = PasswordReset::create([
                    'token' => $token,
                    'email' => $checkingEmail->email,
                ]);
                $checkingEmail->timRelawans()->attach($current_team_id);

                $inv = InvitationUser::create([
                    'user_id' => $checkingEmail->id,
                    'email' => $checkingEmail->email,
                    'token' => $tokenPassword->token,
                ]);

                UserRoleTim::create([
                    'user_id' => $checkingEmail->id,
                    'tim_relawan_id' => $current_team_id,
                    'role_id' => $role,
                ]);

                $url = $request->custom_url;
                Notification::send($inv, new InvitationKonsultanNotification($url));

                return response()->json([
                    'message' => 'User konsultan has been created & invited',
                    'dataUser' => $checkingEmail,
                ], Response::HTTP_OK);
            }

            DB::beginTransaction();

            $userKonsultan = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]);
            $userKonsultan->timRelawans()->attach($current_team_id);
            $tokenPassword = PasswordReset::create([
                'token' => $token,
                'email' => $email,
            ]);

            $inv = InvitationUser::create([
                'user_id' => $userKonsultan->id,
                'email' => $email,
                'token' => $tokenPassword->token,
            ]);

            UserRoleTim::create([
                'user_id' => $userKonsultan->id,
                'tim_relawan_id' => $current_team_id,
                'role_id' => $role,
            ]);

            $url = $request->custom_url . '/new-password?token=' . $token;
            Notification::send($inv, new InvitationKonsultanNotification($url));
            DB::commit();


            return response()->json([
                'message' => 'User konsultan has been created & invited',
                'dataUser' => $userKonsultan,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, user konsultan cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateKonsultan(Request $request, $id)
    {
        // if (!Auth::user()->customHasPermissionTo(8)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            DB::beginTransaction();
            $findUser = User::find($id);
            $findUser->forceFill([
                'name' => $request->nama
            ])->save();
            DB::commit();
            return response()->json([
                'message' => 'User konsultan has been updated.',
                'user' => $findUser,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, user konsultan cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showKonsultan($id)
    {
        // if (!Auth::user()->customHasPermissionTo(8)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        try {
            if ($userKonsultan  = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                })
                )
            ->whereHas("userRoleTim.role", function ($q) {
                $q->where("id", 2);
            })->orderBy('created_at', 'desc')->find($id)) {
                return response()->json([
                    'message' => 'Detail User.',
                    'data' => $userKonsultan
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Sorry, konsultan not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, konsultan not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteKonsultan($id)
    {
        // if (!Auth::user()->customHasPermissionTo(8)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        try {
            $user = User::find($id);
            $user->timRelawans()->detach();
            $user->delete();
            return response()->json([
                'message' => 'Konsultan has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Konsultan has ben deleted.',
            ], Response::HTTP_OK);
        }
    }
}
