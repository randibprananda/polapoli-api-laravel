<?php

namespace App\Http\Controllers\Api\Rekapitulasi;

use App\Http\Controllers\Controller;
use App\Models\DetailUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RekapitulasiSaksiController extends Controller
{
    public function rekapitulasiSaksi(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(8)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        if ($request->propinsi_id == null) {
            $relawans = DetailUser::with('user.timRelawans', 'propinsi')
                ->whereHas("user.userRoleTim.role", function ($q) {
                    $q->where("id", '=', 5);
                })
                ->whereHas("user.timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })
                ->select(
                    'propinsi_id',
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                )
                ->groupBy('propinsi_id')->get();
        } else if ($request->propinsi_id != null && $request->kabupaten_id == null) {
            $relawans = DetailUser::with('user.timRelawans', 'kabupaten')
                ->where('propinsi_id', $request->propinsi_id)
                ->whereHas("user.userRoleTim.role", function ($q) {
                    $q->where("id", '=', 5);
                })
                ->whereHas("user.timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })
                ->select(
                    'kabupaten_id',
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                )
                ->groupBy('kabupaten_id')->get();
        } else if ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
            $relawans = DetailUser::with('user.timRelawans', 'kecamatan')
                ->where('kabupaten_id', $request->kabupaten_id)
                ->whereHas("user.userRoleTim.role", function ($q) {
                    $q->where("id", '=', 5);
                })
                ->whereHas("user.timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })
                ->select(
                    'kecamatan_id',
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                )
                ->groupBy('kecamatan_id')->get();
        } else if ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null) {
            $relawans = DetailUser::with('user.timRelawans', 'kelurahan')
                ->where('kecamatan_id', $request->kecamatan_id)
                ->whereHas("user.userRoleTim.role", function ($q) {
                    $q->where("id", '=', 5);
                })
                ->whereHas("user.timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })
                ->select(
                    'kelurahan_id',
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                )
                ->groupBy('kelurahan_id')->get();
        } else if ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null) {
            $relawans = DetailUser::with('user.timRelawans', 'kelurahan')
                ->where('kelurahan_id', $request->kelurahan_id)
                ->whereHas("user.userRoleTim.role", function ($q) {
                    $q->where("id", '=', 5);
                })
                ->whereHas("user.timRelawans", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })
                ->select(
                    'rt',
                    'kelurahan_id',
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                )
                ->groupBy('rt', 'kelurahan_id')->get();
        } else {
            return response()->json([
                'message' => 'Request not valid',
            ], Response::HTTP_OK);
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
}
