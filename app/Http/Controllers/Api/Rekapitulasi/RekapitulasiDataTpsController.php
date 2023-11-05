<?php

namespace App\Http\Controllers\Api\Rekapitulasi;

use App\Http\Controllers\Controller;
use App\Models\TPS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RekapitulasiDataTpsController extends Controller
{
    public function rekapitulasiTps(Request $request)
    {
        // manajemen_tps
        // if (!Auth::user()->customHasPermissionTo(4)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        if ($request->propinsi_id == null) {
            $currentPropinsi = TPS::with('propinsi', 'kabupaten', 'kecamatan')
                ->where([
                    ['tim_relawan_id', Auth::user()->current_team_id]
                ])
                ->selectRaw('sum(jumlah_tps) as total_tps')->addSelect('propinsi_id')
                ->groupBy('propinsi_id')->get();
        } else if ($request->propinsi_id != null && $request->kabupaten_id == null) {
            $currentPropinsi = TPS::with('propinsi', 'kabupaten', 'kecamatan')
                ->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['propinsi_id', $request->propinsi_id]
                ])
                ->selectRaw('sum(jumlah_tps) as total_tps')->addSelect('kabupaten_id')
                ->groupBy('kabupaten_id')->get();
        } else if ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
            $currentPropinsi = TPS::with('propinsi', 'kabupaten', 'kecamatan')
                ->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['kabupaten_id', $request->kabupaten_id]
                ])
                ->selectRaw('sum(jumlah_tps) as total_tps')->addSelect('kecamatan_id')
                ->groupBy('kecamatan_id')->get();
        } else if ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null) {
            $currentPropinsi = TPS::where([
                ['tim_relawan_id', Auth::user()->current_team_id],
                ['kecamatan_id', $request->kecamatan_id]
            ])
                ->select(
                    DB::raw('sum(jumlah_tps) as total_tps'),
                    'kelurahan',
                )->groupBy('kelurahan')->get();
        } else {
            return response()->json([
                'message' => 'Request not valid',
            ], Response::HTTP_OK);
        }
        if ($currentPropinsi != null) {
            return response()->json([
                'message' => 'List of tps',
                'data' => $currentPropinsi,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No tps available',
            ], Response::HTTP_OK);
        }
    }
}