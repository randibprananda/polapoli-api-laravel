<?php

namespace App\Http\Controllers\Api\User\Saksi;

use App\Http\Controllers\Controller;
use App\Models\CheckinCheckout;
use App\Models\DetailUser;
use App\Models\InvitationUser;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserRoleTim;
use App\Notifications\InvitationRelawanNotification;
use BadMethodCallException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class SaksiController extends Controller
{
    public function listSaksi(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(9)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        if ($request->search != null) {
            $saksis = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                },'detailUser', 'daftarAnggotaAtasan.detailUserAtasan.user'))
                ->whereHas("userRoleTim.role", function ($q) {
                    $q->where("id", '=', 5);
                })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->orderBy('created_at', 'desc')->where("name", "LIKE", "%$request->search%")
                ->paginate(10)->withQueryString();
        } else {
            $saksis = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                },'detailUser', 'daftarAnggotaAtasan.detailUserAtasan.user')
                )
                ->whereHas("userRoleTim.role", function ($q) {
                    $q->where("id", '=', 5);
                })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
        }
        if ($saksis != null) {
            return response()->json([
                'message' => 'List of saksis',
                'data' => $saksis,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No saksis available',
            ], Response::HTTP_OK);
        }
    }

    public function addSaksi(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(9)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        $validator = Validator::make($request->all(), [
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
            'tps' => 'required|string',
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
                $checkingEmail->timRelawans()->attach($current_team_id);

                $tokenPassword = PasswordReset::create([
                    'token' => $token,
                    'email' => $checkingEmail->email,
                ]);

                $inv = InvitationUser::create([
                    'user_id' => $checkingEmail->id,
                    'email' => $checkingEmail->email,
                    'token' => $tokenPassword->token,
                ]);

                UserRoleTim::create([
                    'user_id' => $checkingEmail->id,
                    'tim_relawan_id' => $current_team_id,
                    'role_id' => 5,
                ]);

                $url = $request->custom_url;
                Notification::send($inv, new InvitationRelawanNotification($url));

                return response()->json([
                    'message' => 'User Saksi has been created & invited',
                    'dataUser' => $checkingEmail,
                ], Response::HTTP_OK);
            }

            DB::beginTransaction();
            $userSaksi = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]);
            $userSaksi->timRelawans()->attach($current_team_id);

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
                'tps' => $request->tps,
            ]);
            $tokenPassword = PasswordReset::create([
                'token' => $token,
                'email' => $email,
            ]);

            $inv = InvitationUser::create([
                'user_id' => $userSaksi->id,
                'email' => $email,
                'token' => $tokenPassword->token,
            ]);

            UserRoleTim::create([
                'user_id' => $userSaksi->id,
                'tim_relawan_id' => $current_team_id,
                'role_id' => 5,
            ]);

            $url = $request->custom_url . '/new-password?token=' . $token;
            Notification::send($inv, new InvitationRelawanNotification($url));
            DB::commit();

            return response()->json([
                'message' => 'User saksi has been created & invited',
                'dataUser' => $userSaksi,
                'detailUser' => $detailUser,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, user saksi cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateSaksi(Request $request, $id)
    {
        // if (!Auth::user()->customHasPermissionTo(9)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nomor_hp' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'keterangan' => 'nullable|string',
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
                'propinsi_id' => $request->propinsi_id,
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
                'no_hp' => $request->nomor_hp,
                'keterangan' => $request->keterangan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'tps' => $request->tps,
            ])->save();

            DB::commit();
            return response()->json([
                'message' => 'User saksi has been updated.',
                'user' => $findUser,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, user saksi cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showSaksi($id)
    {
        // if (!Auth::user()->customHasPermissionTo(9)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        try {
            if ($userSaksi  = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                },
                'detailUser', 'detailUser.propinsi',
                'detailUser.kabupaten', 'detailUser.kecamatan',
                'detailUser.kelurahan'))
                ->whereHas("userRoleTim.role", function ($q) {
                $q->where("id", "=", 5);
            })->orderBy('created_at', 'desc')->find($id)) {
                return response()->json([
                    'message' => 'Detail User.',
                    'data' => $userSaksi
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Sorry, saksi not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, saksi not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteSaksi($id)
    {
        // if (!Auth::user()->customHasPermissionTo(9)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        try {
            $user = User::find($id);
            $user->timRelawans()->detach();
            $user->delete();
            return response()->json([
                'message' => 'Saksi has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            if ($e instanceof BadMethodCallException) {
                return response()->json([
                    'message' => 'Saksi has ben deleted.'
                ], Response::HTTP_OK);
            }else{
                return response()->json([
                    'message' => 'Sorry, saksi cannot be deleted.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function logPresensiSaksi(Request $request)
    {
        $presensi = CheckinCheckout::where([['user_id', $request->id_user], ['tim_relawan_id', Auth::user()->current_team_id]])->orderBy('date', 'desc')->paginate(10);
        return response()->json([
            'message' => 'Log Presensi Saksi.',
            'data' => $presensi
        ], Response::HTTP_OK);
    }
}
