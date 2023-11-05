<?php

namespace App\Http\Controllers\Api\DPT;

use App\Exports\DptExport;
use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Propinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class FileExportDPTController extends Controller
{
    public function __invoke(Request $request)
    {
        // Management DPT
        // if (!Auth::user()->customHasPermissionTo(5)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'propinsi_id' => 'required|numeric',
            'kabupaten_id' => 'required|numeric',
            'kecamatan_id' => 'required|numeric',
            'kelurahan_id' => 'required|numeric',
            'dapil' => 'numeric'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $propinsi = Propinsi::find($request->propinsi_id)->name;
        $kabupaten = Kabupaten::find($request->kabupaten_id)->name;
        $kecamatan = Kecamatan::find($request->kecamatan_id)->name;
        $kelurahan = Kelurahan::find($request->kelurahan_id)->name;
        $dapil = $request->dapil;

        return Excel::download(new DptExport($propinsi, $kabupaten, $kecamatan, $kelurahan,$dapil), 'template-dpt.xlsx');
    }
}