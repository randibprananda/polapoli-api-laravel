<?php

namespace App\Http\Controllers\Api\Rekapitulasi;

use App\Http\Controllers\Controller;
use App\Models\DetailUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RekapitulasiKoordinatorController extends Controller
{
    public function rekapitulasiKoordinator(Request $request)
    {
        // manajemen_koordinator
        // if (!Auth::user()->customHasPermissionTo(6)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        if ($request->tingkat_koordinator == 'propinsi' &&  $request->propinsi_id == null && $request->kabupaten_id == null && $request->kecamatan_id == null && $request->kelurahan_id == null) {
            $koordinators = DetailUser::with('user.timRelawans', 'propinsi')
                ->whereHas("user.userRoleTim.role", function ($q) {
                    $q->where("id", '=', 3);
                })
                ->whereHas("user.timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })
                ->whereHas("tingkatKoordinator", function ($p) {
                    $p->where("nama_tingkat_koordinator", '=', 'Provinsi');
                })
                ->select(
                    'propinsi_id',
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                )
                ->groupBy('propinsi_id')->get();
        } else if ($request->tingkat_koordinator == 'kabupaten' && $request->propinsi_id != null && $request->kabupaten_id == null && $request->kecamatan_id == null && $request->kelurahan_id == null) {
            $koordinators = DetailUser::with('user.timRelawans', 'propinsi')
                ->whereHas("user.userRoleTim.role", function ($q) {
                    $q->where("id", '=', 3);
                })
                ->whereHas("user.timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })
                ->whereHas("tingkatKoordinator", function ($p) {
                    $p->where("nama_tingkat_koordinator", '=', 'Kota/Kab');
                })
                ->select(
                    'propinsi_id',
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                )
                ->groupBy('propinsi_id')->get();
        } else if ($request->tingkat_koordinator == 'kecamatan' && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null && $request->kelurahan_id == null) {
            $koordinators = DetailUser::with('user.timRelawans', 'propinsi')
                ->whereHas("user.userRoleTim.role", function ($q) {
                    $q->where("id", '=', 3);
                })
                ->whereHas("user.timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })
                ->whereHas("tingkatKoordinator", function ($p) {
                    $p->where("nama_tingkat_koordinator", '=', 'Kecamatan');
                })
                ->select(
                    'propinsi_id',
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                )
                ->groupBy('propinsi_id')->get();
        } else if ($request->tingkat_koordinator == 'kelurahan' && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null) {
            $koordinators = DetailUser::with('user.timRelawans', 'propinsi')
                ->whereHas("user.userRoleTim.role", function ($q) {
                    $q->where("id", '=', 3);
                })
                ->whereHas("user.timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })
                ->whereHas("tingkatKoordinator", function ($p) {
                    $p->where("nama_tingkat_koordinator", '=', 'Kelurahan');
                })
                ->select(
                    'propinsi_id',
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                )
                ->groupBy('propinsi_id')->get();
        } else if ($request->tingkat_koordinator == 'rt/rw' && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null) {
            $koordinators = DetailUser::with('user.timRelawans', 'propinsi')
                ->whereHas("user.userRoleTim.role", function ($q) {
                    $q->where("id", '=', 3);
                })
                ->whereHas("user.timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })
                ->whereHas("tingkatKoordinator", function ($p) {
                    $p->where("nama_tingkat_koordinator", '=', 'RT/RW');
                })
                ->select(
                    'propinsi_id',
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                )
                ->groupBy('propinsi_id')->get();
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
                'message' => 'No koordinators found',
            ], Response::HTTP_OK);
        }
    }
}
