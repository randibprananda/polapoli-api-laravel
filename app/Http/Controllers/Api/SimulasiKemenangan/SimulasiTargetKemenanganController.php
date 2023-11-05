<?php

namespace App\Http\Controllers\Api\SimulasiKemenangan;

use App\Http\Controllers\Controller;
use App\Models\DPT;
use App\Models\JumlahDpt;
use App\Models\Paslon;
use App\Models\Pendukung;
use App\Models\ViewSimulasiWebKemenangan;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SimulasiTargetKemenanganController extends Controller
{
    public function index(Request $request)
    {
        try {
            $countPaslon = Paslon::where('tim_relawan_id',Auth::user()->current_team_id)->count();
            $totalJumlahDPT = ViewSimulasiWebKemenangan::select(
                DB::raw('sum(total_lk_pr) as total_jumlah_dpt')
            )->where('tim_relawan_id_left', Auth::user()->current_team_id)->first();
            if ($request->propinsi_id == null  && $request->kabupaten_id == null && $request->kecamatan_id == null) {
                $data = ViewSimulasiWebKemenangan::with('propinsi')->where('tim_relawan_id_left', Auth::user()->current_team_id)
                    ->groupBy('propinsi_id_left')
                    ->select(
                        'propinsi_id_left as propinsi_id',
                        DB::raw('sum(total_lk_pr) as jumlah_dpt'),
                        DB::raw('sum(jml_pendukung) as jumlah_pendukung'),
                    )->get();
            } elseif ($request->propinsi_id != null  && $request->kabupaten_id == null && $request->kecamatan_id == null) {
                $data = ViewSimulasiWebKemenangan::with('kabupaten')->where([['tim_relawan_id_left', Auth::user()->current_team_id], ['propinsi_id_left', $request->propinsi_id]])
                    ->groupBy('kabupaten_id_left')
                    ->select(
                        'kabupaten_id_left as kabupaten_id',
                        DB::raw('sum(total_lk_pr) as jumlah_dpt'),
                        DB::raw('sum(jml_pendukung) as jumlah_pendukung'),
                    )->get();
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
                $data = ViewSimulasiWebKemenangan::with('kecamatan')->where([['tim_relawan_id_left', Auth::user()->current_team_id], ['kabupaten_id_left', $request->kabupaten_id]])
                    ->groupBy('kecamatan_id_left')
                    ->select(
                        'kecamatan_id_left as kecamatan_id',
                        DB::raw('sum(total_lk_pr) as jumlah_dpt'),
                        DB::raw('sum(jml_pendukung) as jumlah_pendukung'),
                    )->get();
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null) {
                $data = ViewSimulasiWebKemenangan::with('kelurahan')->where([['tim_relawan_id_left', Auth::user()->current_team_id], ['kecamatan_id_left', $request->kecamatan_id]])
                    ->groupBy('kelurahan_id_left')
                    ->select(
                        'kelurahan_id_left as kelurahan_id',
                        DB::raw('sum(total_lk_pr) as jumlah_dpt'),
                        DB::raw('sum(jml_pendukung) as jumlah_pendukung'),
                    )->get();
            }

            return response()->json([
                'message' => 'List of Jumlah DPT & Pendukung',
                'data' => $data,
                'total_paslon' => $countPaslon,
                'total_jumlah_dpt' => $totalJumlahDPT,


            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No jumlah dpt available',
            ], Response::HTTP_OK);
        }
    }
}
