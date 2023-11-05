<?php

namespace App\Http\Controllers\Api\Issue;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\KindofIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class IssueController extends Controller
{
    public function getAll(Request $request)
    {
        // manajemen_isu
        // if (!Auth::user()->customHasPermissionTo(13)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        if ($request->jenis_isu_id != null) {
            $issues = Issue::with('kindOfIssue', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                ->where('tim_relawan_id', Auth::user()->current_team_id)
                ->where("jenis_isu_id", "LIKE", "%".$request->jenis_isu_id."%")
                ->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        } elseif ($request->search != null) {
            $search = $request->search;
            $issues = Issue::with('kindOfIssue', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                ->where('tim_relawan_id', Auth::user()->current_team_id)
                ->where('judul_isu', 'LIKE', '%'.$request->search.'%')
                ->orWhere(function ($q) use ($search){
                    $q->where('jenis_isu_id', 'LIKE', "%".$search."%")
                    ->orWhere('nama_pelapor', 'LIKE', "%".$search."%")
                    ->orWhere('tanggapan_isu', 'LIKE', "%".$search."%")
                    ->orWhere('keterangan_isu', 'LIKE', "%".$search."%");
                })
                ->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        } else {
            $issues = Issue::with('kindOfIssue', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                ->where('tim_relawan_id', Auth::user()->current_team_id)
                ->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        }
        if ($issues != null) {
            return response()->json([
                'message' => 'List of issue',
                'data' => $issues,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No issues available',
            ], Response::HTTP_OK);
        }
    }

    public function addIssue(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(13)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'jenis_isu_id' => 'required|numeric',
            'dampak_isu' => 'in:Positif,Negatif,Netral|required',
            'tanggal_isu' => 'required|string|max:255',
            'keterangan_isu' => 'required|string',
            'nama_pelapor' => 'required|string|max:255',
            'judul_isu' => 'nullable|string',
            'url_isu' => 'nullable|string',
            'foto_isu' => 'nullable|image|mimes:png,jpg,jpeg',
            'propinsi_id' => 'nullable|numeric',
            'kabupaten_id' => 'nullable|numeric',
            'kecamatan_id' => 'nullable|numeric',
            'kelurahan_id' => 'nullable|numeric',
            'dapil' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {


            if ($findTeamRelawanId = Auth::user()->current_team_id) {

                $fixFoto = null;
                if ($request->hasFile('foto_isu')) {
                    $filename = 'foto_isu-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->foto_isu->extension();
                    $request->file('foto_isu')->move('storage/foto-isu/', $filename);
                    $fixFoto = env('APP_URL') . '/storage/foto-isu/' . $filename;
                }
                $issue = Issue::create([
                    'jenis_isu_id' => $request->jenis_isu_id,
                    'dampak_isu' => $request->dampak_isu,
                    'tanggal_isu' => $request->tanggal_isu,
                    'keterangan_isu' => $request->keterangan_isu,
                    'nama_pelapor' => $request->nama_pelapor,
                    'judul_isu' => $request->judul_isu,
                    'foto_isu' =>  $fixFoto,
                    'url_isu' => $request->url_isu,
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'dapil' => $request->dapil,
                    'tim_relawan_id' => $findTeamRelawanId,
                ]);


                return response()->json([
                    'message' => 'Issue has been created',
                    'data' => $issue
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Sorry, issue cannot be created.',
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, issue cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detailIssue($id)
    {
        // if (!Auth::user()->customHasPermissionTo(13)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $issue = Issue::with('kindOfIssue', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')->where('tim_relawan_id', Auth::user()->current_team_id)->find($id);
        return response()->json([
            'message' => 'Issue detail',
            'data' => $issue
        ], Response::HTTP_OK);
    }

    public function updateIssue(Request $request, $id)
    {
        // if (!Auth::user()->customHasPermissionTo(13)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'jenis_isu_id' => 'required|numeric',
            'dampak_isu' => 'in:Positif,Negatif,Netral|required',
            'tanggal_isu' => 'required|string|max:255',
            'keterangan_isu' => 'required|string',
            'nama_pelapor' => 'required|string|max:255',
            'judul_isu' => 'nullable|string',
            'url_isu' => 'nullable|string',
            'foto_isu' => 'nullable|image|mimes:png,jpg,jpeg',
            'propinsi_id' => 'nullable|numeric',
            'kabupaten_id' => 'nullable|numeric',
            'kecamatan_id' => 'nullable|numeric',
            'kelurahan_id' => 'nullable|numeric',
            'dapil' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $issue = Issue::find($id);
            if ($request->hasFile('foto_isu')) {
                if($issue->foto_isu != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $issue->foto_isu));
                }
                $filename = 'foto_isu-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->foto_isu->extension();
                $request->file('foto_isu')->move('storage/foto-isu/', $filename);
                $fixFoto = env('APP_URL') . '/storage/foto-isu/' . $filename;
                $issue->forceFill([
                    'foto_isu' =>  $fixFoto,
                ]);
            }

            $findTeamRelawanId = Auth::user()->current_team_id;
            $issue->forceFill([
                'jenis_isu_id' => $request->jenis_isu_id,
                'dampak_isu' => $request->dampak_isu,
                'tanggal_isu' => $request->tanggal_isu,
                'keterangan_isu' => $request->keterangan_isu,
                'nama_pelapor' => $request->nama_pelapor,
                'judul_isu' => $request->judul_isu,
                'url_isu' => $request->url_isu,
                'propinsi_id' => $request->propinsi_id,
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
                'dapil' => $request->dapil,
                'tim_relawan_id' => $findTeamRelawanId,
            ])->save();

            return response()->json([
                'message' => 'Issue has ben updated.',
                'data' => $issue
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, issue cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteIssue($id)
    {
        // if (!Auth::user()->customHasPermissionTo(13)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        try {
            $issue = Issue::find($id);
            if($issue->foto_isu != null)
            {
                Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $issue->foto_isu));
            }
            $issue->delete();
            return response()->json([
                'message' => 'Issue has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Sorry, issue cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function jenisIsu()
    {
        // if (!Auth::user()->customHasPermissionTo(13)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $kindofissue = KindofIssue::all();
        return response()->json([
            'message' => 'Jenis Isu',
            'data' => $kindofissue
        ], Response::HTTP_OK);
    }
}
