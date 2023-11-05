<?php

namespace App\Http\Controllers\Api\Wilayah;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Propinsi;
use Symfony\Component\HttpFoundation\Response;

class WilayahController extends Controller
{
    public function propinsi()
    {
        try {
            $propinsis = Propinsi::all();
            if ($propinsis != null) {
                return response()->json([
                    'message' => 'List Wilayah Propinsi - Kabupaten - Kecamatan - Kelurahan',
                    'data' => $propinsis
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Sorry, data empty.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, something went wrong.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function findPropinsi($id)
    {
        try {
            $propinsi = Propinsi::find($id);
            if ($propinsi != null) {
                return response()->json([
                    'message' => 'List Wilayah Propinsi',
                    'data' => $propinsi
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Sorry, data empty.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, something went wrong.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function findKabbyPropinsi($id)
    {
        try {
            $kabupatens = Kabupaten::where('propinsi_id', $id)->get();
            if ($kabupatens != null) {
                return response()->json([
                    'message' => 'Hasil pencarian data kabupaten by propinsi',
                    'data' => $kabupatens
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Sorry, data empty.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, something went wrong.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function findKabupaten($id)
    {
        try {
            $kabupaten = Kabupaten::find($id);
            if ($kabupaten != null) {
                return response()->json([
                    'message' => 'Hasil pencarian data kabupaten',
                    'data' => $kabupaten
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Sorry, data empty.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, something went wrong.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function findKecbyKab($id)
    {
        try {
            $kecamatan = Kecamatan::where('kabupaten_id', $id)->get();
            if ($kecamatan != null) {
                return response()->json([
                    'message' => 'Hasil pencarian data kecamatan by kabupaten',
                    'data' => $kecamatan
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Sorry, data empty.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, something went wrong.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function findKecamatan($id)
    {
        try {
            $kecamatan = Kecamatan::find($id);
            if ($kecamatan != null) {
                return response()->json([
                    'message' => 'Hasil pencarian data kecamatan',
                    'data' => $kecamatan
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Sorry, data empty.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, something went wrong.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function findKelbyKec($id)
    {
        try {
            $kelurahan = Kelurahan::where('kecamatan_id', $id)->get();
            if ($kelurahan != null) {
                return response()->json([
                    'message' => 'Hasil pencarian data kelurahan',
                    'data' => $kelurahan
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Sorry, data empty.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, something went wrong.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function findKelurahan($id)
    {
        try {
            $kelurahan = Kelurahan::find($id);
            if ($kelurahan != null) {
                return response()->json([
                    'message' => 'Hasil pencarian data kelurahan',
                    'data' => $kelurahan
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Sorry, data empty.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, something went wrong.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}