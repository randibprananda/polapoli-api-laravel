<?php

namespace App\Http\Controllers\Api\Logistik;

use App\Http\Controllers\Controller;
use App\Models\PemesananBarang;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PemesananBarangController extends Controller
{
    public function index()
    {
        // manajemen_logistik
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $pemesananBarang = PemesananBarang::
        whereHas("stokBarang", function ($p) {
            $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
        })->
        with(['propinsi', 'kabupaten', 'kecamatan', 'kelurahan','stokBarang', 'penerimaanBarangs'])->get();
        if ($pemesananBarang != null) {
            return response()->json([
                'message' => 'List of Pemesanan Barang',
                'data' => $pemesananBarang,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No pemesanan barang available',
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
            'stok_barang_id' => 'required|numeric',
            'jumlah_pesanan' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $findStokBarang = StokBarang::find($request->stok_barang_id);

        $estimasiHargaTotal = ($findStokBarang->harga_satuan * $request->jumlah_pesanan);
        try {
            $pemesananBarang = PemesananBarang::create(
                [
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'rt' => $request->rt,
                    'rw' => $request->rw,
                    'dapil' => $request->dapil,
                    'stok_barang_id' => $request->stok_barang_id,
                    'jumlah_pesanan' => $request->jumlah_pesanan,
                    'sisa_pesanan' => $request->jumlah_pesanan,
                    'keterangan' => $request->keterangan,
                    'estimasi_harga_total' => $estimasiHargaTotal,
                ]
            );

            return response()->json([
                'message' => 'Pemesanan barang has been created',
                'data' => $pemesananBarang
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, pemesanan barang cannot be created',
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
            if ($pemesananBarang = PemesananBarang::
                whereHas("stokBarang", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->
                with(['propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'stokBarang', 'penerimaanBarangs'])->find($id)) {
                return response()->json([
                    'message' => 'Detail pemesanan barang.',
                    'data' => $pemesananBarang
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, data pemesanan barang not found.',
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
            'stok_barang_id' => 'required|numeric',
            'jumlah_pesanan' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

            $findStok = StokBarang::find($request->stok_barang_id);

            $estimasiHargaTotal = ($findStok->harga_satuan * $request->jumlah_pesanan);

            $findPemesananBarang = PemesananBarang::find($id);
            if ($findPemesananBarang != null) {
                $findPemesananBarang->forceFill(
                    [
                        'propinsi_id' => $request->propinsi_id,
                        'kabupaten_id' => $request->kabupaten_id,
                        'kecamatan_id' => $request->kecamatan_id,
                        'kelurahan_id' => $request->kelurahan_id,
                        'rt' => $request->rt,
                        'rw' => $request->rw,
                        'dapil' => $request->dapil,
                        'stok_barang_id' => $request->stok_barang_id,
                        'jumlah_pesanan' => $request->jumlah_pesanan,
                        'sisa_pesanan' => $request->jumlah_pesanan,
                        'keterangan' => $request->keterangan,
                        'estimasi_harga_total' => $estimasiHargaTotal,
                    ]
                )->save();

                return response()->json([
                    'message' => 'Pemesanan barang has been updated.',
                    'data' => $findPemesananBarang
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Pemesanan barang not found.',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, pemesanan barang cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
