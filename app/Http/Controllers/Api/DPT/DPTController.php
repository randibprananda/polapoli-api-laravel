<?php

namespace App\Http\Controllers\Api\DPT;

use App\Http\Controllers\Controller;
use App\Models\DPT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class DPTController extends Controller
{
    public function getAllByKel(Request $request, $id_kelurahan)
    {
        // if (!Auth::user()->customHasPermissionTo(5)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        if ($request->search != null) {
            $dpts = DPT::with('timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                ->where([['tim_relawan_id', Auth::user()->current_team_id], ['kelurahan_id', $id_kelurahan]])
                ->orderBy('created_at', 'desc')
                ->where("nama", "LIKE", "%{$request->search}%")
                ->paginate(10)->withQueryString();
        } else {
            $dpts = DPT::with('timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                ->where([['tim_relawan_id', Auth::user()->current_team_id], ['kelurahan_id', $id_kelurahan]])
                ->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
        }

        if ($dpts != null) {
            return response()->json([
                'message' => 'List of dpt',
                'data' => $dpts,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No dpts available',
            ], Response::HTTP_OK);
        }
    }

    public function getAllByDapil(Request $request, $dapil)
    {
        if ($request->search != null) {
            $dpts = DPT::with('timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                ->where([['tim_relawan_id', Auth::user()->current_team_id], ['dapil', $dapil]])
                ->orderBy('created_at', 'desc')
                ->where("nama", "LIKE", "%{$request->search}%")
                ->paginate(10)->withQueryString();
        } else {
            $dpts = DPT::with('timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                ->where([['tim_relawan_id', Auth::user()->current_team_id], ['dapil', $dapil]])
                ->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
        }

        if ($dpts != null) {
            return response()->json([
                'message' => 'List of dpt',
                'data' => $dpts,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No dpts available',
            ], Response::HTTP_OK);
        }
    }

    public function addDpt(Request $request)
    {
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
            'dapil' => 'required|numeric',
            'nik' => 'required|string|min:16|max:16',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|string|max:255',
            'jenis_kelamin' => 'in:L,P|required',
            'alamat' => 'required|string|max:255',
            'tps' => 'required|numeric',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
            'is_pendukung' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }


        $checkedUniqueNIK = DPT::where([['nik', $request->nik], ['tim_relawan_id', Auth::user()->current_team_id]])->first();
        if ($checkedUniqueNIK != null) {
            return response()->json([
                'message' => 'NIK already exists',
            ], Response::HTTP_BAD_REQUEST);
        }

        $isPendukung = 0;
        if ($request->is_pendukung == 1 && $request->is_pendukung != null) {
            $isPendukung = 1;
        }
        try {
            if ($isPendukung == 1) {
                $validator = Validator::make($request->all(), [
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

                DB::beginTransaction();
                $referalRelawan = Auth::user()->id;
                $dpt = DPT::create([
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
                    'tim_relawan_id' => Auth::user()->current_team_id,
                    'agama' => $request->agama,
                    'suku' => $request->suku,
                    'is_pendukung' => $isPendukung,
                    'keterangan' => $request->keterangan,
                    'no_hp' => $request->no_hp,
                    'no_hp_lainnya' => $request->no_hp_lainnya,
                    'email' => $request->email,
                    'foto' => $filenameFotoFix,
                    'referal_relawan' => $referalRelawan,
                    'foto_ktp' => $filenameFotoKtpFix,
                ]);
                DB::commit();
            } else if ($isPendukung == 0) {
                DB::beginTransaction();
                $dpt = DPT::create([
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
                    'tim_relawan_id' => Auth::user()->current_team_id,
                ]);
                DB::commit();
            }


            return response()->json([
                'message' => 'DPT has been created',
                'data' => $dpt
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, dpt cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detailDpt($id)
    {
        // if (!Auth::user()->customHasPermissionTo(5)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $dpt = DPT::with('timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')->where([['tim_relawan_id', Auth::user()->current_team_id]])->find($id);
        return response()->json([
            'message' => 'DPT detail',
            'data' => $dpt
        ], Response::HTTP_OK);
    }

    public function updateDpt(Request $request, $id)
    {
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
            'dapil' => 'required|numeric',
            'nik' => ['required', 'string', 'min:16', 'max:16'],
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|string|max:255',
            'jenis_kelamin' => 'in:L,P|required',
            'alamat' => 'required|string|max:255',
            'tps' => 'required|numeric',
            'rt' => 'required|numeric',
            'rw' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $dpt = DPT::find($id);
            $dpt->forceFill(
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
                ]
            )->save();

            return response()->json([
                'message' => 'DPT has ben updated.',
                'data' => $dpt
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, dpt cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteDpt($id)
    {
        // if (!Auth::user()->customHasPermissionTo(5)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        try {
            DPT::find($id)->delete();
            return response()->json([
                'message' => 'DPT has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, dpt cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showDPTByNIK(Request $request)
    {
        // code...
        $get = $request->query->all();
        $dpt = DPT::with('timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
               ->where([['tim_relawan_id', Auth::user()->current_team_id]])
               ->where("nik", "LIKE", "%{$get['nik']}%")
               ->where("is_pendukung", "LIKE", "%{$get['isPemilih']}%")
               ->get();
        return response()->json([
            'message' => 'DPT detail by NIK',
            'data' => $dpt
        ], Response::HTTP_OK);

    }

    public function showDPTByNIKFilter(Request $request)
    {
        $dpt = DPT::with('timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
               ->where([['tim_relawan_id', Auth::user()->current_team_id]])
               ->where("is_pendukung", 0)
               ->get();
        return response()->json([
            'message' => 'DPT detail by NIK',
            'data' => $dpt
        ], Response::HTTP_OK);

    }

    public function getAllDapilByDPT()
    {
        $collction = DPT::select('dapil')->distinct()->get()->toArray();
        return response()->json($collction);
    }
}
