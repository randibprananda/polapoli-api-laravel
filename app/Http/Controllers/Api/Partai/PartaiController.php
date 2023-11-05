<?php

namespace App\Http\Controllers\Api\Partai;

use App\Http\Controllers\Controller;
use App\Models\Partai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class PartaiController extends Controller
{
    public function getAll(Request $request)
    {
        if ($request->status != "") {
            $partai = Partai::with('timRelawan')->where('status', $request->status)
            ->where('tim_relawan_id', Auth::user()->current_team_id)->orderBy('created_at','DESC')->paginate(10)->withQueryString();
        } else {
            $partai = Partai::with('timRelawan')->where('tim_relawan_id', Auth::user()->current_team_id)->orderBy('created_at','DESC')->paginate(10)->withQueryString();
        }

        return response()->json([
                'message' => 'List of partai',
                'data' => $partai,
            ], Response::HTTP_OK);
    }

    public function addPartai(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_partai' => 'required|string',
            'logo' => 'required|image|mimes:png,jpg,jpeg',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

            $logo = null;

            if ($request->hasFile('logo')) {
                $filenameFoto = 'logo-partai' . uniqid() . strtolower(Str::random(10)) . '.' . $request->logo->extension();
                $request->file('logo')->move('storage/logo-partai/', $filenameFoto);

                $logo = env('APP_URL') . '/storage/logo-partai/' . $filenameFoto;
            }

            $partai = Partai::create(
                [
                    'nama_partai' => $request->nama_partai,
                    'logo' => $logo,
                    'status' => $request->status,
                    'tim_relawan_id' => Auth::user()->current_team_id,
                ]
            );

            return response()->json([
                'message' => 'Partai has been created',
                'data' => $partai
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, partai cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detailPartai($id)
    {

        $partai = Partai::find($id);
        return response()->json([
            'message' => 'Partai detail',
            'data' => $partai
        ], Response::HTTP_OK);
    }

    public function updatePartai(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'nama_partai' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $partai = Partai::find($id);
            $partai->forceFill(
                [
                    'nama_partai' => $request->nama_partai,
                    'status' => $request->status,
                    'tim_relawan_id' => Auth::user()->current_team_id,
                ]
            )->save();
            if ($request->hasFile('logo')) {
                if($partai->logo != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $partai->logo));
                }
                $filename = 'logo-partai-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->logo->extension();
                $request->file('logo')->move('storage/logo-partai/', $filename);
                $partai->forceFill([
                    'logo' => env('APP_URL') . '/storage/logo-partai/' . $filename,
                ])->save();
            }

            return response()->json([
                'message' => 'Partai has ben updated.',
                'data' => $partai
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, partai cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deletePartai($id)
    {
        try {
            $partai = Partai::find($id);
            if($partai->logo != null)
            {
                Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $partai->logo));
            }
            $partai->delete();
            return response()->json([
                'message' => 'Partai has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, partai cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
