<?php

namespace App\Http\Controllers\Api\TPS;

use App\Http\Controllers\Controller;
use App\Models\TPS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TPSController extends Controller
{
    public function getAll(Request $request)
    {
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
                ->selectRaw('sum(jumlah_tps) as total_tps , dapil',)->addSelect('kabupaten_id')
                ->groupBy('kabupaten_id')->get();
        } else if ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
            $currentPropinsi = TPS::with('propinsi', 'kabupaten', 'kecamatan')
                ->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['kabupaten_id', $request->kabupaten_id]
                ])
                ->selectRaw('sum(jumlah_tps) as total_tps, dapil')->addSelect('kecamatan_id')
                ->groupBy('kecamatan_id')->get();
        } else if ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null) {
            $currentPropinsi = TPS::where([
                ['tim_relawan_id', Auth::user()->current_team_id],
                ['kecamatan_id', $request->kecamatan_id]
            ])
                ->select(
                    'jumlah_tps as total_tps',
                    'id as tps_id',
                    'kelurahan',
                    'dapil',
                    'keterangan',
                )->get();
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

    public function addTps(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(4)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'propinsi_id' => 'required|numeric',
            'kabupaten_id' => 'required|numeric',
            'kecamatan_id' => 'required|numeric',
            'kelurahan' => 'required|string',
            'dapil' => 'required|numeric',
            'jumlah_tps' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $findTeamRelawanId = Auth::user()->current_team_id;
            $kelurahan = strtoupper($request->kelurahan);
            $tps = TPS::create(
                [
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'dapil' => $request->dapil,
                    'jumlah_tps' => $request->jumlah_tps,
                    'kelurahan' => $kelurahan,
                    'keterangan' => $request->keterangan,
                    'tim_relawan_id' => $findTeamRelawanId,
                ]
            );

            return response()->json([
                'message' => 'TPS has been created',
                'data' => $tps
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, tps cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detailTps($id)
    {
        // if (!Auth::user()->customHasPermissionTo(4)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $tps = TPS::with('propinsi', 'kabupaten', 'kecamatan')->where('tim_relawan_id', Auth::user()->current_team_id)->find($id);
        return response()->json([
            'message' => 'TPS detail',
            'data' => $tps
        ], Response::HTTP_OK);
    }

    public function updateTps(Request $request, $id)
    {
        // if (!Auth::user()->customHasPermissionTo(4)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'propinsi_id' => 'required|numeric',
            'kabupaten_id' => 'required|numeric',
            'kecamatan_id' => 'required|numeric',
            'kelurahan' => ['required', 'string'],
            'dapil' => 'required|numeric',
            'jumlah_tps' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $tps = TPS::where('tim_relawan_id', Auth::user()->current_team_id)->find($id);
            $findTeamRelawanId = Auth::user()->current_team_id;
            $kelurahan = strtoupper($request->kelurahan);
            $tps->forceFill(
                [
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'dapil' => $request->dapil,
                    'jumlah_tps' => $request->jumlah_tps,
                    'kelurahan' => $kelurahan,
                    'keterangan' => $request->keterangan,
                    'tim_relawan_id' => $findTeamRelawanId,
                ]
            )->save();

            return response()->json([
                'message' => 'TPS has ben updated.',
                'data' => $tps
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, tps cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteTps($id)
    {
        // if (!Auth::user()->customHasPermissionTo(4)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        try {
            TPS::where('tim_relawan_id', Auth::user()->current_team_id)->find($id)->delete();
            return response()->json([
                'message' => 'TPS has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, tps cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}