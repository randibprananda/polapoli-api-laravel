<?php

namespace App\Http\Controllers\Api\Logistik;

use App\Http\Controllers\Controller;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class StokBarangController extends Controller
{
    public function index()
    {
        // manajemen_logistik
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $stokBarang = StokBarang::where('tim_relawan_id',Auth::user()->current_team_id)
        ->withCount(['penerimaanBarangs as total_masuk' => function($query) {
            $query->select(DB::raw('SUM(jumlah_diterima)'));
        }
        ])->withCount(['pengeluaranBarang as total_keluar' => function($query) {
            $query->select(DB::raw('SUM(jumlah_pengeluaran)'));
        }])->with('propinsi', 'kabupaten', 'kecamatan', 'kelurahan','pemesananBarangs',
            'pemesananBarangs.propinsi','pemesananBarangs.kabupaten','pemesananBarangs.kecamatan','pemesananBarangs.kelurahan')
        ->get();
        if ($stokBarang != null) {
            return response()->json([
                'message' => 'List of Stok Barang',
                'data' => $stokBarang,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No stokBarang available',
            ], Response::HTTP_OK);
        }
    }

    public function store(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'harga_satuan' => 'required|numeric',
            'nama_satuan' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $tim_relawan_id = Auth::user()->current_team_id;
            $stokBarang = StokBarang::create(
                [
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'dapil' => $request->dapil,
                    'nama_barang' => $request->nama_barang,
                    'harga_satuan' => $request->harga_satuan,
                    'nama_satuan' => $request->nama_satuan,
                    'created_by' => Auth::user()->id,
                    'rt' => $request->rt,
                    'rw' => $request->rw,
                    'keterangan' => $request->keterangan,
                    'tim_relawan_id' => $tim_relawan_id
                ]
            );

            return response()->json([
                'message' => 'Stok barang has been created',
                'data' => $stokBarang
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, stok barang cannot be created',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            // if (!Auth::user()->customHasPermissionTo(12)) {
            //     return response()->json([
            //         'message' => 'FORBIDDEN',
            //     ], Response::HTTP_FORBIDDEN);
            // }
            if ($stokBarang = StokBarang::where('tim_relawan_id',Auth::user()->current_team_id)
            ->with(['propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'pemesananBarangs', 'historyLogistikStok'])->find($id)) {
                return response()->json([
                    'message' => 'Detail Stok Barang.',
                    'data' => $stokBarang
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, stok barang not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'harga_satuan' => 'required|numeric',
            'nama_satuan' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $findStokBarang = StokBarang::where('tim_relawan_id',Auth::user()->current_team_id)
            ->find($id);
            if ($findStokBarang != null) {
                $findStokBarang->forceFill(
                    [
                        'propinsi_id' => $request->propinsi_id,
                        'kabupaten_id' => $request->kabupaten_id,
                        'kecamatan_id' => $request->kecamatan_id,
                        'kelurahan_id' => $request->kelurahan_id,
                        'rt' => $request->rt,
                        'rw' => $request->rw,
                        'keterangan' => $request->keterangan,
                        'dapil' => $request->dapil,
                        'nama_barang' => $request->nama_barang,
                        'harga_satuan' => $request->harga_satuan,
                        'nama_satuan' => $request->nama_satuan,
                        'created_by' => Auth::user()->id,
                    ]
                )->save();

                return response()->json([
                    'message' => 'Stok barang has been updated.',
                    'data' => $findStokBarang
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Stok barang not found.',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, stok barang cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        try {
            StokBarang::where('tim_relawan_id',Auth::user()->current_team_id)->find($id)->delete();
            return response()->json([
                'message' => 'Stok barang has been deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, stok barang cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
