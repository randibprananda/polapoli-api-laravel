<?php

namespace App\Http\Controllers\Api\DPT;

use App\Http\Controllers\Controller;
use App\Imports\DPTImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class FileImportController extends Controller
{
    public function __invoke(Request $request)
    {
        //  management dpt
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
            'dapil' => 'numeric',
            'file_dpt' => 'required|file|mimes:xls,csv,xlsx'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {


            $propinsi_id = $request->propinsi_id;
            $kabupaten_id = $request->kabupaten_id;
            $kecamatan_id = $request->kecamatan_id;
            $kelurahan_id = $request->kelurahan_id;
            $dapil = $request->dapil;

            Excel::import(new DPTImport($propinsi_id, $kabupaten_id, $kecamatan_id, $kelurahan_id,$dapil), $request->file('file_dpt')->store('temp'));

            DB::commit();
            return response()->json([
                'message' => 'Data DPT Import Successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Data DPT Import Failed, check your list data, dont duplicate nik, record must be more than 4 row',
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
