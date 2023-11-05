<?php

namespace App\Http\Controllers\Api\Rekapitulasi;

use App\Http\Controllers\Controller;
use App\Models\JumlahDpt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RekapitulasiPemilihTetapManualController extends Controller
{
    public function rekapitulasiDptManual(Request $request)
    {
        // manajemen_jumlah_dpt
        // if (!Auth::user()->customHasPermissionTo(21)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        if ($request->propinsi_id == null) {
            $dataJumlahDpts = JumlahDpt::with('propinsi', 'kabupaten', 'kecamatan')
                ->where([
                    ['tim_relawan_id', Auth::user()->current_team_id]
                ])
                ->select(
                    DB::raw('sum(laki_laki) as l'),
                    DB::raw('sum(perempuan) as p'),
                    DB::raw('sum(laki_laki) + sum(perempuan) as total'),
                    'propinsi_id'
                )
                ->groupBy('propinsi_id')->get();
        } else if ($request->propinsi_id != null && $request->kabupaten_id == null) {
            $dataJumlahDpts = JumlahDpt::with('propinsi', 'kabupaten', 'kecamatan')
                ->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['propinsi_id', $request->propinsi_id]
                ])
                ->select(
                    DB::raw('sum(laki_laki) as l'),
                    DB::raw('sum(perempuan) as p'),
                    DB::raw('sum(laki_laki) + sum(perempuan) as total'),
                    'kabupaten_id'
                )
                ->groupBy('kabupaten_id')->get();
        } else if ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
            $dataJumlahDpts = JumlahDpt::with('propinsi', 'kabupaten', 'kecamatan')
                ->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['kabupaten_id', $request->kabupaten_id]
                ])
                ->select(
                    DB::raw('sum(laki_laki) as l'),
                    DB::raw('sum(perempuan) as p'),
                    DB::raw('sum(laki_laki) + sum(perempuan) as total'),
                    'kecamatan_id'
                )
                ->groupBy('kecamatan_id')->get();
        } else if ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null) {
            $dataJumlahDpts = JumlahDpt::with("kelurahan")->where([
                ['tim_relawan_id', Auth::user()->current_team_id],
                ['kecamatan_id', $request->kecamatan_id]
            ])
                ->select(
                    'laki_laki as l',
                    'perempuan as p',
                    DB::raw('laki_laki + perempuan as total'),
                    'id',
                )->get();
        } else {
            return response()->json([
                'message' => 'Request not valid',
            ], Response::HTTP_OK);
        }
        if ($dataJumlahDpts != null) {
            return response()->json([
                'message' => 'List of Jumlah DPT',
                'data' =>  $dataJumlahDpts,

            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No jumlah dpt available',
            ], Response::HTTP_OK);
        }
    }
}
