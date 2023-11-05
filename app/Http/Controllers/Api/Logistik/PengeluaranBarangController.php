<?php

namespace App\Http\Controllers\Api\Logistik;

use App\Http\Controllers\Controller;
use App\Models\HistoryLogistikStok;
use App\Models\PengeluaranBarang;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PengeluaranBarangController extends Controller
{
    public function index()
    {
        // manajemen_logistik
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $pengeluaranBarang = PengeluaranBarang::
        whereHas("stokBarang", function ($p) {
            $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
        })->
        with('stokBarang','propinsi', 'kabupaten', 'kecamatan', 'kelurahan')->get();
        if ($pengeluaranBarang != null) {
            return response()->json([
                'message' => 'List of Pengeluaran Barang',
                'data' => $pengeluaranBarang,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No pengeluaran barang available',
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
            'propinsi_id' => 'nullable|numeric',
            'kabupaten_id' => 'nullable|numeric',
            'kecamatan_id' => 'nullable|numeric',
            'kecamatan_id' => 'nullable|numeric',
            'kelurahan_id' => 'nullable|numeric',
            'stok_barang_id' => 'required|numeric',
            'jumlah_pengeluaran' => 'required|numeric',
            'keterangan' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // try {
            DB::beginTransaction();
            $findStok = StokBarang::find($request->stok_barang_id);

            $batasanStok = $findStok->stok_akhir - $request->jumlah_pengeluaran;

            if ($batasanStok >= 0) {
                $pengeluaranBarang = PengeluaranBarang::create([
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'rt' => $request->rt,
                    'rw' => $request->rw,
                    'dapil' => $request->dapil,
                    'stok_barang_id' => $request->stok_barang_id,
                    'jumlah_pengeluaran' => $request->jumlah_pengeluaran,
                    'keterangan' => $request->keterangan,
                ]);

                // Update Stok
                $findStok = StokBarang::find($request->stok_barang_id);
                $stokAwal = $findStok->stok_akhir;
                $stokAkhir = ($stokAwal - $request->jumlah_pengeluaran);
                $findStok->forceFill(
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
                    'total_keluar' => $request->jumlah_pengeluaran
                ]);

                DB::commit();
                return response()->json([
                    'message' => 'Penerimaan barang has been created',
                    'data' => $pengeluaranBarang
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Sorry, jumlah pengeluaran melebihi stok',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
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
            if ($pengeluaranBarang = PengeluaranBarang::
                whereHas("stokBarang", function ($p) {
                    $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                })->
                with('stokBarang')->find($id)) {
                return response()->json([
                    'message' => 'Detail pengeluaran barang.',
                    'data' => $pengeluaranBarang
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, data pengeluaran barang not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
