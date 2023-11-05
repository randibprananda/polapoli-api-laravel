<?php

namespace App\Http\Controllers\Api\TPS;

use App\Http\Controllers\Controller;
use App\Imports\TPSImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Maatwebsite\Excel\Facades\Excel;

class FileImportController extends Controller
{
    public function __invoke(Request $request)
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
            'dapil' => 'numeric',
            'file-tps' => 'required|file|mimes:xls,csv,xlsx',
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
            Excel::import(new TPSImport($propinsi, $kabupaten, $kecamatan, $findCurrentTeam,$dapil), $request->file('file-tps')->store('temp'));

            DB::commit();
            return response()->json([
                'message' => 'Data TPS Import Successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Data TPS Import Failed | must be more than 3 row'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}