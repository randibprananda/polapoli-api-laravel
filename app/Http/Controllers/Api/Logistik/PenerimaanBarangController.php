<?php

namespace App\Http\Controllers\Api\Logistik;

use App\Http\Controllers\Controller;
use App\Models\HistoryLogistikStok;
use App\Models\PemesananBarang;
use App\Models\PenerimaanBarang;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PenerimaanBarangController extends Controller
{
    public function index()
    {
        // manajemen_logistik
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $penerimaanBarang = PenerimaanBarang::
        whereHas("stokBarang", function ($p) {
            $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
        })->
        with('stokBarang','propinsi', 'kabupaten', 'kecamatan', 'kelurahan')->get();
        if ($penerimaanBarang != null) {
            return response()->json([
                'message' => 'List of Penerimaan Barang',
                'data' => $penerimaanBarang,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No penerimaan barang available',
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
            'stok_barang_id' => 'nullable|numeric',
            'pemesanan_barang_id' => 'nullable|numeric',
            'jumlah_diterima' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // try {
            DB::beginTransaction();
            if ($request->pemesanan_barang_id != null) {
                $penerimaanBarang = PenerimaanBarang::create(
                    [
                        'propinsi_id' => $request->propinsi_id,
                        'kabupaten_id' => $request->kabupaten_id,
                        'kecamatan_id' => $request->kecamatan_id,
                        'kelurahan_id' => $request->kelurahan_id,
                        'rt' => $request->rt,
                        'rw' => $request->rw,
                        'dapil' => $request->dapil,
                        'pemesanan_barang_id' => $request->pemesanan_barang_id,
                        'jumlah_diterima' => $request->jumlah_diterima,
                        'keterangan' => $request->keterangan,
                    ]
                );

                // Update Penerimaan
                $getStockFromLastPenerimaan = PenerimaanBarang::with('pemesananBarang.stokBarang')->orderBy('created_at', 'desc')->first();
                $fixGetStokPenerimaan = $getStockFromLastPenerimaan->pemesananBarang->stokBarang->id;
                $getLastDataPenerimaan = PenerimaanBarang::orderBy('created_at', 'desc')->first();
                $getLastDataPenerimaan->forceFill([
                    'stok_barang_id' => $fixGetStokPenerimaan,
                ])->save();

                // Update Pemesanan
                $findPemesananBarang = PemesananBarang::find($request->pemesanan_barang_id);
                $findPemesananBarangIsComplete = PemesananBarang::find($request->pemesanan_barang_id);
                $sisaPesanan = $findPemesananBarang->sisa_pesanan - $request->jumlah_diterima;

                // IsComplete Checker
                if ($sisaPesanan <= 0) {
                    $sisaPesanan = 0;
                    $findPemesananBarangIsComplete->forceFill(
                        [
                            'is_complete' => 1,
                        ]
                    )->save();
                }
                $findPemesananBarang->forceFill(
                    [
                        'sisa_pesanan' => $sisaPesanan,
                        'jumlah_diterima' => $request->jumlah_diterima,
                    ]
                )->save();

                // Update Stok
                $findStok = StokBarang::find($findPemesananBarang->stok_barang_id);
                $stokAwal = $findStok->stok_akhir;
                $stokAkhir = ($stokAwal + $request->jumlah_diterima);
                $findStok->forceFill(
                    [
                        'stok_awal' => $stokAwal,
                        'stok_akhir' => $stokAkhir,
                    ]
                )->save();

                // Input to History
                $findStokforHistory = StokBarang::find($findPemesananBarang->stok_barang_id);
                HistoryLogistikStok::create([
                    'stok_barang_id' => $findStokforHistory->id,
                    'keterangan' => $request->keterangan,
                    'stok_awal' => $findStokforHistory->stok_awal,
                    'stok_akhir' => $findStokforHistory->stok_akhir,
                    'total_masuk' => $request->jumlah_diterima
                ]);
            } else if ($request->stok_barang_id != null) {
                $penerimaanBarang = PenerimaanBarang::create(
                    [
                        'propinsi_id' => $request->propinsi_id,
                        'kabupaten_id' => $request->kabupaten_id,
                        'kecamatan_id' => $request->kecamatan_id,
                        'kelurahan_id' => $request->kelurahan_id,
                        'rt' => $request->rt,
                        'rw' => $request->rw,
                        'dapil' => $request->dapil,
                        'stok_barang_id' => $request->stok_barang_id,
                        'jumlah_diterima' => $request->jumlah_diterima,
                        'keterangan' => $request->keterangan,
                    ]
                );

                // Update Stok
                $findStokBarang = StokBarang::find($request->stok_barang_id);
                $stokAwal = $findStokBarang->stok_akhir;
                $stokAkhir = ($stokAwal + $request->jumlah_diterima);
                $findStokBarang->forceFill(
                    [
                        'stok_awal' => $stokAwal,
                        'stok_akhir' => $stokAkhir,
                    ]
                )->save();

                // Input to History
                $findStokforHistory = StokBarang::find($request->stok_barang_id);
                HistoryLogistikStok::create([
                    'stok_barang_id' => $findStokforHistory->id,
                    'keterangan' => $request->keterangan,
                    'stok_awal' => $findStokforHistory->stok_awal,
                    'stok_akhir' => $findStokforHistory->stok_akhir,
                    'total_masuk' => $request->jumlah_diterima
                ]);
            }
            DB::commit();
            return response()->json([
                'message' => 'Penerimaan barang has been created',
                'data' => $penerimaanBarang
            ], Response::HTTP_OK);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'message' => 'Sorry, penerimaan barang cannot be created',
        //     ], Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
    }

    public function show($id)
    {
        try {
            // if (!Auth::user()->customHasPermissionTo(12)) {
            //     return response()->json([
            //         'message' => 'FORBIDDEN',
            //     ], Response::HTTP_FORBIDDEN);
            // }
            if ($penerimaanBarang = PenerimaanBarang::
                whereHas("stokBarang", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->
                with('propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'stokBarang')->find($id)) {
                return response()->json([
                    'message' => 'Detail penerimaan barang.',
                    'data' => $penerimaanBarang
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, data penerimaan barang not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
