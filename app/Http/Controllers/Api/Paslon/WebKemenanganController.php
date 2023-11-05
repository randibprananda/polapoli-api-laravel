<?php

namespace App\Http\Controllers\Api\Paslon;

use App\Http\Controllers\Controller;
use App\Models\MisiPaslon;
use App\Models\ParpolPaslon;
use App\Models\Paslon;
use App\Models\ProkerPaslon;
use App\Models\TentangPaslon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class WebKemenanganController extends Controller
{
    public function showWebKemenangan()
    {
        // manajemen_paslon
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $paslons = Paslon::with(
            'tentangPaslon',
        'tentangPaslon.prokerPaslons',
        'tentangPaslon.misiPaslons',
        'tentangPaslon.parpolPaslons',
        'tentangPaslon.pengalamanKerja',
        'tentangPaslon.pengalamanKerja.detail_pengalaman'
        )
        ->where([['tim_relawan_id', Auth::user()->current_team_id], ['is_usung', 1]])->first();
        if ($paslons != null) {
            return response()->json([
                'message' => 'Detail of web kemenangan',
                'data' => $paslons,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No paslons available',
            ], Response::HTTP_OK);
        }
    }

    public function updateBackground(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'background_web_kemenangan' => 'required|image|mimes:png,jpg,jpeg',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $isBackgroundExist = Paslon::with('tentangPaslon')->where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();
            if ($request->hasFile('background_web_kemenangan')) {
                $filename = 'background-web-kemenangan-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->background_web_kemenangan->extension();
                $request->file('background_web_kemenangan')->move('storage/background-web/', $filename);

                $findPaslonId = $isBackgroundExist->id;
                if ($isBackgroundExist->tentangPaslon  == null) {
                    $resultPaslon = TentangPaslon::create([
                        'paslon_id' => $findPaslonId,
                        'background' => env('APP_URL') . '/storage/background-web/' . $filename,
                    ]);
                    return response()->json([
                        'message' => 'Background has been updated',
                        'data' => $resultPaslon
                    ], Response::HTTP_OK);
                } else if ($isBackgroundExist->tentangPaslon != null) {
                    $checkId = TentangPaslon::find($isBackgroundExist->tentangPaslon->id);
                    if($checkId->background != null)
                    {
                        Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $checkId->background));
                    }
                    $checkId->forceFill([
                        'background' => env('APP_URL') . '/storage/background-web/' . $filename,
                    ])->save();
                    return response()->json([
                        'message' => 'Background has been updated',
                        'data' => $checkId
                    ], Response::HTTP_OK);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, background cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateFotoCalon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto_calon_web_kemenangan' => 'required|image|mimes:png,jpg,jpeg',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $isBackgroundExist = Paslon::with('tentangPaslon')->where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();
            if ($request->hasFile('foto_calon_web_kemenangan')) {
                $filename = 'foto_calon_web_kemenangan-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->foto_calon_web_kemenangan->extension();
                $request->file('foto_calon_web_kemenangan')->move('storage/paslon-image-web-kemenangan/', $filename);

                $findPaslonId = $isBackgroundExist->id;
                if ($isBackgroundExist->tentangPaslon  == null) {
                    $resultPaslon = TentangPaslon::create([
                        'paslon_id' => $findPaslonId,
                        'foto_calon_web_kemenangan' => env('APP_URL') . '/storage/paslon-image-web-kemenangan/' . $filename,
                    ]);
                    return response()->json([
                        'message' => 'Background has been updated',
                        'data' => $resultPaslon
                    ], Response::HTTP_OK);
                } else if ($isBackgroundExist->tentangPaslon != null) {
                    $checkId = TentangPaslon::find($isBackgroundExist->tentangPaslon->id);
                    if($checkId->foto_calon_web_kemenangan != null)
                    {
                        Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $checkId->foto_calon_web_kemenangan));
                    }
                    $checkId->forceFill([
                        'foto_calon_web_kemenangan' => env('APP_URL') . '/storage/paslon-image-web-kemenangan/' . $filename,
                    ])->save();
                    return response()->json([
                        'message' => 'Web paslon image has been updated',
                        'data' => $checkId
                    ], Response::HTTP_OK);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, web paslon image cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function updateWarnaTema(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tema_warna' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $isBackgroundExist = Paslon::with('tentangPaslon')->where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();


            $findPaslonId = $isBackgroundExist->id;

            if ($isBackgroundExist->tentangPaslon  == null) {
                $resultPaslon = TentangPaslon::create([
                    'paslon_id' => $findPaslonId,
                    'tema_warna' => $request->tema_warna,
                ]);
                return response()->json([
                    'message' => 'Warna tema has been updated',
                    'data' => $resultPaslon
                ], Response::HTTP_OK);
            } else if ($isBackgroundExist->tentangPaslon != null) {
                $checkId = TentangPaslon::find($isBackgroundExist->tentangPaslon->id);
                $checkId->forceFill([
                    'tema_warna' => $request->tema_warna,
                ])->save();
                return response()->json([
                    'message' => 'Warna tema has been updated',
                    'data' => $checkId
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Update warna tema failed',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, Warna tema cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateInfoCalon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slogan' => 'string|nullable',
            'motto' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

            $isBackgroundExist = Paslon::with('tentangPaslon')->where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();


            $findPaslonId = $isBackgroundExist->id;
            if ($findPaslonId != null) {

                if ($isBackgroundExist->tentangPaslon  == null) {
                    $checkId = TentangPaslon::create([
                        'paslon_id' => $findPaslonId,
                        'slogan' => $request->slogan,
                        'motto' => $request->motto
                    ]);
                } else if ($isBackgroundExist->tentangPaslon != null) {
                    $checkId = TentangPaslon::find($isBackgroundExist->tentangPaslon->id);
                    $checkId->forceFill([
                        'slogan' => $request->slogan,
                        'motto' => $request->motto
                    ])->save();
                }
            }
            return response()->json([
                'message' => 'Info calon has been updated',
                'data' => $checkId
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, info calon cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLinkHalaman()
    {
        try {
            $isBackgroundExist = Paslon::with('tentangPaslon')->where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();


            $slugLink = $isBackgroundExist->tentangPaslon->slug;
            return response()->json([
                'message' => 'Get data slug link web kemenangan',
                'data' => $slugLink,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, Link halaman cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function updateVisi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $isBackgroundExist = Paslon::with('tentangPaslon')->where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();


            $findPaslonId = $isBackgroundExist->id;

            if ($isBackgroundExist->tentangPaslon  == null) {
                $resultPaslon = TentangPaslon::create([
                    'paslon_id' => $findPaslonId,
                    'visi' => $request->visi,
                ]);
                return response()->json([
                    'message' => 'Visi has been updated',
                    'data' => $resultPaslon
                ], Response::HTTP_OK);
            } else if ($isBackgroundExist->tentangPaslon != null) {
                $checkId = TentangPaslon::find($isBackgroundExist->tentangPaslon->id);
                $checkId->forceFill([
                    'visi' => $request->visi,
                ])->save();
                return response()->json([
                    'message' => 'Visi has been updated',
                    'data' => $checkId
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Update visi failed',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, visi cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateMisi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'misi.*' => 'required|string',
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $isBackgroundExist = Paslon::with('tentangPaslon')->where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();


            $findPaslonId = $isBackgroundExist->id;

            if ($isBackgroundExist->tentangPaslon  == null) {
                DB::beginTransaction();
                TentangPaslon::create([
                    'paslon_id' =>  $findPaslonId
                ]);

                $tentangPaslonId = TentangPaslon::orderBy('created_at', 'desc')->first();
                if ($tentangPaslonId) {
                    if (count($request->misi) > 0) {
                        for ($i = 0; $i < count($request->misi); $i++) {
                            MisiPaslon::create([
                                'misi' => $request->input('misi')[$i],
                                'tentang_paslon_id' => $tentangPaslonId->id,
                            ]);
                        }
                    }
                }
                DB::commit();
            } else if ($isBackgroundExist->tentangPaslon != null) {
                MisiPaslon::whereIn('tentang_paslon_id', $isBackgroundExist->tentangPaslon)->delete();
                    if (count($request->misi) > 0) {
                        for ($i = 0; $i < count($request->misi); $i++) {
                            MisiPaslon::create([
                                'misi' => $request->input('misi')[$i],
                                'tentang_paslon_id' => $isBackgroundExist->tentangPaslon->id,
                            ]);
                        }
                    }
            }
            return response()->json([
                'message' => 'Misi has been updated',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, misi cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProker(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isi_proker.*' => 'required|string',
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $isBackgroundExist = Paslon::with('tentangPaslon')->where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();


            $findPaslonId = $isBackgroundExist->id;

            if ($isBackgroundExist->tentangPaslon  == null) {

                TentangPaslon::create([
                    'paslon_id' =>  $findPaslonId
                ]);

                $tentangPaslonId = TentangPaslon::orderBy('created_at', 'desc')->first();
                if ($tentangPaslonId) {
                    if (count($request->isi_proker) > 0) {
                        for ($i = 0; $i < count($request->isi_proker); $i++) {
                            ProkerPaslon::create([
                                'isi_proker' => $request->input('isi_proker')[$i],
                                'tentang_paslon_id' => $tentangPaslonId->id,
                            ]);
                        }
                    }
                }
            } else if ($isBackgroundExist->tentangPaslon != null) {
                ProkerPaslon::whereIn('tentang_paslon_id', $isBackgroundExist->tentangPaslon)->delete();
                    if (count($request->isi_proker) > 0) {
                        for ($i = 0; $i < count($request->isi_proker); $i++) {
                            ProkerPaslon::create([
                                'isi_proker' => $request->input('isi_proker')[$i],
                                'tentang_paslon_id' => $isBackgroundExist->tentangPaslon->id,
                            ]);
                        }
                    }
            }
            return response()->json([
                'message' => 'Proker has been updated',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, proker cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function tambahDaftarParpol(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto_parpol' => 'required|image|mimes:png,jpg,jpeg',
            'nama_parpol' => 'required|string',
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $isBackgroundExist = Paslon::with('tentangPaslon')->where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();


            $findPaslonId = $isBackgroundExist->id;

            if ($isBackgroundExist->tentangPaslon  == null) {
                DB::beginTransaction();
                TentangPaslon::create([
                    'paslon_id' =>  $findPaslonId
                ]);

                $tentangPaslonId = TentangPaslon::orderBy('created_at', 'desc')->first();
                if ($tentangPaslonId) {

                    if ($request->hasFile('foto_parpol')) {
                        $filename = 'foto_parpol-' . uniqid() . strtolower(Str::random(10)) . '.' . $request['foto_parpol']->extension();
                        $request->file('foto_parpol')->move('storage/foto-parpol/', $filename);
                        $data = ParpolPaslon::create([
                            'foto_parpol' => env('APP_URL') . '/storage/foto-parpol/' . $filename,
                            'nama_parpol' => $request['nama_parpol'],
                            'tentang_paslon_id' => $tentangPaslonId->id,
                        ]);
                    }
                }
                DB::commit();
            } else if ($isBackgroundExist->tentangPaslon != null) {
                $tentangPaslonId = $isBackgroundExist->tentangPaslon->id;
                if ($tentangPaslonId) {
                    if ($request->hasFile('foto_parpol')) {
                        $filename = 'foto_parpol-' . uniqid() . strtolower(Str::random(10)) . '.' . $request['foto_parpol']->extension();
                        $request->file('foto_parpol')->move('storage/foto-parpol/', $filename);
                        $data = ParpolPaslon::create([
                            'foto_parpol' => env('APP_URL') . '/storage/foto-parpol/' . $filename,
                            'nama_parpol' => $request['nama_parpol'],
                            'tentang_paslon_id' => $tentangPaslonId,
                        ]);
                    }
                }
            }
            return response()->json([
                'message' => 'Parpol has been added',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, parpol cannot be added.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateDaftarParpol(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'foto_parpol' => 'nullable|image|mimes:png,jpg,jpeg',
            'nama_parpol' => 'required|string',
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {


            $parpolId = ParpolPaslon::find($id);
            if ($parpolId) {
                if ($request->hasFile('foto_parpol')) {
                    if($parpolId->foto_parpol != null)
                    {
                        Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $parpolId->foto_parpol));
                    }
                    $filename = 'foto_parpol-' . uniqid() . strtolower(Str::random(10)) . '.' . $request['foto_parpol']->extension();
                    $request->file('foto_parpol')->move('storage/foto-parpol/', $filename);
                    $parpolId->forceFill([
                        'foto_parpol' => env('APP_URL') . '/storage/foto-parpol/' . $filename,
                        'nama_parpol' => $request['nama_parpol'],
                    ])->save();
                }else{
                    $parpolId->forceFill([
                        'nama_parpol' => $request['nama_parpol'],
                    ])->save();
                }
            }

            return response()->json([
                'message' => 'Parpol has been updated',
                'data' => $parpolId
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, parpol cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteDaftarParpol($id)
    {
        try {
            $parpolId = ParpolPaslon::find($id);
            if ($parpolId) {
                if($parpolId->foto_parpol != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $parpolId->foto_parpol));
                }
                $parpolId->delete();
            }
            return response()->json([
                'message' => 'Parpol has been deleted',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, parpol cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
