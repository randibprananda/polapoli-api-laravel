<?php

namespace App\Http\Controllers\Api\RuteHarian;

use App\Http\Controllers\Controller;
use App\Models\RuteRelawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RuteHarianController extends Controller
{
    public function getJadwalRute()
    {
        $data = RuteRelawan::with('propinsi','kabupaten','kecamatan','kelurahan')->where('tim_relawan_id', Auth::user()->current_team_id)->orderBy('id', "DESC")->get();
        if ($data != null) {
            return response()->json([
                'message' => 'List of rute harian',
                'data' => $data,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No data available',
            ], Response::HTTP_OK);
        }
    }

    public function getRiwayatRute()
    {
        $data = RuteRelawan::with('propinsi','kabupaten','kecamatan','kelurahan')->where('tim_relawan_id', Auth::user()->current_team_id)
                ->orderBy('id', "DESC")->get();
        if ($data != null) {
            return response()->json([
                'message' => 'List of rute harian',
                'data' => $data,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No data available',
            ], Response::HTTP_OK);
        }
    }
}
