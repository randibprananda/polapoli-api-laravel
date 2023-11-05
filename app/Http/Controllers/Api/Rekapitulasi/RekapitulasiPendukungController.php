<?php

namespace App\Http\Controllers\Api\Rekapitulasi;

use App\Http\Controllers\Controller;
use App\Models\DPT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RekapitulasiPendukungController extends Controller
{
    public function rekapitulasiPemilihPendukung(Request $request)
    {
        // manajemen_dpt
        // if (!Auth::user()->customHasPermissionTo(5)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        if ($request->propinsi_id == null && $request->kabupaten_id == null && $request->kecamatan_id == null) {
            $dpts = DPT::with('propinsi')->where([['tim_relawan_id', Auth::user()->current_team_id]])
                ->select(
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                    'propinsi_id',
                )->groupBy('propinsi_id')
                ->get();
        } elseif ($request->propinsi_id != null && $request->kabupaten_id == null && $request->kecamatan_id == null) {
            $dpts = DPT::with('kabupaten')->where([['tim_relawan_id', Auth::user()->current_team_id], ['propinsi_id', $request->propinsi_id]])
                ->select(
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                    'kabupaten_id'
                )->groupBy('kabupaten_id')
                ->get();
        } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
            $dpts = DPT::with('kecamatan')->where([['tim_relawan_id', Auth::user()->current_team_id], ['kabupaten_id', $request->kabupaten_id]])
                ->select(
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                    'kecamatan_id'
                )->groupBy('kecamatan_id')
                ->get();
        } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null) {
            $dpts = DPT::with('kelurahan')->where([['tim_relawan_id', Auth::user()->current_team_id], ['kecamatan_id', $request->kecamatan_id]])
                ->select(
                    DB::raw("sum(case when jenis_kelamin = 'L' then 1 end) as laki_laki"),
                    DB::raw("sum(case when jenis_kelamin = 'P' then 1 end) as perempuan"),
                    DB::raw('count(*) as total'),
                    'kelurahan_id'
                )->groupBy('kelurahan_id')
                ->get();
        } else {
            return response()->json([
                'message' => 'Request not valid',
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($dpts != null) {
            return response()->json([
                'message' => 'List of dpt',
                'data' => $dpts,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No dpts available',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
