<?php

namespace App\Http\Controllers\Api\Count;

use App\Http\Controllers\Controller;
use App\Models\CalonAnggota;
use App\Models\Paslon;
use App\Models\QuickCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class QuickCountController extends Controller
{
    public function listQuickCount(Request $request)
    {
        try {
            if ($request ->isLegislatif == 0) {
               if ($request->propinsi_id == null) {
                    $quickCount  = QuickCount::with('relawan', 'kandidatPilihan', 'kandidatCalonAnggota', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id == null) {
                    $quickCount  = QuickCount::with('relawan', 'kandidatPilihan', 'kandidatCalonAnggota', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['propinsi_id', $request->propinsi_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
                    $quickCount  = QuickCount::with('relawan', 'kandidatPilihan', 'kandidatCalonAnggota', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kabupaten_id', $request->kabupaten_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null && $request->tps == null) {
                    $quickCount  = QuickCount::with('relawan', 'kandidatPilihan', 'kandidatCalonAnggota', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kecamatan_id', $request->kecamatan_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->tps == null) {
                    $quickCount  = QuickCount::with('relawan', 'kandidatPilihan', 'kandidatCalonAnggota', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kelurahan_id', $request->kelurahan_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->tps != null) {
                    $quickCount  = QuickCount::with('relawan', 'kandidatPilihan', 'kandidatCalonAnggota', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kelurahan_id', $request->kelurahan_id],
                            ['tps', $request->tps],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } else {
                    return response()->json([
                        'message' => 'Sorry, quick count not found.',
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            if ($request->isLegislatif == 1) {
                if ($request->propinsi_id == null) {
                    $quickCount  = QuickCount::with('relawan', 'kandidatCalonAnggota', 'kandidatPartai', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id == null) {
                    $quickCount  = QuickCount::with('relawan', 'kandidatCalonAnggota', 'kandidatPartai', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['propinsi_id', $request->propinsi_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
                    $quickCount  = QuickCount::with('relawan', 'kandidatCalonAnggota', 'kandidatPartai', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kabupaten_id', $request->kabupaten_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null && $request->tps == null) {
                    $quickCount  = QuickCount::with('relawan', 'kandidatCalonAnggota', 'kandidatPartai', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kecamatan_id', $request->kecamatan_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->tps == null) {
                    $quickCount  = QuickCount::with('relawan', 'kandidatCalonAnggota', 'kandidatPartai', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kelurahan_id', $request->kelurahan_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->tps != null) {
                    $quickCount  = QuickCount::with('relawan', 'kandidatCalonAnggota', 'kandidatPartai', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kelurahan_id', $request->kelurahan_id],
                            ['tps', $request->tps],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } else {
                    return response()->json([
                        'message' => 'Sorry, quick count not found.',
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            return response()->json([
                'message' => 'List Quick Count.',
                'data' => $quickCount
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, quick count not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function addQuickCount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'metode_pengambilan' => 'nullable|in:Tatap Muka,Telepon',
                'propinsi_id' => 'required|numeric',
                'kabupaten_id' => 'required|numeric',
                'kecamatan_id' => 'required|numeric',
                'kelurahan_id' => 'required|numeric',
                'tps' => 'required|string',
                'nama_responden' => 'required|string',
                'no_hp' => 'nullable|string',
                'no_hp_lain' => 'nullable|string',
                'nik' => 'nullable|string|min:16|max:16',
                'usia' => 'nullable|string',
                'keterangan' => 'nullable|string',
                'kandidat_pilihan_id' => 'required|numeric',
                // 'kandidat_partai_id' => 'required|numeric',
                'bukti' => 'nullable|image|mimes:png,jpg,jpeg',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            if ($request->isLegislatif == 0) {
                $paslon = Paslon::find($request->kandidat_pilihan_id);
                if ($paslon != null) {
                    DB::beginTransaction();
                    $data = $request->all();
                    $data['tim_relawan_id'] = Auth::user()->current_team_id;
                    $data['relawan_id'] = Auth::user()->id;
                    $data['kandidat_calon_anggota_id'] = NULL;
                    $data['kandidat_pilihan_id'] = $request->kandidat_pilihan_id;
                    if ($request->hasFile('bukti')) {
                        $data['bukti'] = $request->file('bukti')->store('bukti-quick-count');
                        $data['bukti'] = env('APP_URL') . '/storage/' . $data['bukti'];
                        $request->file('bukti')->move('storage/bukti-quick-count',  $data['bukti']);
                    }
                    $res = QuickCount::create($data);
                    DB::commit();

                    return response()->json([
                        'message' => 'Quick count has been created.',
                        'data' => $res,
                    ], Response::HTTP_OK);
                }
            } else if ($request->isLegislatif == 1) {
                $calonAnggota = CalonAnggota::where('id', $request->kandidat_pilihan_id)->where('id_partai', $request->kandidat_partai_id)->first();
                // return $calonAnggota;
                if ($calonAnggota != null) {
                    DB::beginTransaction();
                    $data = $request->all();
                    $data['tim_relawan_id'] = Auth::user()->current_team_id;
                    $data['relawan_id'] = Auth::user()->id;
                    $data['kandidat_calon_anggota_id'] = $request->kandidat_pilihan_id;
                    $data['kandidat_pilihan_id'] = NULL;
                    if ($request->hasFile('bukti')) {
                        $data['bukti'] = $request->file('bukti')->store('bukti-quick-count');
                        $data['bukti'] = env('APP_URL') . '/storage/' . $data['bukti'];
                        $request->file('bukti')->move('storage/bukti-quick-count',  $data['bukti']);
                    }
                    // return $data;
                    $res = QuickCount::create($data);
                    DB::commit();

                    return response()->json([
                        'message' => 'Quick count has been created.',
                        'data' => $res,
                    ], Response::HTTP_OK);
                }
            }
            return response()->json([
                'message' => 'Sorry, quick count cannot be created.',
                'error' => 'Kandidat pilihan / paslon not found'
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, quick count cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateQuickCount(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'metode_pengambilan' => 'nullable|in:Tatap Muka,Telepon',
            'propinsi_id' => 'required|numeric',
            'kabupaten_id' => 'required|numeric',
            'kecamatan_id' => 'required|numeric',
            'kelurahan_id' => 'required|numeric',
            'tps' => 'required|string',
            'nama_responden' => 'required|string',
            'no_hp' => 'nullable|string',
            'no_hp_lain' => 'nullable|string',
            'nik' => 'nullable|string|min:16|max:16',
            'usia' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'kandidat_pilihan_id' => 'required|numeric',
            'bukti' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            $quickCount = QuickCount::findOrFail($id);
            if ($request->hasFile('bukti')) {
                if($quickCount->bukti != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $quickCount->bukti));
                }
                DB::beginTransaction();
                $filename = uniqid() . strtolower(Str::random(5)) . '.' . $request->bukti->extension();
                $request->file('bukti')->move('storage/bukti-quick-count',  $filename);
                $quickCount->forceFill([
                    'bukti' => env('APP_URL') . '/storage/bukti-quick-count/' . $filename,
                ])->save();
                DB::commit();
            }
            DB::beginTransaction();
            if ($request->isLegislatif == 0) {
                $quickCount->forceFill([
                    'metode_pengambilan' => $request->metode_pengambilan,
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'tps' => $request->tps,
                    'nama_responden' => $request->nama_responden,
                    'no_hp' => $request->no_hp,
                    'no_hp_lain' => $request->no_hp_lain,
                    'nik' => $request->nik,
                    'usia' => $request->usia,
                    'keterangan' => $request->keterangan,
                    'kandidat_pilihan_id' => $request->kandidat_pilihan_id,
                ])->save();
            } elseif ($request->isLegislatif == 1) {
                $quickCount->forceFill([
                    'metode_pengambilan' => $request->metode_pengambilan,
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'tps' => $request->tps,
                    'nama_responden' => $request->nama_responden,
                    'no_hp' => $request->no_hp,
                    'no_hp_lain' => $request->no_hp_lain,
                    'nik' => $request->nik,
                    'usia' => $request->usia,
                    'keterangan' => $request->keterangan,
                    'kandidat_calon_anggota_id' => $request->kandidat_pilihan_id,
                    'kandidat_partai_id' => $request->kandidat_partai_id,
                ])->save();
            }
            DB::commit();
            return response()->json([
                'message' => 'Quick count has been updated.',
                'data' => $quickCount,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, quick count cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detailQuickCount($id)
    {
        try {
            if ($quickCount  = QuickCount::with('relawan', 'kandidatPilihan', 'kandidatPartai', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan')
                ->whereHas("timRelawan", function ($q) {
                    $q->whereIn("id", [Auth::user()->current_team_id]);
                })->orderBy('created_at', 'desc')->find($id)
            ) {
                return response()->json([
                    'message' => 'Detail Quick Count.',
                    'data' => $quickCount
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Sorry, quick count not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, quick count not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteQuickCount($id)
    {
        try {
            $quickCount = QuickCount::find($id);
            if ($quickCount != null) {
                if($quickCount->bukti != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $quickCount->bukti));
                }
                $quickCount->delete();
                return response()->json([
                    'message' => 'Quick count has been deleted.',
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Sorry, quick count cannot be deleted.',
                'error' => 'Quick count not found'
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, quick count cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function hasilQuickCount(Request $request)
    {
        try {
            if ($request->propinsi_id == null) {
                $quickCount  = QuickCount::with('propinsi:id,name', 'kandidatPilihan:id')->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                ])->whereNotNull('kandidat_pilihan_id')
                ->select(
                    'propinsi_id',
                    'kandidat_pilihan_id',
                    DB::raw('count(*) as total_kandidat'),
                )->groupBy('propinsi_id', 'kandidat_pilihan_id')->get()->groupBy('propinsi_id')->map(function ($item) {
                    $row =  (object) ['total' => $item->sum('total_kandidat'), 'paslon' => $item];
                    return $row;
                });
            } elseif ($request->propinsi_id != null && $request->kabupaten_id == null) {
                $quickCount  = QuickCount::with('kabupaten:id,name', 'kandidatPilihan:id')->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['propinsi_id', $request->propinsi_id],
                ])->whereNotNull('kandidat_pilihan_id')
                ->select(
                    'kabupaten_id',
                    'kandidat_pilihan_id',
                    DB::raw('count(*) as total_kandidat'),
                )->groupBy('kabupaten_id', 'kandidat_pilihan_id')->get()->groupBy('kabupaten_id')->map(function ($item) {
                    $row =  (object) ['total' => $item->sum('total_kandidat'), 'paslon' => $item];
                    return $row;
                });
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
                $quickCount  = QuickCount::with('kecamatan:id,name', 'kandidatPilihan:id')->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['kabupaten_id', $request->kabupaten_id],
                ])->whereNotNull('kandidat_pilihan_id')
                ->select(
                    'kecamatan_id',
                    'kandidat_pilihan_id',
                    DB::raw('count(*) as total_kandidat'),
                )->groupBy('kecamatan_id', 'kandidat_pilihan_id')->get()->groupBy('kecamatan_id')->map(function ($item) {
                    $row =  (object) ['total' => $item->sum('total_kandidat'), 'paslon' => $item];
                    return $row;
                });
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null && $request->tps == null) {
                $quickCount  = QuickCount::with('kelurahan:id,name', 'kandidatPilihan:id')->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['kecamatan_id', $request->kecamatan_id],
                ])->whereNotNull('kandidat_pilihan_id')
                ->select(
                    'kelurahan_id',
                    'kandidat_pilihan_id',
                    DB::raw('count(*) as total_kandidat'),
                )->groupBy('kelurahan_id', 'kandidat_pilihan_id')->get()->groupBy('kelurahan_id')->map(function ($item) {
                    $row =  (object) ['total' => $item->sum('total_kandidat'), 'paslon' => $item];
                    return $row;
                });
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->tps == null) {
                $quickCount  = QuickCount::with('kandidatPilihan:id')->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['kelurahan_id', $request->kelurahan_id],
                ])->whereNotNull('kandidat_pilihan_id')
                ->select(
                    'tps',
                    'kandidat_pilihan_id',
                    DB::raw('count(*) as total_kandidat'),
                )->groupBy('tps', 'kandidat_pilihan_id')->get()->groupBy('tps')->map(function ($item) {
                    $row =  (object) ['total' => $item->sum('total_kandidat'), 'paslon' => $item];
                    return $row;
                });
            } else {
                return response()->json([
                    'message' => 'Sorry, quick count not found.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return response()->json([
                'message' => 'Hasil Quick Count.',
                'data' => $quickCount
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, quick count not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function hasilQuickCountPartai(Request $request)
    {
        try {
            if ($request->propinsi_id == null) {
                $quickCount  = QuickCount::with('propinsi:id,name', 'kandidatPartai:id')->where([
                    ['tim_relawan_id', Auth::user()->current_team_id]
                ])->whereNotNull('kandidat_calon_anggota_id')
                ->select(
                    'propinsi_id',
                    'kandidat_partai_id',
                    DB::raw('count(*) as total_partai'),
                )->groupBy('propinsi_id', 'kandidat_partai_id')->get()->groupBy('propinsi_id')->map(function ($item) {
                    $row =  (object) ['total' => $item->sum('total_partai'), 'paslon' => $item];
                    return $row;
                });
            } elseif ($request->propinsi_id != null && $request->kabupaten_id == null) {
                $quickCount  = QuickCount::with('kabupaten:id,name', 'kandidatPartai:id')->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['propinsi_id', $request->propinsi_id],
                ])->whereNotNull('kandidat_calon_anggota_id')
                ->select(
                    'kabupaten_id',
                    'kandidat_partai_id',
                    DB::raw('count(*) as total_partai'),
                )->groupBy('kabupaten_id', 'kandidat_partai_id')->get()->groupBy('kabupaten_id')->map(function ($item) {
                    $row =  (object) ['total' => $item->sum('total_partai'), 'paslon' => $item];
                    return $row;
                });
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
                $quickCount  = QuickCount::with('kecamatan:id,name', 'kandidatPartai:id')->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['kabupaten_id', $request->kabupaten_id],
                ])->whereNotNull('kandidat_calon_anggota_id')
                ->select(
                    'kecamatan_id',
                    'kandidat_partai_id',
                    DB::raw('count(*) as total_partai'),
                )->groupBy('kecamatan_id', 'kandidat_partai_id')->get()->groupBy('kecamatan_id')->map(function ($item) {
                    $row =  (object) ['total' => $item->sum('total_partai'), 'paslon' => $item];
                    return $row;
                });
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null && $request->tps == null) {
                $quickCount  = QuickCount::with('kelurahan:id,name', 'kandidatPartai:id')->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['kecamatan_id', $request->kecamatan_id],
                ])->whereNotNull('kandidat_calon_anggota_id')
                ->select(
                    'kelurahan_id',
                    'kandidat_partai_id',
                    DB::raw('count(*) as total_partai'),
                )->groupBy('kelurahan_id', 'kandidat_partai_id')->get()->groupBy('kelurahan_id')->map(function ($item) {
                    $row =  (object) ['total' => $item->sum('total_partai'), 'paslon' => $item];
                    return $row;
                });
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->tps == null) {
                $quickCount  = QuickCount::with('kandidatPartai:id')->where([
                    ['tim_relawan_id', Auth::user()->current_team_id],
                    ['kelurahan_id', $request->kelurahan_id],
                ])->whereNotNull('kandidat_calon_anggota_id')
                ->select(
                    'tps',
                    'kandidat_partai_id',
                    DB::raw('count(*) as total_partai'),
                )->groupBy('tps', 'kandidat_partai_id')->get()->groupBy('tps')->map(function ($item) {
                    $row =  (object) ['total' => $item->sum('total_partai'), 'paslon' => $item];
                    return $row;
                });
            } else {
                return response()->json([
                    'message' => 'Sorry, quick count not found.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return response()->json([
                'message' => 'Hasil Quick Count.',
                'data' => $quickCount
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, quick count not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function listTPSQuickCount()
    {
        $tpsquickCount  = QuickCount::where([
            ['tim_relawan_id', Auth::user()->current_team_id],
        ])->select(
            'tps',
        )->groupBy('tps')->get();

        return response()->json([
            'message' => 'List TPS from Quick Count.',
            'data' => $tpsquickCount
        ], Response::HTTP_OK);
    }
}
