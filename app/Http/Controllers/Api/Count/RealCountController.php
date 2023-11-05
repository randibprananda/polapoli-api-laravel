<?php

namespace App\Http\Controllers\Api\Count;

use App\Http\Controllers\Controller;
use App\Models\RealCount;
use App\Models\SuaraCalonAnggotaRealCount;
use App\Models\SuaraPartaiRealCount;
use App\Models\SuaraPaslonRealCount;
use App\Models\TPS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class RealCountController extends Controller
{
    public function addRealCount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'propinsi_id' => 'required|numeric',
                'kabupaten_id' => 'required|numeric',
                'kecamatan_id' => 'required|numeric',
                'kelurahan_id' => 'required|numeric',
                'tps' => 'required|string',
                'keterangan' => 'nullable|string',
                'suara_sah' => 'required|numeric',
                'suara_tidak_sah' => 'required|numeric',
                'foto_form' => 'nullable|image|mimes:png,jpg,jpeg',
                'paslon_id.*' => 'required|numeric',
                'partai_id.*' => 'required|numeric',
                'suara_sah_paslon.*' => 'required|numeric',
                'suara_sah_partai.*' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $filegambar = null;
            if ($request->hasFile('foto_form')) {
                $filegambar = 'foto_form_real_count_' . Str::uuid() . '.' . $request->foto_form->extension();
                $request->file('foto_form')->move('storage/foto_form_real_count/', $filegambar);
            }
            DB::beginTransaction();
            $realcount = RealCount::create([
                'propinsi_id' => $request->propinsi_id,
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
                'tps' => $request->tps,
                'keterangan' => $request->keterangan,
                'suara_sah' => $request->suara_sah,
                'suara_tidak_sah' => $request->suara_tidak_sah,
                'partai_id' => $request->partai_id,
                'suara_sah_partai' => $request->suara_sah_partai,
                'suara_tidak_sah_partai' => $request->suara_tidak_sah_partai,
                'saksi_relawan_id' => Auth::user()->id,
                'tim_relawan_id' => Auth::user()->current_team_id,
                'foto_form' => $filegambar != null ? env('APP_URL') . '/storage/foto_form_real_count/' . $filegambar : null,
            ]);
            if ($request->isLegislatif == 0) {
                $suaraPaslon = [];
                for ($i = 0; $i < count($request->paslon_id); $i++) {
                    $suaraPaslon[$i] = SuaraPaslonRealCount::create([
                        'real_count_id' => $realcount->id,
                        'paslon_id' => $request->paslon_id[$i],
                        'suara_sah_paslon' => $request->suara_sah_paslon[$i],
                    ]);
                }
            } elseif ($request->isLegislatif == 1) {
                // $suaraPaslon = [];
                $suaraPaslon = SuaraPartaiRealCount::create([
                    'real_count_id' => $realcount->id,
                    'partai_id' => $request->partai_id,
                    'suara_sah_partai' => $request->suara_sah_partai,
                ]);
                for ($i = 0; $i < count($request->paslon_id); $i++) {
                    SuaraCalonAnggotaRealCount::create([
                        'real_count_id' => $realcount->id,
                        'paslon_id' => $request->paslon_id[$i],
                        'suara_sah_paslon' => $request->suara_sah_paslon[$i],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Real count has been created.',
                'data' => ['realcount' => $realcount, 'suarapaslon' => $suaraPaslon],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, real count cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detailRealCount($id)
    {
        try {
            if ($realCount  = RealCount::with('timRelawan', 'saksiRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'suaraPaslon.paslon','suaraCalonAnggota.paslon', 'partai')
                ->whereHas("timRelawan", function ($q) {
                    $q->whereIn("id", [Auth::user()->current_team_id]);
                })->orderBy('created_at', 'desc')->find($id)
            ) {
                return response()->json([
                    'message' => 'Detail Real Count.',
                    'data' => $realCount
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Sorry, real count not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, real count not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function listRealCount(Request $request)
    {
        try {
            if ($request->isLegislatif == 0) {
                if ($request->propinsi_id == null) {
                    $realCount  = RealCount::with('saksiRelawan', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'suaraPaslon.paslon','suaraCalonAnggota.paslon')
                        ->whereNull('suara_sah_partai')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id == null) {
                    $realCount  = RealCount::with('saksiRelawan', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'suaraPaslon.paslon','suaraCalonAnggota.paslon')
                        ->whereNull('suara_sah_partai')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['propinsi_id', $request->propinsi_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
                    $realCount  = RealCount::with('saksiRelawan', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'suaraPaslon.paslon','suaraCalonAnggota.paslon')
                        ->whereNull('suara_sah_partai')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kabupaten_id', $request->kabupaten_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null && $request->tps == null) {
                    $realCount  = RealCount::with('saksiRelawan', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'suaraPaslon.paslon','suaraCalonAnggota.paslon')
                        ->whereNull('suara_sah_partai')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kecamatan_id', $request->kecamatan_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->tps == null) {
                    $realCount  = RealCount::with('saksiRelawan', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'suaraPaslon.paslon','suaraCalonAnggota.paslon')
                        ->whereNull('suara_sah_partai')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kelurahan_id', $request->kelurahan_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->tps != null) {
                    $realCount  = RealCount::with('saksiRelawan', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'suaraPaslon.paslon','suaraCalonAnggota.paslon')
                        ->whereNull('suara_sah_partai')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kelurahan_id', $request->kelurahan_id],
                            ['tps', $request->tps],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } else {
                    return response()->json([
                        'message' => 'Sorry, real count not found.',
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            if ($request->isLegislatif == 1) {
                if ($request->propinsi_id == null) {
                    $realCount  = RealCount::with('saksiRelawan', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan','suaraCalonAnggota.paslon', 'partai')
                        ->whereNotNull('suara_sah_partai')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id == null) {
                    $realCount  = RealCount::with('saksiRelawan', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan','suaraCalonAnggota.paslon', 'partai')
                        ->whereNotNull('suara_sah_partai')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['propinsi_id', $request->propinsi_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
                    $realCount  = RealCount::with('saksiRelawan', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan','suaraCalonAnggota.paslon', 'partai')
                        ->whereNotNull('suara_sah_partai')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kabupaten_id', $request->kabupaten_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null && $request->tps == null) {
                    $realCount  = RealCount::with('saksiRelawan', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan','suaraCalonAnggota.paslon', 'partai')
                        ->whereNotNull('suara_sah_partai')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kecamatan_id', $request->kecamatan_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->tps == null) {
                    $realCount  = RealCount::with('saksiRelawan', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan','suaraCalonAnggota.paslon', 'partai')
                        ->whereNotNull('suara_sah_partai')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kelurahan_id', $request->kelurahan_id],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->tps != null) {
                    $realCount  = RealCount::with('saksiRelawan', 'timRelawan', 'propinsi', 'kabupaten', 'kecamatan', 'kelurahan','suaraCalonAnggota.paslon', 'partai')
                        ->whereNotNull('suara_sah_partai')
                        ->where([
                            ['tim_relawan_id', Auth::user()->current_team_id],
                            ['kelurahan_id', $request->kelurahan_id],
                            ['tps', $request->tps],
                        ])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
                } else {
                    return response()->json([
                        'message' => 'Sorry, real count not found.',
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            return response()->json([
                'message' => 'List Real Count.',
                'data' => $realCount
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, real count not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateRealCount(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'propinsi_id' => 'required|numeric',
                'kabupaten_id' => 'required|numeric',
                'kecamatan_id' => 'required|numeric',
                'kelurahan_id' => 'required|numeric',
                'tps' => 'required|string',
                'keterangan' => 'nullable|string',
                'suara_sah' => 'required|numeric',
                'suara_tidak_sah' => 'required|numeric',
                'foto_form' => 'nullable|image|mimes:png,jpg,jpeg',
                'paslon_id.*' => 'required|numeric',
                'partai_id.*' => 'required|numeric',
                'suara_sah_paslon.*' => 'required|numeric',
                'suara_sah_partai.*' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $realCount = RealCount::find($id);
            if ($request->hasFile('foto_form')) {
                if($realCount->foto_form != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $realCount->foto_form));
                }
                $filegambar = 'foto_form_real_count_' . Str::uuid() . '.' . $request->foto_form->extension();
                $request->file('foto_form')->move('storage/foto_form_real_count/', $filegambar);
                $realCount->forceFill([
                    'foto_form' => env('APP_URL') . '/storage/foto_form_real_count/' . $filegambar,
                ])->save();
            }
            DB::beginTransaction();
            $realCount->forceFill([
                'propinsi_id' => $request->propinsi_id,
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
                'tps' => $request->tps,
                'keterangan' => $request->keterangan,
                'suara_sah' => $request->suara_sah,
                'suara_tidak_sah' => $request->suara_tidak_sah,
                'partai_id' => $request->partai_id,
                'suara_sah_partai' => $request->suara_sah_partai,
                'suara_tidak_sah_partai' => $request->suara_tidak_sah_partai,
                'saksi_relawan_id' => Auth::user()->id,
            ])->save();

            if ($request->isLegislatif == 0) {
                $suaraPaslon = [];
                if (SuaraPaslonRealCount::where('real_count_id', $realCount->id)->delete()) {
                    for ($i = 0; $i < count($request->paslon_id); $i++) {
                        $suaraPaslon[$i] = SuaraPaslonRealCount::create([
                            'real_count_id' => $realCount->id,
                            'paslon_id' => $request->paslon_id[$i],
                            'suara_sah_paslon' => $request->suara_sah_paslon[$i],
                        ]);
                    }
                }
            } elseif ($request->isLegislatif == 1) {
                $suaraPaslon = [];
                if (SuaraCalonAnggotaRealCount::where('real_count_id', $realCount->id)->delete()) {
                    for ($i = 0; $i < count($request->paslon_id); $i++) {
                        $suaraPaslon[$i] = SuaraCalonAnggotaRealCount::create([
                            'real_count_id' => $realCount->id,
                            'paslon_id' => $request->paslon_id[$i],
                            'suara_sah_paslon' => $request->suara_sah_paslon[$i],
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Real count has been updated.',
                'data' => ['realcount' => $realCount, 'suarapaslon' => $suaraPaslon],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, real count cannot updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function deleteRealCount($id)
    {
        try {
            $realcount = RealCount::find($id);
            if ($realcount != null) {
                if($realcount->foto_form != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $realcount->foto_form));
                }
                SuaraCalonAnggotaRealCount::where('real_count_id', $realcount->id)->delete();
                SuaraPartaiRealCount::where('real_count_id', $realcount->id)->delete();
                SuaraPaslonRealCount::where('real_count_id', $realcount->id)->delete();
                $realcount->delete();
                return response()->json([
                    'message' => 'Real count has been deleted.',
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Sorry, real count cannot be deleted.',
                'error' => 'Real count not found'
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, real count cannot be deleted.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function hasilRealCount(Request $request)
    {
        try {
            if ($request->propinsi_id == null) {
                $realCount  = SuaraPaslonRealCount::with(['realCount' => function ($q) {
                    $q->with('propinsi')->select('propinsi_id', 'id');
                }])
                    ->whereHas("realCount", function ($q) {
                        $q->where("tim_relawan_id", [Auth::user()->current_team_id]);
                    })->select('id', 'real_count_id', 'paslon_id', 'suara_sah_paslon')->get();

                $realCount = $realCount->groupBy(['realCount.propinsi_id', 'paslon_id']);

                $realCount = $realCount->map(function ($propinsi) {
                    return $propinsi->map(function ($paslon) {

                        $obj = (object)[
                            'total_per_paslon' => $paslon->sum('suara_sah_paslon'),
                            'propinsi' => $paslon->pluck('realCount.propinsi.id')[0],
                            'propinsi_name' => $paslon->pluck('realCount.propinsi.name')[0],
                            'paslon_id' => $paslon->pluck('paslon_id')[0]
                        ];
                        return $obj;
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } elseif ($request->propinsi_id != null && $request->kabupaten_id == null) {
                $realCount  = SuaraPaslonRealCount::with(['realCount' => function ($q) use ($request) {
                    $q->with('kabupaten')->select('kabupaten_id', 'id')->where('propinsi_id', $request->propinsi_id);
                }])
                    ->whereHas("realCount", function ($q) {
                        $q->where("tim_relawan_id", [Auth::user()->current_team_id]);
                    })->select('id', 'real_count_id', 'paslon_id', 'suara_sah_paslon')->get();

                $realCount = $realCount->groupBy(['realCount.kabupaten_id', 'paslon_id']);

                $realCount = $realCount->map(function ($kabupaten) {
                    return $kabupaten->map(function ($paslon) {
                        if ($paslon->pluck('realCount') != null) {
                            $obj = (object)[
                                'total_per_paslon' => $paslon->sum('suara_sah_paslon'),
                                'kabupaten' => $paslon->pluck('realCount.kabupaten.id')[0],
                                'kabupaten_name' => $paslon->pluck('realCount.kabupaten.name')[0],
                                'paslon_id' => $paslon->pluck('paslon_id')[0]
                            ];
                            return $obj;
                        }
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()->kabupaten != null) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
                $realCount  = SuaraPaslonRealCount::with(['realCount' => function ($q) use ($request) {
                    $q->with('kecamatan')->select('kecamatan_id', 'id')->where('kabupaten_id', $request->kabupaten_id);
                }])
                    ->whereHas("realCount", function ($q) {
                        $q->where("tim_relawan_id", [Auth::user()->current_team_id]);
                    })->select('id', 'real_count_id', 'paslon_id', 'suara_sah_paslon')->get();

                $realCount = $realCount->groupBy(['realCount.kecamatan_id', 'paslon_id']);

                $realCount = $realCount->map(function ($kecamatan) {
                    return $kecamatan->map(function ($paslon) {
                        if ($paslon->pluck('realCount') != null) {
                            $obj = (object)[
                                'total_per_paslon' => $paslon->sum('suara_sah_paslon'),
                                'kecamatan' => $paslon->pluck('realCount.kecamatan.id')[0],
                                'kecamatan_name' => $paslon->pluck('realCount.kecamatan.name')[0],
                                'paslon_id' => $paslon->pluck('paslon_id')[0]
                            ];
                            return $obj;
                        }
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()->kecamatan != null) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null && $request->tps == null) {
                $realCount  = SuaraPaslonRealCount::with(['realCount' => function ($q) use ($request) {
                    $q->with('kelurahan')->select('kelurahan_id', 'id')->where('kecamatan_id', $request->kecamatan_id);
                }])
                    ->whereHas("realCount", function ($q) {
                        $q->where("tim_relawan_id", [Auth::user()->current_team_id]);
                    })->select('id', 'real_count_id', 'paslon_id', 'suara_sah_paslon')->get();

                $realCount = $realCount->groupBy(['realCount.kelurahan_id', 'paslon_id']);

                $realCount = $realCount->map(function ($kelurahan) {
                    return $kelurahan->map(function ($paslon) {
                        if ($paslon->pluck('realCount') != null) {
                            $obj = (object)[
                                'total_per_paslon' => $paslon->sum('suara_sah_paslon'),
                                'kelurahan' => $paslon->pluck('realCount.kelurahan.id')[0],
                                'kelurahan_name' => $paslon->pluck('realCount.kelurahan.name')[0],
                                'paslon_id' => $paslon->pluck('paslon_id')[0]
                            ];
                            return $obj;
                        }
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()->kelurahan != null) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->tps == null) {
                $realCount  = SuaraPaslonRealCount::with(['realCount' => function ($q) use ($request) {
                    $q->select('tps', 'id')->where('kelurahan_id', $request->kelurahan_id);
                }])
                    ->whereHas("realCount", function ($q) {
                        $q->where("tim_relawan_id", [Auth::user()->current_team_id]);
                    })->select('id', 'real_count_id', 'paslon_id', 'suara_sah_paslon')->get();

                $realCount = $realCount->groupBy(['realCount.tps', 'paslon_id']);

                $realCount = $realCount->map(function ($tps) {
                    return $tps->map(function ($paslon) {
                        if ($paslon->pluck('realCount') != null) {
                            $obj = (object)[
                                'total_per_paslon' => $paslon->sum('suara_sah_paslon'),
                                'nomor_tps' => $paslon->pluck('realCount.tps'),
                                'paslon_id' => $paslon->pluck('paslon_id')[0]
                            ];
                            return $obj;
                        }
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()->nomor_tps->first() != null) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } else {
                return response()->json([
                    'message' => 'Sorry, real count not found.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $jmlTPS = TPS::where('tim_relawan_id', Auth::user()->current_team_id)->count();
            $TPSmasuk = TPS::where('tim_relawan_id', Auth::user()->current_team_id)->sum('jumlah_tps');
            $tps = $TPSmasuk / $jmlTPS;
            $presentase = round($tps);

            $TPS = [
                'presentase' => $presentase,
                'jml_tps' => $jmlTPS,
                'tps_masuk' => $TPSmasuk,
            ];

            return response()->json([
                'message' => 'Hasil Real Count.',
                'meta' => $TPS,
                'data' => $filtered
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, real count not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function hasilRealCountCalonAnggota(Request $request)
    {
        try {
            if ($request->propinsi_id == null) {
                $realCount  = SuaraCalonAnggotaRealCount::with(['realCount' => function ($q) {
                    $q->with('propinsi')->select('propinsi_id', 'id');
                }])
                    ->whereHas("realCount", function ($q) {
                        $q->where("tim_relawan_id", [Auth::user()->current_team_id]);
                    })->select('id', 'real_count_id', 'paslon_id', 'suara_sah_paslon')->get();

                $realCount = $realCount->groupBy(['realCount.propinsi_id', 'paslon_id']);

                $realCount = $realCount->map(function ($propinsi) {
                    return $propinsi->map(function ($paslon) {

                        $obj = (object)[
                            'total_per_paslon' => $paslon->sum('suara_sah_paslon'),
                            'propinsi' => $paslon->pluck('realCount.propinsi.id')[0],
                            'propinsi_name' => $paslon->pluck('realCount.propinsi.name')[0],
                            'paslon_id' => $paslon->pluck('paslon_id')[0]
                        ];
                        return $obj;
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } elseif ($request->propinsi_id != null && $request->kabupaten_id == null) {
                $realCount  = SuaraCalonAnggotaRealCount::with(['realCount' => function ($q) use ($request) {
                    $q->with('kabupaten')->select('kabupaten_id', 'id')->where('propinsi_id', $request->propinsi_id);
                }])
                    ->whereHas("realCount", function ($q) {
                        $q->where("tim_relawan_id", [Auth::user()->current_team_id]);
                    })->select('id', 'real_count_id', 'paslon_id', 'suara_sah_paslon')->get();

                $realCount = $realCount->groupBy(['realCount.kabupaten_id', 'paslon_id']);

                $realCount = $realCount->map(function ($kabupaten) {
                    return $kabupaten->map(function ($paslon) {
                        if ($paslon->pluck('realCount') != null) {
                            $obj = (object)[
                                'total_per_paslon' => $paslon->sum('suara_sah_paslon'),
                                'kabupaten' => $paslon->pluck('realCount.kabupaten.id')[0],
                                'kabupaten_name' => $paslon->pluck('realCount.kabupaten.name')[0],
                                'paslon_id' => $paslon->pluck('paslon_id')[0]
                            ];
                            return $obj;
                        }
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()->kabupaten != null) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {
                $realCount  = SuaraCalonAnggotaRealCount::with(['realCount' => function ($q) use ($request) {
                    $q->with('kecamatan')->select('kecamatan_id', 'id')->where('kabupaten_id', $request->kabupaten_id);
                }])
                    ->whereHas("realCount", function ($q) {
                        $q->where("tim_relawan_id", [Auth::user()->current_team_id]);
                    })->select('id', 'real_count_id', 'paslon_id', 'suara_sah_paslon')->get();

                $realCount = $realCount->groupBy(['realCount.kecamatan_id', 'paslon_id']);

                $realCount = $realCount->map(function ($kecamatan) {
                    return $kecamatan->map(function ($paslon) {
                        if ($paslon->pluck('realCount') != null) {
                            $obj = (object)[
                                'total_per_paslon' => $paslon->sum('suara_sah_paslon'),
                                'kecamatan' => $paslon->pluck('realCount.kecamatan.id')[0],
                                'kecamatan_name' => $paslon->pluck('realCount.kecamatan.name')[0],
                                'paslon_id' => $paslon->pluck('paslon_id')[0]
                            ];
                            return $obj;
                        }
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()->kecamatan != null) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null && $request->tps == null) {
                $realCount  = SuaraCalonAnggotaRealCount::with(['realCount' => function ($q) use ($request) {
                    $q->with('kelurahan')->select('kelurahan_id', 'id')->where('kecamatan_id', $request->kecamatan_id);
                }])
                    ->whereHas("realCount", function ($q) {
                        $q->where("tim_relawan_id", [Auth::user()->current_team_id]);
                    })->select('id', 'real_count_id', 'paslon_id', 'suara_sah_paslon')->get();

                $realCount = $realCount->groupBy(['realCount.kelurahan_id', 'paslon_id']);

                $realCount = $realCount->map(function ($kelurahan) {
                    return $kelurahan->map(function ($paslon) {
                        if ($paslon->pluck('realCount') != null) {
                            $obj = (object)[
                                'total_per_paslon' => $paslon->sum('suara_sah_paslon'),
                                'kelurahan' => $paslon->pluck('realCount.kelurahan.id')[0],
                                'kelurahan_name' => $paslon->pluck('realCount.kelurahan.name')[0],
                                'paslon_id' => $paslon->pluck('paslon_id')[0]
                            ];
                            return $obj;
                        }
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()->kelurahan != null) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->tps == null) {
                $realCount  = SuaraCalonAnggotaRealCount::with(['realCount' => function ($q) use ($request) {
                    $q->select('tps', 'id')->where('kelurahan_id', $request->kelurahan_id);
                }])
                    ->whereHas("realCount", function ($q) {
                        $q->where("tim_relawan_id", [Auth::user()->current_team_id]);
                    })->select('id', 'real_count_id', 'paslon_id', 'suara_sah_paslon')->get();

                $realCount = $realCount->groupBy(['realCount.tps', 'paslon_id']);

                $realCount = $realCount->map(function ($tps) {
                    return $tps->map(function ($paslon) {
                        if ($paslon->pluck('realCount') != null) {
                            $obj = (object)[
                                'total_per_paslon' => $paslon->sum('suara_sah_paslon'),
                                'nomor_tps' => $paslon->pluck('realCount.tps'),
                                'paslon_id' => $paslon->pluck('paslon_id')[0]
                            ];
                            return $obj;
                        }
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()->nomor_tps->first() != null) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } else {
                return response()->json([
                    'message' => 'Sorry, real count not found.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $jmlTPS = TPS::where('tim_relawan_id', Auth::user()->current_team_id)->count();
            $TPSmasuk = TPS::where('tim_relawan_id', Auth::user()->current_team_id)->sum('jumlah_tps');
            $tps = $TPSmasuk / $jmlTPS;
            $presentase = round($tps);

            $TPS = [
                'presentase' => $presentase,
                'jml_tps' => $jmlTPS,
                'tps_masuk' => $TPSmasuk,
            ];

            return response()->json([
                'message' => 'Hasil Real Count.',
                'meta' => $TPS,
                'data' => $filtered
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, real count not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function hasilRealCountPartai(Request $request)
    {
        try {
            if ($request->propinsi_id == null) {

                $realCount = RealCount::with('propinsi', 'partai')
                            ->select('tim_relawan_id', 'id', 'propinsi_id', 'partai_id', 'suara_sah_partai')
                            ->where("tim_relawan_id", Auth::user()->current_team_id)
                            ->get();

                $realCount = $realCount->groupBy(['propinsi_id', 'partai_id']);

                $realCount = $realCount->map(function ($propinsi) {
                    return $propinsi->map(function ($partai) {

                        $obj = (object)[
                            'total_per_partai' => $partai->sum('suara_sah_partai'),
                            'propinsi' => $partai->pluck('propinsi.id')[0],
                            'propinsi_name' => $partai->pluck('propinsi.name')[0],
                            'partai_id' => $partai->pluck('partai.id')[0],
                            'partai_name' => $partai->pluck('partai.nama_partai')[0],
                        ];
                        return $obj;
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } elseif ($request->propinsi_id != null && $request->kabupaten_id == null) {

                $realCount = RealCount::with('kabupaten', 'partai')
                            ->select('tim_relawan_id', 'id', 'kabupaten_id','propinsi_id', 'partai_id', 'suara_sah_partai')
                            ->where('propinsi_id', $request->propinsi_id)
                            ->where("tim_relawan_id", Auth::user()->current_team_id)
                            ->get();

                $realCount = $realCount->groupBy(['kabupaten_id', 'partai_id']);

                $realCount = $realCount->map(function ($kabupaten) {
                    return $kabupaten->map(function ($partai) {
                        if ($partai->pluck('realCount') != null) {
                            $obj = (object)[
                                'total_per_partai' => $partai->sum('suara_sah_partai'),
                                'kabupaten' => $partai->pluck('kabupaten.id')[0],
                                'kabupaten_name' => $partai->pluck('kabupaten.name')[0],
                                'partai_id' => $partai->pluck('partai.id')[0],
                                'partai_name' => $partai->pluck('partai.nama_partai')[0],
                            ];
                            return $obj;
                        }
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()->kabupaten != null) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null) {

                $realCount = RealCount::with('kecamatan', 'partai')
                            ->select('tim_relawan_id', 'id', 'kabupaten_id','kecamatan_id', 'partai_id', 'suara_sah_partai')
                            ->where('kabupaten_id', $request->kabupaten_id)
                            ->where("tim_relawan_id", Auth::user()->current_team_id)
                            ->get();

                $realCount = $realCount->groupBy(['kecamatan_id', 'partai_id']);

                $realCount = $realCount->map(function ($kecamatan) {
                    return $kecamatan->map(function ($partai) {
                        if ($partai->pluck('realCount') != null) {
                            $obj = (object)[
                                'kecamatan' => $partai->pluck('kecamatan.id')[0],
                                'kecamatan_name' => $partai->pluck('kecamatan.name')[0],
                                'total_per_partai' => $partai->sum('suara_sah_partai'),
                                'partai_id' => $partai->pluck('partai.id')[0],
                                'partai_name' => $partai->pluck('partai.nama_partai')[0],
                            ];
                            return $obj;
                        }
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()->kecamatan != null) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null && $request->tps == null) {

                    $realCount = RealCount::with('kelurahan', 'partai')
                            ->select('tim_relawan_id', 'id', 'kelurahan_id','kecamatan_id', 'partai_id', 'suara_sah_partai')
                            ->where('kecamatan_id', $request->kecamatan_id)
                            ->where("tim_relawan_id", Auth::user()->current_team_id)
                            ->get();

                $realCount = $realCount->groupBy(['kelurahan_id', 'partai_id']);

                $realCount = $realCount->map(function ($kelurahan) {
                    return $kelurahan->map(function ($partai) {
                        if ($partai->pluck('realCount') != null) {
                            $obj = (object)[
                                'kelurahan' => $partai->pluck('kelurahan.id')[0],
                                'kelurahan_name' => $partai->pluck('kelurahan.name')[0],
                                'total_per_partai' => $partai->sum('suara_sah_partai'),
                                'partai_id' => $partai->pluck('partai.id')[0],
                                'partai_name' => $partai->pluck('partai.nama_partai')[0],
                            ];
                            return $obj;
                        }
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()->kelurahan != null) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } elseif ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->tps == null) {

                $realCount = RealCount::with('tps', 'partai')
                            ->select('tim_relawan_id', 'id', 'kelurahan_id', 'partai_id', 'suara_sah_partai')
                            ->where('kelurahan_id', $request->kelurahan_id)
                            ->where("tim_relawan_id", Auth::user()->current_team_id)
                            ->get();

                $realCount = $realCount->groupBy(['tps', 'partai_id']);

                $realCount = $realCount->map(function ($tps) {
                    return $tps->map(function ($partai) {
                        if ($partai->pluck('realCount') != null) {
                            $obj = (object)[
                                'total_per_partai' => $partai->sum('suara_sah_partai'),
                                'nomor_tps' => $partai->pluck('tps'),
                                'partai_id' => $partai->pluck('partai_id')[0],
                                'partai_name' => $partai->pluck('partai.nama_partai')[0],
                            ];
                            return $obj;
                        }
                    });
                });

                $filtered = array();
                for ($i = 0; $i < count($realCount); $i++) {
                    if ($realCount->values()->get($i)->first()->nomor_tps->first() != null) {
                        array_push($filtered, $realCount->values()->get($i));
                    }
                }
            } else {
                return response()->json([
                    'message' => 'Sorry, real count not found.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $jmlTPS = TPS::where('tim_relawan_id', Auth::user()->current_team_id)->count();
            $TPSmasuk = TPS::where('tim_relawan_id', Auth::user()->current_team_id)->sum('jumlah_tps');
            $tps = $TPSmasuk / $jmlTPS;
            $presentase = round($tps);

            $TPS = [
                'presentase' => $presentase,
                'jml_tps' => $jmlTPS,
                'tps_masuk' => $TPSmasuk,
            ];
            return response()->json([
                'message' => 'Hasil Real Count.',
                'meta'    => $TPS,
                'data' => $filtered
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, real count not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
