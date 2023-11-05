<?php

namespace App\Http\Controllers\Api\Paslon;

use App\Http\Controllers\Controller;
use App\Models\Paslon;
use App\Models\TentangPaslon;
use App\Models\TPS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class PaslonController extends Controller
{
    public function listPaslon(Request $request)
    {
        if ($request->search != null) {
            $paslons = Paslon::with('timRelawan')->where('tim_relawan_id', Auth::user()->current_team_id)
                ->orderBy('nomor_urut', 'asc')->where("nama_paslon", "LIKE", "%$request->search%")
                ->paginate(10)->withQueryString();
        } else {
            $paslons = Paslon::with('timRelawan')->where('tim_relawan_id', Auth::user()->current_team_id)
                ->orderBy('nomor_urut', 'asc')
                ->paginate(10)->withQueryString();
        }

        if ($paslons != null) {
            return response()->json([
                'message' => 'List of paslon',
                'data' => $paslons,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No paslons available',
            ], Response::HTTP_OK);
        }
    }

    public function addPaslon(Request $request)
    {
        // manajemen_paslon
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'jenis_pencalonan' => 'required|string',
            'nomor_urut' => 'required|numeric',
            'nama_paslon' => 'required|string|max:255',
            'nama_wakil_paslon' => 'nullable|string|max:255',
            'is_usung' => 'required|boolean',
            'paslon_profile_photo' => 'required|image|mimes:png,jpg,jpeg',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            DB::beginTransaction();
            $tim_relawan_id = Auth::user()->current_team_id;
            $paslon = new Paslon();

            $findJenisPencalonan = Paslon::where('tim_relawan_id', Auth::user()->current_team_id)->orderBy('created_at', 'desc');

            for ($i = 0; $i < $findJenisPencalonan->count(); $i++) {
                if ($findJenisPencalonan->get()[$i]->nomor_urut == $request->nomor_urut) {
                    return response()->json([
                        'message' => 'Nomor urut paslon sudah ada',
                    ], Response::HTTP_FORBIDDEN);
                }
            }

            // $jenisCalon = $request->jenis_pencalonan;
            // if ($findJenisPencalonan->first() != null && $findJenisPencalonan->first()->jenis_pencalonan != $jenisCalon) {
            //     return response()->json([
            //         'message' => 'Sorry, jenis pencalonan paslon harus sama dengan data paslon lainnya.',
            //     ], Response::HTTP_BAD_REQUEST);
            // }
            $findIsUsung = Paslon::where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();

            if ($findIsUsung && $request->is_usung == 1) {
                return response()->json([
                    'error' => 'Data usung already exists',
                    'message' => 'Please create new team / update your data if want to make Data Paslon Usung again'
                ], Response::HTTP_BAD_REQUEST);
            }

            $paslon->tim_relawan_id = $tim_relawan_id;
            $paslon->jenis_pencalonan = $request->jenis_pencalonan;
            $paslon->nomor_urut = $request->nomor_urut;
            $paslon->nama_paslon = $request->nama_paslon;
            $paslon->nama_wakil_paslon = $request->nama_wakil_paslon;
            $paslon->is_usung = $request->is_usung;
            if ($request->hasFile('paslon_profile_photo')) {
                $filename = 'foto_paslon-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->paslon_profile_photo->extension();
                $request->file('paslon_profile_photo')->move('storage/paslon-image/', $filename);
                $paslon->paslon_profile_photo = env('APP_URL') . '/storage/paslon-image/' . $filename;
            }

            $paslon->save();

            $slug = uniqid() . Str::random(7);
            TentangPaslon::create([
                'paslon_id' =>  $paslon->id,
                'slug' =>  $slug
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Paslon has been created',
                'data' => $paslon
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, paslon cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showPaslon($id)
    {
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $paslon = Paslon::with('timRelawan')->where('tim_relawan_id', Auth::user()->current_team_id)->find($id);
        return response()->json([
            'message' => 'Paslon detail',
            'data' => $paslon
        ], Response::HTTP_OK);
    }

    public function updatePaslon(Request $request, $id)
    {
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'jenis_pencalonan' => 'required|string',
            'nomor_urut' => 'required|numeric',
            'nama_paslon' => 'required|string|max:255',
            'nama_wakil_paslon' => 'nullable|string|max:255',
            'is_usung' => 'required|boolean',
            'paslon_profile_photo' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

            $findJenisPencalonan = Paslon::where('tim_relawan_id', Auth::user()->current_team_id)->orderBy('created_at', 'desc');

            for ($i = 0; $i < $findJenisPencalonan->count(); $i++) {
                if ($findJenisPencalonan->get()[$i]->nomor_urut == $request->nomor_urut) {
                    if (Paslon::find($id)->nomor_urut == $request->nomor_urut) {
                        # code...
                    } else {
                        return response()->json([
                            'message' => 'Nomor urut paslon sudah ada',
                        ], Response::HTTP_FORBIDDEN);
                    }
                }
            }

            $jenisCalon = $request->jenis_pencalonan;
            if ($findJenisPencalonan->first() != null && $findJenisPencalonan->first()->jenis_pencalonan != $jenisCalon) {
                if ($findJenisPencalonan->count() == 1) {
                } else {
                    return response()->json([
                        'message' => 'Sorry, jenis pencalonan paslon harus sama dengan data paslon lainnya.',
                    ], Response::HTTP_BAD_REQUEST);
                }
            }

            $findIsUsung = Paslon::where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();
            $paslon2 = Paslon::find($id);
            if ($findIsUsung && $request->is_usung == 1) {
                if ($paslon2->is_usung != 1) {
                    return response()->json([
                        'error' => 'Data usung already exists',
                        'message' => 'Please create new team / update your data if want to make Data Paslon Usung again'
                    ], Response::HTTP_BAD_REQUEST);
                }
            }

            DB::beginTransaction();
            $paslon = Paslon::find($id);
            $paslon->jenis_pencalonan = $request->jenis_pencalonan;
            $paslon->nomor_urut = $request->nomor_urut;
            $paslon->nama_paslon = $request->nama_paslon;
            $paslon->nama_wakil_paslon = $request->nama_wakil_paslon;
            $paslon->is_usung = $request->is_usung;

            if ($request->hasFile('paslon_profile_photo')) {
                if($paslon->paslon_profile_photo != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $paslon->paslon_profile_photo));
                }
                $filename = 'foto_paslon-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->paslon_profile_photo->extension();
                $request->file('paslon_profile_photo')->move('storage/paslon-image/', $filename);
                $paslon->paslon_profile_photo =  env('APP_URL') . '/storage/paslon-image/' . $filename;
            }


            $paslon->save();
            DB::commit();
            return response()->json([
                'message' => 'Paslon has ben updated.',
                'data' => $paslon
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Sorry, paslon cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deletePaslon($id)
    {
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        try {
            $paslon = Paslon::find($id);
            if($paslon->paslon_profile_photo != null)
            {
                Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $paslon->paslon_profile_photo));
            }
            $paslon->delete();
            return response()->json([
                'message' => 'Paslon has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Sorry, paslon cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
