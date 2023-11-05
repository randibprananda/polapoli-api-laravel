<?php

namespace App\Http\Controllers\Api\PengalamanKerja;

use App\Http\Controllers\Controller;
use App\Models\DetailPengalamanKerja;
use App\Models\Paslon;
use App\Models\PengalamanKerja;
use App\Models\TentangPaslon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PengalamanKerjaController extends Controller
{
    public function getAll()
    {
        $pengalaman = PengalamanKerja::with ('detail_pengalaman')
                    ->orderBy('id', "DESC")
                    ->paginate(10)->withQueryString();

        return response()->json([
                'message' => 'List of Experience',
                'data' => $pengalaman,
            ], Response::HTTP_OK);
    }

    public function addPengalaman(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
                $isBackgroundExist = Paslon::with('tentangPaslon')->where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();
                $findPaslonId = $isBackgroundExist->id;
                $tentangPaslonId = TentangPaslon::where('paslon_id', $findPaslonId)->orderBy('created_at', 'desc')->first();
                DB::beginTransaction();
                    $pengalaman = PengalamanKerja::create([
                        'tentang_paslon_id' => $tentangPaslonId->id,
                        "name" => $request->name,
                    ]);

                    foreach ($request->detail_pengalaman as $value) {
                        DetailPengalamanKerja::create([
                            "id_pengalaman_kerja" => $pengalaman['id'],
                            "description" => $value['description'],
                            "start" => $value['start'],
                            "end" => $value['end'],
                        ]);
                    }
                DB::commit();
                return response()->json([
                    'message' => 'Pengalaman kerja has been success'
                ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, Pengalam kerja cannot been create'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePengalaman(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
                PengalamanKerja::findOrFail($id)->update([
                    "name" => $request->name,
                ]);
                DetailPengalamanKerja::where('id_pengalaman_kerja', $id)->delete();

                foreach ($request->detail_pengalaman as $value) {
                    DetailPengalamanKerja::create([
                        "id_pengalaman_kerja" => $id,
                        "description" => $value['description'],
                        "start" => $value['start'],
                        "end" => $value['end'],
                    ]);
                }
                return response()->json([
                    'message' => 'Pengalaman kerja has been update'
                ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, Pengalam kerja cannot been update'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function delPengalaman($id)
    {
        try {
            DetailPengalamanKerja::where('id_pengalaman_kerja', $id)->delete();
            PengalamanKerja::destroy($id);

            return response()->json([
                    'message' => 'Pengalaman kerja has been deleted success'
                ], Response::HTTP_OK);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'Sorry, Pengalam kerja cannot been deleted'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
