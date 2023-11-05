<?php

namespace App\Http\Controllers\Api\JumlahDPT;

use App\Http\Controllers\Controller;
use App\Models\JumlahDpt;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class JumlahDPTController extends Controller
{
    public function getAll(Request $request)
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
                    'kabupaten_id','dapil'
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
                    'kecamatan_id','dapil'
                )
                ->groupBy('kecamatan_id')->get();
        } else if ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null) {
            $dataJumlahDpts = JumlahDpt::with('kelurahan')->where([
                ['tim_relawan_id', Auth::user()->current_team_id],
                ['kecamatan_id', $request->kecamatan_id]
            ])
                ->select(
                    'laki_laki as l',
                    'perempuan as p',
                    DB::raw('laki_laki + perempuan as total'),
                    'kelurahan_id',
                    'dapil',
                    'id'
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
    public function addJumlahDpt(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(21)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'propinsi_id' => 'required|numeric',
            'kabupaten_id' => 'required|numeric',
            'kecamatan_id' => 'required|numeric',
            'kelurahan_id' => 'required|numeric',
            'dapil' => 'required|numeric',
            'lakilaki' => 'required|numeric',
            'perempuan' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $findCurrentTeam = Auth::user()->current_team_id;
            $jumlahDpt = JumlahDpt::create(
                [
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'dapil' => $request->dapil,
                    'laki_laki' => $request->lakilaki,
                    'perempuan' => $request->perempuan,
                    'tim_relawan_id' => $findCurrentTeam
                ]
            );

            return response()->json([
                'message' => 'Jumlah DPT has been created',
                'data' => $jumlahDpt
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, jumlah dpt cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function detailJumlahDpt($id)
    {
        // if (!Auth::user()->customHasPermissionTo(21)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $jumlahDpt = JumlahDpt::with('propinsi', 'kabupaten', 'kecamatan', 'kelurahan')->where('tim_relawan_id', Auth::user()->current_team_id)->find($id);

        return response()->json([
            'message' => 'Jumlah DPT detail',
            'data' => $jumlahDpt,
        ], Response::HTTP_OK);
    }

    public function updateJumlahDpt(Request $request, $id)
    {
        // if (!Auth::user()->customHasPermissionTo(21)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'propinsi_id' => 'required|string',
            'kabupaten_id' => 'required|string',
            'kecamatan_id' => 'required|string',
            'kelurahan_id' => 'required|string',
            'dapil' => 'required|numeric',
            'lakilaki' => 'required|numeric',
            'perempuan' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $jumlahDpt = JumlahDpt::find($id);
            $kelurahan_id = strtoupper($request->kelurahan_id);
            $jumlahDpt->forceFill(
                [
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'dapil' => $request->dapil,
                    'laki_laki' => $request->lakilaki,
                    'perempuan' => $request->perempuan
                ]
            )->save();

            return response()->json([
                'message' => 'Jumlah DPT has ben updated.',
                'data' => $jumlahDpt
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, jumlah dpt cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteJumlahDpt($id)
    {
        // if (!Auth::user()->customHasPermissionTo(21)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        try {
            JumlahDpt::find($id)->delete();
            return response()->json([
                'message' => 'Jumlah dpt has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, jumlah dpt cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}