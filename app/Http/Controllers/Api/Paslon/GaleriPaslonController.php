<?php

namespace App\Http\Controllers\Api\Paslon;

use App\Http\Controllers\Controller;
use App\Models\GaleriPaslon;
use App\Models\Paslon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class GaleriPaslonController extends Controller
{
    public function listGaleri()
    {
        // manajemen_paslon
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $galeripaslons = GaleriPaslon::with('paslon.timRelawan')->whereHas('paslon', function ($q) {
            $q->where('tim_relawan_id', Auth::user()->current_team_id);
        })->get();
        if ($galeripaslons != null) {
            return response()->json([
                'message' => 'List of galeri paslon',
                'data' => $galeripaslons,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No galeri paslon available',
            ], Response::HTTP_OK);
        }
    }

    public function addGaleri(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        $validator = Validator::make($request->all(), [
            'keterangan' => 'nullable|string',
            'foto_galeri_paslon' => 'required|image|mimes:png,jpg,jpeg',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $keterangan = $request->keterangan;
            $paslon = Paslon::with('tentangPaslon')->where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();
            $paslonId = $paslon->id;
            if ($request->hasFile('foto_galeri_paslon')) {
                $filename = 'foto-galeri-paslon_' . uniqid() . strtolower(Str::random(5)) . '.' . $request->foto_galeri_paslon->extension();
                $request->file('foto_galeri_paslon')->move('storage/galeri-paslon/', $filename);
                $galeripaslon = GaleriPaslon::create(
                    [
                        'paslon_id' => $paslonId,
                        'keterangan' => $keterangan,
                        'foto_galeri_paslon' => env('APP_URL') . '/storage/galeri-paslon/' . $filename,
                    ]
                );
            }

            return response()->json([
                'message' => 'Foto galeri paslon has been created',
                'data' => $galeripaslon
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, foto galeri paslon cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showGaleri($id)
    {
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $timrelawan = GaleriPaslon::with('paslon.timRelawan')->whereHas('paslon', function ($q) {
            $q->where('tim_relawan_id', Auth::user()->current_team_id);
        })->find($id);
        return response()->json([
            'message' => 'Galeri paslon detail',
            'data' => $timrelawan
        ], Response::HTTP_OK);
    }

    public function updateGaleri(Request $request, $id)
    {
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        $validator = Validator::make($request->all(), [
            'keterangan' => 'nullable|string',
            'foto_galeri_paslon' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $galeriPaslon = GaleriPaslon::find($id);
            $keterangan = $request->keterangan;

            if ($request->hasFile('foto_galeri_paslon')) {
                if($galeriPaslon->foto_galeri_paslon != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $galeriPaslon->foto_galeri_paslon));
                }
                $filename = 'foto-galeri-paslon-' . uniqid() . strtolower(Str::random(5)) . '.' . $request->foto_galeri_paslon->extension();
                $request->file('foto_galeri_paslon')->move('storage/galeri-paslon/', $filename);
                $galeriPaslon->forceFill(
                    [
                        'foto_galeri_paslon' => env('APP_URL') . '/storage/galeri-paslon/' . $filename,
                    ]
                )->save();
            }

            $galeriPaslon->forceFill(
                [
                    'keterangan' => $keterangan,
                ]
            )->save();

            return response()->json([
                'message' => 'Foto galeri paslon has ben updated.',
                'data' => $galeriPaslon
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, foto galeri paslon cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteGaleri($id)
    {
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        try {
            $galeriPaslon =  GaleriPaslon::find($id);
            if($galeriPaslon->foto_galeri_paslon != null)
            {
                Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $galeriPaslon->foto_galeri_paslon));
            }
            $galeriPaslon->delete();
            return response()->json([
                'message' => 'Foto galeri paslon has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, foto galeri paslon cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
