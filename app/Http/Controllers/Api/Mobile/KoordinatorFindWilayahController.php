<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KoordinatorFindWilayahController extends Controller
{
    public function findByWilayah(Request $request)
    {
        if ($request->tingkat_koordinator == 'propinsi'  && $request->propinsi_id == null && $request->kabupaten_id == null  && $request->kecamatan_id == null && $request->kelurahan_id == null) {
            $koordinators = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('role');
                },'detailUser.tingkatKoordinator',
                'detailUser.propinsi')
            )
                ->whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],['tim_relawan_id', '=', Auth::user()->current_team_id]]);
                })->whereHas('detailUser.tingkatKoordinator', function ($r) {
                    $r->where('nama_tingkat_koordinator', 'Provinsi');
                })->get();
        } elseif ($request->tingkat_koordinator == 'kabupaten'  && $request->propinsi_id != null && $request->kabupaten_id == null  && $request->kecamatan_id == null && $request->kelurahan_id == null  && $request->rt == null  && $request->rw == null) {

            $koordinators = User::with(

                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('role');
                },'detailUser.tingkatKoordinator',
                'detailUser.propinsi', 'detailUser.kabupaten')
               )
                ->whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],
                    ['tim_relawan_id', '=', Auth::user()->current_team_id]]);
                })->whereHas('detailUser', function ($r) use ($request) {
                    $r->where('propinsi_id', $request->propinsi_id);
                })->whereHas('detailUser.tingkatKoordinator', function ($s) {
                    $s->where('nama_tingkat_koordinator', 'Kota/Kab');
                })->get();
        } elseif ($request->tingkat_koordinator == 'kecamatan' && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null && $request->kelurahan_id == null  && $request->rt == null  && $request->rw == null) {
            $koordinators = User::with(

                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('role');
                },
                'detailUser.tingkatKoordinator',
                'detailUser.propinsi',
                'detailUser.kabupaten',
                'detailUser.kecamatan')
               )
                ->whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],['tim_relawan_id', '=', Auth::user()->current_team_id]]);
                })->whereHas('detailUser', function ($r) use ($request) {
                    $r->where('kabupaten_id', $request->kabupaten_id);
                })->whereHas('detailUser.tingkatKoordinator', function ($s) {
                    $s->where('nama_tingkat_koordinator', 'kecamatan');
                })->get();
        } elseif ($request->tingkat_koordinator == 'kelurahan' && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null  && $request->rt == null  && $request->rw == null) {
            $koordinators = User::with(

                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('role');
                },
                'detailUser.tingkatKoordinator',
                'detailUser.propinsi',
                'detailUser.kabupaten',
                'detailUser.kecamatan',
                'detailUser.kelurahan')
                )
                ->whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],['tim_relawan_id', '=',
                    Auth::user()->current_team_id]]);
                })->whereHas('detailUser', function ($r) use ($request) {
                    $r->where('kecamatan_id', $request->kecamatan_id);
                })->whereHas('detailUser.tingkatKoordinator', function ($s) {
                    $s->where('nama_tingkat_koordinator', 'kelurahan');
                })->get();
        } elseif ($request->tingkat_koordinator == 'rt/rw' && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null  && $request->rt == null  && $request->rw == null) {

            $koordinators = User::with(

                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('role');
                },
                'detailUser.tingkatKoordinator',
                'detailUser.daftarAnggota',
                'detailUser.propinsi',
                'detailUser.kabupaten',
                'detailUser.kecamatan',
                'detailUser.kelurahan'))
                ->whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],['tim_relawan_id', '=',
                    Auth::user()->current_team_id]]);
                })->whereHas('detailUser', function ($r) use ($request) {
                    $r->where('kelurahan_id', $request->kelurahan_id);
                })->whereHas('detailUser.tingkatKoordinator', function ($s) {
                    $s->where('nama_tingkat_koordinator', 'RT/RW');
                })->get();
        } elseif ($request->tingkat_koordinator == 'rt/rw' && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null  && $request->rt != null  && $request->rw != null) {
            $koordinators = User::with(

                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('role','timRelawan');
                },'detailUser.tingkatKoordinator',
                'detailUser.propinsi',
                'detailUser.kabupaten',
                'detailUser.kecamatan',
                'detailUser.kelurahan',
                'detailUser.daftarAnggota.user.userRoleTim' => function($r)
                    {
                        $r->where("tim_relawan_id", '=',
                        Auth::user()->current_team_id)
                        ->with('role');
                    }
                ))
                ->whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],['tim_relawan_id', '=',
                    Auth::user()->current_team_id]]);
                })->whereHas('detailUser', function ($r) use ($request) {
                    $r->where('rt', $request->rt);
                })->whereHas('detailUser', function ($s) use ($request) {
                    $s->where('rw', $request->rw);
                })
                ->whereHas('detailUser.tingkatKoordinator', function ($t) {
                    $t->where('nama_tingkat_koordinator', 'RT/RW');
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
}
