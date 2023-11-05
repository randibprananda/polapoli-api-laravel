<?php

namespace App\Http\Controllers\Api\Anggota;

use App\Http\Controllers\Controller;
use App\Models\CalonAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class CalonAnggotaController extends Controller
{
    public function getAll(Request $request)
    {
        $calonAnggota = CalonAnggota::with('partai','timRelawan')
                        ->where('nama_calon', 'LIKE', '%'.$request->search.'%')
                        ->where('id_partai', 'LIKE', '%'.$request->partai.'%')
                        ->where('tim_relawan_id', Auth::user()->current_team_id)
        				->orderBy('created_at', 'desc')
                		->paginate(10)->withQueryString();

        if ($calonAnggota != null) {
            return response()->json([
                'message' => 'List of calon anggota',
                'data' => $calonAnggota,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No calon anggota available',
            ], Response::HTTP_OK);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_calon' => 'required|string',
            'jenis_pencalonan' => 'required|string',
            'foto' => 'required|image|mimes:png,jpg,jpeg',
            'no_urut' => 'required',
            'is_usung' => 'required',
            'id_partai' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

            // $timRelawanId = Auth::user()->roles->pluck('tim_relawan_id')->toArray();

            $calon_anggota = CalonAnggota::where('tim_relawan_id', Auth::user()->current_team_id)->where('is_usung', 1)->first();

            if($calon_anggota && $request->is_usung == 1)
            {
                return response()->json([
                    'message' => 'Calon anggota yang sudah di usung sudah ada.',
                ], Response::HTTP_BAD_REQUEST);
            }
            else
            {
                $foto = null;

                if ($request->hasFile('foto')) {
                    $filenameFoto = 'foto-anggota' . uniqid() . strtolower(Str::random(10)) . '.' . $request->foto->extension();
                    $request->file('foto')->move('storage/foto-anggota/', $filenameFoto);

                    $foto = env('APP_URL') . '/storage/foto-anggota/' . $filenameFoto;
                }

                $anggota = CalonAnggota::create(
                    [
                        'nama_calon' => $request->nama_calon,
                        'foto' => $foto,
                        'jenis_pencalonan' => $request->jenis_pencalonan,
                        'no_urut' => $request->no_urut,
                        'is_usung' => $request->is_usung,
                        'id_partai' => $request->id_partai,
                        'tim_relawan_id' => Auth::user()->current_team_id,
                    ]
                );


                return response()->json([
                    'message' => 'Calon Anggota has ben create.',
                    'data' => $anggota
                ], Response::HTTP_OK);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, calon anggota cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_calon' => 'required|string',
            'jenis_pencalonan' => 'required|string',
            'no_urut' => 'required',
            'is_usung' => 'required',
            'id_partai' => 'required',
            'foto' => 'nullable|image|mimes:png,jpg,jpeg'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

            $calon_anggota = CalonAnggota::where('tim_relawan_id', Auth::user()->current_team_id)->where('is_usung', 1)->first();

            if($calon_anggota && $request->is_usung == 1)
            {
                return response()->json([
                    'message' => 'Calon anggota yang sudah di usung sudah ada.',
                ], Response::HTTP_BAD_REQUEST);
            }
            else
            {
                $anggota = CalonAnggota::find($id);

                $anggota->forceFill(
                    [
                        'nama_calon' => $request->nama_calon,
                        'jenis_pencalonan' => $request->jenis_pencalonan,
                        'no_urut' => $request->no_urut,
                        'is_usung' => $request->is_usung,
                        'id_partai' => $request->id_partai,
                        'tim_relawan_id' => Auth::user()->current_team_id,
                    ]
                )->save();

                if ($request->hasFile('foto')) {
                    if($anggota->foto != null)
                    {
                        Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $anggota->foto));
                    }
                    $filename = 'foto-anggota-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->foto->extension();
                    $request->file('foto')->move('storage/foto-anggota/', $filename);
                    $anggota->forceFill([
                        'foto' => env('APP_URL') . '/storage/foto-anggota/' . $filename,
                    ])->save();
                }

                return response()->json([
                    'message' => 'Calon Anggota has ben updated.',
                    'data' => $anggota
                ], Response::HTTP_OK);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, calon Anggota cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $calon = CalonAnggota::find($id);

            if($calon->is_usung == 1)
            {
                return response()->json([
                    'message' => 'Calon anggota yang sudah di usung sudah ada.',
                ], Response::HTTP_BAD_REQUEST);
            }
            else
            {
                if($calon->foto != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $calon->foto));
                }
                $calon->delete();
            }
            return response()->json([
                'message' => 'Calon Anggota has ben deleted.',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
