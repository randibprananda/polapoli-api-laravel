<?php

namespace App\Http\Controllers\Api\User\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\CheckinCheckout;
use App\Models\DaftarAnggota;
use App\Models\DetailUser;
use App\Models\InvitationUser;
use App\Models\PasswordReset;
use App\Models\TingkatKoordinator;
use App\Models\User;
use App\Models\UserRoleTim;
use App\Notifications\InvitationKoordinatorNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class KoordinatorController extends Controller
{
    public function listKoordinator(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(6)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        if ($request->tingkat_koordinator == 'propinsi'  && $request->propinsi_id == null && $request->kabupaten_id == null  && $request->kecamatan_id == null && $request->kelurahan_id == null) {
            $counting = User::whereHas("userRoleTim.role", function ($q) {
                $q->where("id", '=', 3);
            })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->whereHas('detailUser.tingkatKoordinator', function ($q) {
                    $q->where('nama_tingkat_koordinator', 'Kota/Kab');
                })->whereHas('detailUser', function ($q) {
                    $q->groupBy('propinsi_id');
                })
                ->count();

            $koordinators = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                },
                'detailUser.tingkatKoordinator',
                'detailUser.propinsi', 'detailUser.kabupaten',
                'detailUser.kecamatan', 'detailUser.kelurahan')
                )
                ->whereHas("userRoleTim.role", function ($q) {
                    $q->where("id", '=', 3);
                })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->whereHas('detailUser.tingkatKoordinator', function ($q) {
                    $q->where('nama_tingkat_koordinator', 'Provinsi');
                })->get()->map(function ($item) use ($counting) {
                    $row =  (object) ['total_anggota' => $counting, 'data' => $item];
                    return $row;
                });
        } elseif ($request->tingkat_koordinator == 'kabupaten'  && $request->propinsi_id != null && $request->kabupaten_id == null  && $request->kecamatan_id == null && $request->kelurahan_id == null  && $request->rt == null  && $request->rw == null) {
            $counting = User::whereHas("userRoleTim.role", function ($q) {
                $q->where("id", '=', 3);
            })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->whereHas('detailUser', function ($q) use ($request) {
                    $q->where('propinsi_id', $request->propinsi_id);
                })->whereHas('detailUser.tingkatKoordinator', function ($q) {
                    $q->where('nama_tingkat_koordinator', 'Kecamatan');
                })->whereHas('detailUser', function ($q) {
                    $q->groupBy('kabupaten_id');
                })->count();

            $koordinators = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                },
                'detailUser.tingkatKoordinator',
                'detailUser.propinsi',
                'detailUser.kabupaten',
                'detailUser.kecamatan',
                'detailUser.kelurahan')
               )
                ->whereHas("userRoleTim.role", function ($q) {
                    $q->where("id", '=', 3);
                })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->whereHas('detailUser', function ($q) use ($request) {
                    $q->where('propinsi_id', $request->propinsi_id);
                })->whereHas('detailUser.tingkatKoordinator', function ($q) {
                    $q->where('nama_tingkat_koordinator', 'Kota/Kab');
                })->get()->map(function ($item) use ($counting) {
                    $row =  (object) ['total_anggota' => $counting, 'data' => $item];
                    return $row;
                });
        } elseif ($request->tingkat_koordinator == 'kecamatan' && $request->propinsi_id != null
        && $request->kabupaten_id != null && $request->kecamatan_id == null && $request->kelurahan_id == null  && $request->rt == null  && $request->rw == null) {
            $counting = User::whereHas("userRoleTim.role", function ($q) {
                $q->where("id", '=', 3);
            })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->whereHas('detailUser', function ($q) use ($request) {
                    $q->where('kabupaten_id', $request->kabupaten_id);
                })->whereHas('detailUser.tingkatKoordinator', function ($q) {
                    $q->where('nama_tingkat_koordinator', 'Kelurahan');
                })->whereHas('detailUser', function ($q) {
                    $q->groupBy('kecamatan_id');
                })->count();

            $koordinators = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                },
                'detailUser.tingkatKoordinator',
                'detailUser.propinsi',
                'detailUser.kabupaten',
                'detailUser.kecamatan',
                'detailUser.kelurahan')

                )
                ->whereHas("userRoleTim.role", function ($q) {
                    $q->where("id", '=', 3);
                })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->whereHas('detailUser', function ($q) use ($request) {
                    $q->where('kabupaten_id', $request->kabupaten_id);
                })->whereHas('detailUser.tingkatKoordinator', function ($q) {
                    $q->where('nama_tingkat_koordinator', 'kecamatan');
                })->get()->map(function ($item) use ($counting) {
                    $row =  (object) ['total_anggota' => $counting, 'data' => $item];
                    return $row;
                });
        } elseif ($request->tingkat_koordinator == 'kelurahan' && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null  && $request->rt == null  && $request->rw == null) {
            $counting = User::whereHas("userRoleTim.role", function ($q) {
                $q->where("id", '=', 3);
            })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->whereHas('detailUser.tingkatKoordinator', function ($q) {
                    $q->where('nama_tingkat_koordinator', 'RT/RW');
                })->whereHas('detailUser', function ($q) use ($request) {
                    $q->where('kecamatan_id', $request->kecamatan_id);
                })->whereHas('detailUser', function ($q) {
                    $q->groupBy('rt', 'rw');
                })->count();

            $koordinators = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                },
                'detailUser.tingkatKoordinator',
                'detailUser.propinsi',
                'detailUser.kabupaten',
                'detailUser.kecamatan',
                'detailUser.kelurahan')
               )
                ->whereHas("userRoleTim.role", function ($q) {
                    $q->where("id", '=', 3);
                })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->whereHas('detailUser', function ($q) use ($request) {
                    $q->where('kecamatan_id', $request->kecamatan_id);
                })->whereHas('detailUser.tingkatKoordinator', function ($q) {
                    $q->where('nama_tingkat_koordinator', 'kelurahan');
                })->get()->map(function ($item) use ($counting) {
                    $row =  (object) ['total_anggota' => $counting, 'data' => $item];
                    return $row;
                });
        } elseif ($request->tingkat_koordinator == 'rt/rw'
        && $request->propinsi_id != null && $request->kabupaten_id != null
        && $request->kecamatan_id != null && $request->kelurahan_id != null
        && $request->rt == null  && $request->rw == null) {

            $koordinators = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                },
                'detailUser.tingkatKoordinator',
                'detailUser.propinsi',
                'detailUser.kabupaten',
                'detailUser.kecamatan',
                'detailUser.kelurahan',
                'detailUser.daftarAnggota.user.userRoleTim' => function($q)
                {
                    $q->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('role');
                },
                'detailUser.daftarAnggota.user.detailUser'
                )
                )
                ->whereHas("userRoleTim.role", function ($q) {
                    $q->where("id", '=', 3);
                })
                ->whereHas("timRelawans", function ($r) {
                    $r->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->whereHas('detailUser', function ($s) use ($request) {
                    $s->where('kelurahan_id', $request->kelurahan_id);
                })->whereHas('detailUser.tingkatKoordinator', function ($t) {
                    $t->where('nama_tingkat_koordinator', 'RT/RW');
                })->get();

        } elseif ($request->tingkat_koordinator == 'rt/rw' && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null  && $request->rt != null  && $request->rw != null) {
            $koordinators = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                },'detailUser.tingkatKoordinator',
                'detailUser.propinsi',
                'detailUser.kabupaten',
                'detailUser.kecamatan',
                'detailUser.kelurahan',
                'detailUser.daftarAnggota.user.userRoleTim' => function($q)
                {
                    $q->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('role');
                },
                'detailUser.daftarAnggota.user.detailUser'
                )
)
                ->whereHas("userRoleTim.role", function ($q) {
                    $q->where("id", '=', 3);
                })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->whereHas('detailUser', function ($q) use ($request) {
                    $q->where('rt', $request->rt);
                })->whereHas('detailUser', function ($q) use ($request) {
                    $q->where('rw', $request->rw);
                })
                ->whereHas('detailUser.tingkatKoordinator', function ($q) {
                    $q->where('nama_tingkat_koordinator', 'RT/RW');
                })->get();
        } else {
            return response()->json([
                'message' => 'Parameter not valid',
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($koordinators != null) {
            return response()->json([
                'message' => 'List of koordinators',
                'data' => $koordinators,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No koordinators available',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function addKoordinator(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(6)) {
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
            'kabupaten_id' => 'nullable|numeric',
            'kecamatan_id' => 'nullable|numeric',
            'kelurahan_id' => 'nullable|numeric',
            'nama_tingkat_koordinator' => 'required|in:Kelurahan,RT/RW,Kecamatan,Kota/Kab,Provinsi',
            'rt' => 'nullable|numeric',
            'rw' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'relawan_id.*' => 'nullable|numeric',
            'saksi_id.*' => 'nullable|numeric',
            'custom_url' => 'required|url'
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
                    'role_id' => 3,
                ]);

                if ($request->relawan_id != null) {
                    for ($i = 0; $i < count($request->relawan_id); $i++) {
                        $anggotaRelawan = DaftarAnggota::create([
                            'detail_user_id' =>  $checkingEmail->detailUser->id,
                            'user_id' =>  $request->relawan_id[$i]
                        ]);
                    }
                }
                if ($request->saksi_id != null) {
                    for ($i = 0; $i < count($request->saksi_id); $i++) {
                        $anggotaSaksi = DaftarAnggota::create([
                            'detail_user_id' =>  $checkingEmail->detailUser->id,
                            'user_id' =>  $request->saksi_id[$i]
                        ]);
                    }
                }

                $url = $request->custom_url;
                Notification::send($inv, new InvitationKoordinatorNotification($url));

                return response()->json([
                    'message' => 'User koordinator has been created & invited',
                    'dataUser' => $checkingEmail,
                ], Response::HTTP_OK);
            }

            DB::beginTransaction();

            $userKoordinator = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]);
            $userKoordinator->timRelawans()->attach($current_team_id);

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
            $tingkatKoordinator = TingkatKoordinator::create([
                'detail_user_id' =>  $detailUser->id,
                'nama_tingkat_koordinator' => $request->nama_tingkat_koordinator
            ]);

            if ($request->relawan_id != null) {
                for ($i = 0; $i < count($request->relawan_id); $i++) {
                    $anggotaRelawan = DaftarAnggota::create([
                        'detail_user_id' =>  $detailUser->id,
                        'user_id' =>  $request->relawan_id[$i]
                    ]);
                }
            }
            if ($request->saksi_id != null) {
                for ($i = 0; $i < count($request->saksi_id); $i++) {
                    $anggotaSaksi = DaftarAnggota::create([
                        'detail_user_id' =>  $detailUser->id,
                        'user_id' =>  $request->saksi_id[$i]
                    ]);
                }
            }
            $tokenPassword = PasswordReset::create([
                'token' => $token,
                'email' => $email,
            ]);

            $inv = InvitationUser::create([
                'user_id' => $userKoordinator->id,
                'email' => $email,
                'token' => $tokenPassword->token,
            ]);

            UserRoleTim::create([
                'user_id' => $userKoordinator->id,
                'tim_relawan_id' => $current_team_id,
                'role_id' => 3,
            ]);

            // $url = URL::temporarySignedRoute('newpassword.koordinator', now()->addHour(), ['email' => 'user@example.com']);
            $url = $request->custom_url . '/new-password?token=' . $token;
            Notification::send($inv, new InvitationKoordinatorNotification($url));
            DB::commit();

            return response()->json([
                'message' => 'User koordinator has been created & invited',
                'dataUser' => $userKoordinator,
                'detailUser' => $detailUser,
                'tingkatKoordinator ' => $tingkatKoordinator,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, user koordinator cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateKoordinator(Request $request, $id)
    {
        // if (!Auth::user()->customHasPermissionTo(6)) {
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
            'rt' => 'nullable|numeric',
            'rw' => 'nullable|numeric',
            'nama_tingkat_koordinator' => 'required|in:Kelurahan,RT/RW,Kecamatan,Kota/Kab,Provinsi',
            'relawan_id.*' => 'nullable|numeric',
            'saksi_id.*' => 'nullable|numeric',
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

            $tingkatKoordinator = TingkatKoordinator::find($detailUser->tingkatKoordinator->id);
            $tingkatKoordinator->forceFill([
                'detail_user_id' =>  $detailUser->id,
                'nama_tingkat_koordinator' => $request->nama_tingkat_koordinator
            ])->save();

            DaftarAnggota::where('detail_user_id',  $detailUser->id)->delete();
            if ($request->relawan_id != null) {
                for ($i = 0; $i < count($request->relawan_id); $i++) {
                    $anggotaRelawan = DaftarAnggota::create([
                        'detail_user_id' =>  $detailUser->id,
                        'user_id' =>  $request->relawan_id[$i]
                    ]);
                }
            }
            if ($request->saksi_id != null) {
                for ($i = 0; $i < count($request->saksi_id); $i++) {
                    $anggotaSaksi = DaftarAnggota::create([
                        'detail_user_id' =>  $detailUser->id,
                        'user_id' =>  $request->saksi_id[$i]
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'message' => 'User koordinator has been updated.',
                'user' => $findUser,
                'tingkatKoordinator' => $tingkatKoordinator,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, user koordinator cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showKoordinator($id)
    {
        // if (!Auth::user()->customHasPermissionTo(6)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        try {
            if ($userKoordinator  = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                },
                'detailUser.tingkatKoordinator', 'detailUser.tingkatKoordinator',
                'detailUser.propinsi', 'detailUser.kabupaten', 'detailUser.kecamatan',
                'detailUser.kelurahan',
                'detailUser.daftarAnggota.user.userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('role', 'user.detailUser');
                },
                )
                )
            ->whereHas("userRoleTim.role", function ($q) {
                $q->where("id", 3);
            })->orderBy('created_at', 'desc')->find($id)) {
                return response()->json([
                    'message' => 'Detail User.',
                    'data' => $userKoordinator
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Sorry, koordinator not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, koordinator not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteKoordinator($id)
    {
        // if (!Auth::user()->customHasPermissionTo(6)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        try {
            $user = User::find($id);
            $user->timRelawans()->detach();
            $user->delete();
            return response()->json([
                'message' => 'Koordinator has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Sistemnya dia detach jadi ketika hapus user hanya hapus dari tim relawan jika dia tidak punya tim relawan dia akan terhapus
            return response()->json([
                'message' => 'Koordinator has ben deleted.',
            ], Response::HTTP_OK);
        }

        // catch (\Exception $e) {
        //     return response()->json([
        //         'message' => 'Sorry, koordinator cannot be deleted.',
        //     ], Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
    }

    public function logPresensiKoordinator(Request $request)
    {
        $presensi = CheckinCheckout::where([['user_id', $request->id_user], ['tim_relawan_id', Auth::user()->current_team_id]])->orderBy('date', 'desc')->paginate(10);
        return response()->json([
            'message' => 'Log Presensi Koordinator.',
            'data' => $presensi
        ], Response::HTTP_OK);
    }

    public function listRelawanKelurahan(Request $request)
    {

        if($request->search != null)
        {
            $relawans = User::
                whereHas("userRoleTim.role", function ($q) {
                    $q->where("id", '=', 4);
                })
                ->whereHas("timRelawans", function ($q) {
                    $q->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })
                ->whereHas("detailUser", function($q) use($request) {
                    $q->where("kelurahan_id", '=', $request->kelurahan_id);
                })
                ->orderBy('created_at', 'desc')->where("name", "LIKE", "%$request->search%")->get();
        }
        else
        {
            $relawans = User::
                whereHas("userRoleTim.role", function ($q) {
                    $q->where("id", '=', 4);
                })
                ->whereHas("timRelawans", function ($q) {
                    $q->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })
                ->whereHas("detailUser", function($q) use($request) {
                    $q->where("kelurahan_id", '=', $request->kelurahan_id);
                })
                ->orderBy('created_at', 'desc')->get();
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

    public function listSaksiKelurahan(Request $request)
    {
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
                ->whereHas("detailUser", function($q) use($request) {
                    $q->where("kelurahan_id", '=', $request->kelurahan_id);
                })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->orderBy('created_at', 'desc')->where("name", "LIKE", "%$request->search%")->get();
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
                ->whereHas("detailUser", function($q) use($request) {
                    $q->where("kelurahan_id", '=', $request->kelurahan_id);
                })
                ->whereHas("timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->orderBy('created_at', 'desc')->get();
        }
        if ($saksis != null) {
            return response()->json([
                'message' => 'List of saksi',
                'data' => $saksis,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No saksi available',
            ], Response::HTTP_OK);
        }
    }
}
