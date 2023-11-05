<?php

namespace App\Http\Controllers\Api\JumlahDPT;

use App\Http\Controllers\Controller;
use App\Imports\JumlahDPTImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class FileImportJumlahDPTController extends Controller
{
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
            'template_file_jumlah_dpt' => 'required|file|mimes:xls,csv,xlsx',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {
            //code...

            $propinsi = $request->propinsi_id;
            $kabupaten = $request->kabupaten_id;
            $kecamatan = $request->kecamatan_id;
            $dapil = $request->dapil;
            $findCurrentTeam = Auth::user()->current_team_id;
            Excel::import(new JumlahDPTImport($propinsi, $kabupaten, $kecamatan, $findCurrentTeam,$dapil), $request->file('template_file_jumlah_dpt')->store('temp'));

            DB::commit();
            return response()->json([
                'message' => 'Data Jumlah DPT Import Successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return response()->json([
                'message' => 'Data Jumlah DPT Import Failed | must be more than 3 row'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}