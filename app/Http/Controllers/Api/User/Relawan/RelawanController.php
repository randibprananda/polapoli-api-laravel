<?php

namespace App\Http\Controllers\Api\User\Relawan;

use App\Http\Controllers\Controller;
use App\Models\CheckinCheckout;
use App\Models\DetailUser;
use App\Models\InvitationUser;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserRoleTim;
use App\Notifications\InvitationRelawanNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class RelawanController extends Controller
{
    public function listRelawan(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(8)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        if ($request->search != null) {
            $relawans = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                },'detailUser'))
                ->whereHas("userRoleTim.role", function ($q) {
                    $q->where("id", '=', 4);
                })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->orderBy('created_at', 'desc')->where("name", "LIKE", "%$request->search%")
                ->paginate(10)->withQueryString();
        } else {
            $relawans = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                },'detailUser'))
                ->whereHas("userRoleTim.role", function ($q) {
                    $q->where("id", '=', 4);
                })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
        }

        if ($relawans != null) {
            return response()->json([
                'message' => 'List of relawans',
                'data' => $relawans,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No relawans available',
            ], Response::HTTP_OK);
        }
    }

    public function addRelawan(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(8)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        $validator = Validator::make($request->all(), [
            'is_saksi' => 'nullable|boolean',
            'nama' => 'required|string',
            'email' => 'required|email|unique:users',
            'nomor_hp' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'propinsi_id' => 'required|numeric',
            'kabupaten_id' => 'required|numeric',
            'kecamatan_id' => 'required|numeric',
            'kelurahan_id' => 'required|numeric',
            'rt' => 'required|string',
            'rw' => 'required|string',
            'keterangan' => 'nullable|string',
            'custom_url' => 'required|url',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {

            $name = $request->nama;
            $email = $request->email;


            do {
                $token = uniqid() . Str::random(30);
            } while (PasswordReset::where('token', $token)->first());
            $current_team_id = Auth::user()->current_team_id;


            $checkingEmail = User::where('email', $request->email)->first();
            if ($checkingEmail != null) {
                try {

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
                        'role_id' => 4,
                    ]);


                    if ($request->is_saksi != 0 && $request->is_saksi != null) {
                        UserRoleTim::create([
                            'user_id' => $checkingEmail->id,
                            'tim_relawan_id' => $current_team_id,
                            'role_id' => 5,
                        ]);
                    }

                    $url = $request->custom_url;
                    Notification::send($inv, new InvitationRelawanNotification($url));


                        return response()->json([
                            'message' => 'User has been created & invited',
                            'dataUser' => $checkingEmail,
                        ], Response::HTTP_OK);
                } catch (\Exception $e) {
                }
            }
            DB::beginTransaction();

            $userRelawan = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]);
            $userRelawan->timRelawans()->attach($current_team_id);

            $user = User::orderBy('created_at', 'desc')->first();
            $detailUser = DetailUser::create([
                'propinsi_id' => $request->propinsi_id,
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
                'no_hp' => $request->nomor_hp,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'keterangan' => $request->keterangan,
                'user_id' => $user->id,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
            $tokenPassword = PasswordReset::create([
                'token' => $token,
                'email' => $email,
            ]);

            $inv = InvitationUser::create([
                'user_id' => $userRelawan->id,
                'email' => $email,
                'token' => $tokenPassword->token,
            ]);

            UserRoleTim::create([
                'user_id' => $userRelawan->id,
                'tim_relawan_id' => $current_team_id,
                'role_id' => 4,
            ]);

            if ($request->is_saksi != 0 && $request->is_saksi != null) {
                UserRoleTim::create([
                    'user_id' => $userRelawan->id,
                    'tim_relawan_id' => $current_team_id,
                    'role_id' => 5,
                ]);
            }

            $url = $request->custom_url . '/new-password?token=' . $token;
            Notification::send($inv, new InvitationRelawanNotification($url));
            DB::commit();

            return response()->json([
                'message' => 'User has been created & invited',
                'dataUser' => $userRelawan,
                'detailUser' => $detailUser,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, user cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateRelawan(Request $request, $id)
    {
        // if (!Auth::user()->customHasPermissionTo(8)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nomor_hp' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'keterangan' => 'nullable|string',
            'propinsi_id' => 'required|numeric',
            'kabupaten_id' => 'nullable|numeric',
            'kecamatan_id' => 'nullable|numeric',
            'kelurahan_id' => 'nullable|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            DB::beginTransaction();
            $findUser = User::with('detailUser')->find($id);
            $findUser->forceFill([
                'name' => $request->nama
            ])->save();

            $detailUser = DetailUser::find($findUser->detailUser->id);
            $detailUser->forceFill([
                'no_hp' => $request->nomor_hp,
                'keterangan' => $request->keterangan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'propinsi_id' => $request->propinsi_id,
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
            ])->save();

            DB::commit();
            return response()->json([
                'message' => 'User relawan has been updated.',
                'user' => $findUser,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, user relawan cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showRelawan($id)
    {
        // if (!Auth::user()->customHasPermissionTo(8)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        try {
            if ($userRelawan  = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                },
                'detailUser', 'detailUser.propinsi',
                'detailUser.kabupaten', 'detailUser.kecamatan', 'detailUser.kelurahan'))
                ->whereHas("userRoleTim.role", function ($q) {
                $q->where("id", 4);
            })->orderBy('created_at', 'desc')->find($id)) {
                return response()->json([
                    'message' => 'Detail User.',
                    'data' => $userRelawan
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Sorry, relawan not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, relawan not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteRelawan($id)
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
                'message' => 'Relawan has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Relawan has ben deleted.'
            ], Response::HTTP_OK);
        }
    }

    public function logPresensiRelawan(Request $request)
    {
        if ($request->search != null) {
            $presensi = CheckinCheckout::where([['user_id', $request->id_user], ['tim_relawan_id', Auth::user()->current_team_id]])
                ->orderBy('date', 'desc')->where("date", "LIKE", "%$request->search%")
                ->paginate(10)->withQueryString();
        } else {
            $presensi = CheckinCheckout::where([['user_id', $request->id_user], ['tim_relawan_id', Auth::user()->current_team_id]])
                ->orderBy('date', 'desc')->paginate(10)->withQueryString();
        }

        return response()->json([
            'message' => 'Log Presensi Relawan.',
            'data' => $presensi
        ], Response::HTTP_OK);
    }
}
