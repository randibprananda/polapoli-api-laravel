<?php

namespace App\Http\Controllers\Api\Pendukung;

use App\Http\Controllers\Controller;
use App\Models\DPT;
use App\Models\Pendukung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class PendukungController extends Controller
{
    public function getAllByKel(Request $request, $id_kelurahan)
    {
        // manajemen_dpt
        // if (!Auth::user()->customHasPermissionTo(5)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        if ($request->search != null) {
            $pendukungs = DPT::with('timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'user')
                ->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['kelurahan_id', $id_kelurahan], ['is_pendukung', 1]
                ])->orderBy('created_at', 'desc')
                ->where("nama", "LIKE", "%{$request->search}%")
                ->paginate(10)->withQueryString();
        } else {
            $pendukungs = DPT::with('timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'user')
                ->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['kelurahan_id', $id_kelurahan], ['is_pendukung', 1]
                ])->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
        }
        if ($pendukungs != null) {
            return response()->json([
                'message' => 'List of pendukung',
                'data' => $pendukungs,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No pendukungs available',
            ], Response::HTTP_OK);
        }
    }
    public function getAllByDapil(Request $request, $dapil)
    {
        if ($request->search != null) {
            $pendukungs = DPT::with('timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'user')
                ->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['dapil', $dapil], ['is_pendukung', 1]
                ])->orderBy('created_at', 'desc')
                ->where("nama", "LIKE", "%{$request->search}%")
                ->paginate(10)->withQueryString();
        } else {
            $pendukungs = DPT::with('timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'user')
                ->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['dapil', $dapil], ['is_pendukung', 1]
                ])->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
        }
        if ($pendukungs != null) {
            return response()->json([
                'message' => 'List of pendukung',
                'data' => $pendukungs,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No pendukungs available',
            ], Response::HTTP_OK);
        }
    }
    public function addPendukung(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'propinsi_id' => 'required|numeric',
            'kabupaten_id' => 'required|numeric',
            'kecamatan_id' => 'required|numeric',
            'kelurahan_id' => 'required|numeric',
            'nik' => 'required|string|min:16|max:16',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|string|max:255',
            'jenis_kelamin' => 'in:L,P|required',
            'alamat' => 'required|string|max:255',
            'tps' => 'required|numeric',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'agama' => 'nullable|string',
            'suku' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'no_hp' => 'required|string',
            'no_hp_lainnya' => 'nullable|string',
            'email' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
            'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // $checkedUniqueNIK = DPT::where([['nik', $request->nik], ['tim_relawan_id', Auth::user()->current_team_id]])->first();
        // if ($checkedUniqueNIK != null) {
        //     return response()->json([
        //         'message' => 'NIK already exists',
        //     ], Response::HTTP_BAD_REQUEST);
        // }
        try {
            $filenameFotoFix = null;

            if ($request->hasFile('foto')) {
                $filenameFoto = 'foto_dpt_pendukung-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->foto->extension();
                $request->file('foto')->move('storage/foto-dpt-pendukung/', $filenameFoto);

                $filenameFotoFix = env('APP_URL') . '/storage/foto-dpt-pendukung/' . $filenameFoto;
            }

            $filenameFotoKtpFix = null;
            if ($request->hasFile('foto_ktp')) {
                $filenameFotoKtp = 'foto_ktp_dpt_pendukung-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->foto_ktp->extension();
                $request->file('foto_ktp')->move('storage/foto-ktp-dpt-pendukung/', $filenameFotoKtp);

                $filenameFotoKtpFix = env('APP_URL') . '/storage/foto-ktp-dpt-pendukung/' . $filenameFotoKtp;
            }
            $referalRelawan = Auth::user()->id;
            DB::beginTransaction();
            $pendukung = DPT::where("nik", $request->nik)->update(
                [
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'dapil' => $request->dapil,
                    'nik' => $request->nik,
                    'nama' => $request->nama,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'alamat' => $request->alamat,
                    'tps' => $request->tps,
                    'rt' => $request->rt,
                    'rw' => $request->rw,
                    'agama' => $request->agama,
                    'suku' => $request->suku,
                    'keterangan' => $request->keterangan,
                    'no_hp' => $request->no_hp,
                    'no_hp_lainnya' => $request->no_hp_lainnya,
                    'email' => $request->email,
                    'foto' => $filenameFotoFix,
                    'referal_relawan' => $referalRelawan,
                    'is_pendukung' => 1,
                    'foto_ktp' => $filenameFotoKtpFix,
                    'tim_relawan_id' => Auth::user()->current_team_id,
                ]
            );
            DB::commit();


            return response()->json([
                'message' => 'Data pendukung has been created',
                'data' => $pendukung
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, data pendukung cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detailPendukung($id)
    {
        // if (!Auth::user()->customHasPermissionTo(5)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $pendukung = DPT::with('timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')->where([['tim_relawan_id', Auth::user()->current_team_id], ['is_pendukung', 1]])->find($id);
        return response()->json([
            'message' => 'Pendukung detail',
            'data' => $pendukung
        ], Response::HTTP_OK);
    }

    public function updatePendukung(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'propinsi_id' => 'required|numeric',
            'kabupaten_id' => 'required|numeric',
            'kecamatan_id' => 'required|numeric',
            'kelurahan_id' => 'required|numeric',
            'nik' => ['required', 'string', 'min:16', 'max:16', Rule::unique('pendukungs')->ignore($id)],
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|string|max:255',
            'jenis_kelamin' => 'in:L,P|required',
            'alamat' => 'required|string|max:255',
            'tps' => 'required|numeric',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'agama' => 'nullable|string',
            'suku' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'no_hp' => 'required|string',
            'no_hp_lainnya' => 'nullable|string',
            'email' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
            'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            $filenameFotoFix = null;
            $pendukung = DPT::with('timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')->where([['tim_relawan_id', Auth::user()->current_team_id], ['is_pendukung', 1]])->find($id);
            if ($request->hasFile('foto')) {
                if($pendukung->foto != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $pendukung->foto));
                }
                $filenameFoto = 'foto_dpt_pendukung-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->foto->extension();
                $request->file('foto')->move('storage/foto-dpt-pendukung/', $filenameFoto);

                $filenameFotoFix = env('APP_URL') . '/storage/foto-dpt-pendukung/' . $filenameFoto;
                $pendukung->forceFill([
                    'foto' => $filenameFotoFix,
                ])->save();
            }

            $filenameFotoKtpFix = null;
            if ($request->hasFile('foto_ktp')) {
                if($pendukung->foto_ktp != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $pendukung->foto_ktp));
                }
                $filenameFotoKtp = 'foto_ktp_dpt_pendukung-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->foto_ktp->extension();
                $request->file('foto_ktp')->move('storage/foto-ktp-dpt-pendukung/', $filenameFotoKtp);

                $filenameFotoKtpFix = env('APP_URL') . '/storage/foto-ktp-dpt-pendukung/' . $filenameFotoKtp;
                $pendukung->forceFill([
                    'foto_ktp' => $filenameFotoKtpFix,
                ])->save();
            }
            $referalRelawan = Auth::user()->id;
            DB::beginTransaction();
            $pendukung->forceFill(
                [
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'dapil' => $request->dapil,
                    'nik' => $request->nik,
                    'nama' => $request->nama,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'alamat' => $request->alamat,
                    'tps' => $request->tps,
                    'rt' => $request->rt,
                    'rw' => $request->rw,
                    'agama' => $request->agama,
                    'suku' => $request->suku,
                    'keterangan' => $request->keterangan,
                    'no_hp' => $request->no_hp,
                    'no_hp_lainnya' => $request->no_hp_lainnya,
                    'email' => $request->email,
                    'referal_relawan' => $referalRelawan,
                    'tim_relawan_id' => Auth::user()->current_team_id,
                ]
            )->save();
            DB::commit();
            return response()->json([
                'message' => 'Data pendukung has been updated',
                'data' => $pendukung
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, data pendukung cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deletePendukung($id)
    {
        // if (!Auth::user()->customHasPermissionTo(5)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        try {
            $pendukung = DPT::find($id);
            if($pendukung->foto != null)
            {
                Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $pendukung->foto));
            }
            if($pendukung->foto_ktp != null)
            {
                Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $pendukung->foto_ktp));
            }
            $pendukung->delete();
            return response()->json([
                'message' => 'Pendukung has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Sorry, pendukung cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllDapilByDPT()
    {
        $collction = DPT::select('dapil')->distinct()->get()->toArray();
        return response()->json($collction);
    }
}
