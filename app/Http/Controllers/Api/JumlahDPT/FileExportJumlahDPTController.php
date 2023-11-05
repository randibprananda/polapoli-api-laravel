<?php

namespace App\Http\Controllers\Api\JumlahDPT;

use App\Exports\JumlahDPTExport;
use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Propinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class FileExportJumlahDPTController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // manajemen_jumlah_dpt
        // if (!Auth::user()->customHasPermissionTo(21)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'propinsi_id' => 'required|numeric',
            'kabupaten_id' => 'required|numeric',
            'kecamatan_id' => 'required|numeric',
            'dapil' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $propinsi = Propinsi::find($request->propinsi_id)->name;
        $kabupaten = Kabupaten::find($request->kabupaten_id)->name;
        $kecamatan = Kecamatan::find($request->kecamatan_id)->name;
        $dapil = $request->dapil;

        return Excel::download(new JumlahDPTExport($propinsi, $kabupaten, $kecamatan,$dapil), 'template-jumlah-dpt.xlsx');
    }
}