<?php

namespace App\Http\Controllers\Api\Survey;

use App\Http\Controllers\Controller;
use App\Models\DPT;
use App\Models\FormSurvey;
use App\Models\Issue;
use App\Models\LoLa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }]
        if ($request->search != null) {
            $formSurvey = FormSurvey::with('propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'dpt', 'issue')
                ->where("tim_relawan_id", Auth::user()->current_team_id)
                ->where('flag', 'survey')
                ->withCount("lola as total_answer", "fieldForms as total_pertanyaan")
                ->orderBy('created_at', 'desc')->where("judul_survey", "LIKE", "%$request->search%")
                ->paginate(10)->withQueryString();
        } else {
            $formSurvey = FormSurvey::with('propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'dpt', 'issue')
                ->where("tim_relawan_id", Auth::user()->current_team_id)
                ->where('flag', 'survey')
                ->withCount("lola as total_answer", "fieldForms as total_pertanyaan")
                ->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
        }
        if ($formSurvey != null) {
            return response()->json([
                'message' => 'List of Form Survey',
                'data' => $formSurvey,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No form survey available',
            ], Response::HTTP_OK);
        }
    }

    public function getAllForRelawan(Request $request)
    {
        // $surveis = FormSurvey::where([['tim_relawan_id', Auth::user()->current_team_id],
        // ['status','draft']])->get();
        // // Provinsi,Kota/Kab,Kecamatan,Kelurahan,Dapil

        // $user = User::with('detailUser','timRelawans')
        // ->whereHas("timRelawans", function ($p) {
        //     $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
        // })->find(Auth::user()->id);

        // $data = [];

        // for ($i=0; $i < count($surveis); $i++) {
        //     if ($surveis[$i]->tingkat_survei == 'Provinsi') {
        //         $propinsiUser = $user->detailUser->propinsi_id;
        //         if ($propinsiUser == $surveis[$i]->propinsi_id) {
        //             $data[$i]=$surveis[$i];
        //         }
        //     }else if ($surveis[$i]->tingkat_survei == 'Kota/Kab') {
        //         $kabUser = $user->detailUser->kabupaten_id;
        //         if ($kabUser == $surveis[$i]->kabupaten_id) {
        //             $data[$i]=$surveis[$i];
        //         }
        //     }elseif ($surveis[$i]->tingkat_survei == 'Kecamatan') {
        //         $kecUser = $user->detailUser->kecamatan_id;
        //         if ($kecUser == $surveis[$i]->kecamatan_id) {
        //             $data[$i]=$surveis[$i];
        //         }
        //     }elseif ($surveis[$i]->tingkat_survei == 'Kelurahan') {
        //         $kelUser = $user->detailUser->kelurahan_id;
        //         if ($kelUser == $surveis[$i]->kelurahan_id) {
        //             $data[$i]=$surveis[$i];
        //         }
        //     }elseif ($surveis[$i]->tingkat_survei == 'Dapil') {
        //         $dapilUser = $user->detailUser->kabupaten_id;
        //         if ($dapilUser == $surveis[$i]->kabupaten_id) {
        //             $data[$i]=$surveis[$i];
        //         }
        //     }
        // }

        if ($request->search != null) {
            $data = FormSurvey::with('propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'dpt', 'issue')
                ->where([["tim_relawan_id", Auth::user()->current_team_id],['status', '=', 'publish']])
                ->where('flag', 'survey')
                ->withCount("lola as total_answer", "fieldForms as total_pertanyaan")
                ->orderBy('created_at', 'desc')->where("judul_survey", "LIKE", "%$request->search%")
                ->paginate(10)->withQueryString();
        } else {
            $data = FormSurvey::with('propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'dpt', 'issue')
                ->where([["tim_relawan_id", Auth::user()->current_team_id],['status', '=', 'publish']])
                ->where('flag', 'survey')
                ->withCount("lola as total_answer", "fieldForms as total_pertanyaan")
                ->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
        }

        if ($data != null) {
            return response()->json([
                'message' => 'List of Form Survey',
                $data,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No form survey available',
            ], Response::HTTP_OK);
        }
    }

    public function store(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'judul_survey' => 'required|string|max:255',
            'tingkat_survei' => 'required|string|in:Provinsi,Kota/Kab,Kecamatan,Kelurahan,Dapil',
            'propinsi_id' => 'nullable|numeric',
            'kabupaten_id' => 'nullable|numeric',
            'kecamatan_id' => 'nullable|numeric',
            'kelurahan_id' => 'nullable|numeric',
            'dapil' => 'nullable|string|max:255',
            'target_responden' => 'required|numeric',
            'pembukaan_survey' => 'required|string|max:255',
            'penutupan_survey' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $tim_relawan_id = Auth::user()->current_team_id;
            $dpt = DPT::where('tim_relawan_id', $tim_relawan_id)->value('id');
            $issue = Issue::where('tim_relawan_id', $tim_relawan_id)->value('id');
            $survey = FormSurvey::create(
                [
                    'judul_survey' => $request->judul_survey,
                    'tingkat_survei' => $request->tingkat_survei,
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'dapil' => $request->dapil,
                    'target_responden' => $request->target_responden,
                    'pembukaan_survey' => $request->pembukaan_survey,
                    'penutupan_survey' => $request->penutupan_survey,
                    'created_by' => Auth::user()->id,
                    'tim_relawan_id' => $tim_relawan_id,
                    'id_dpt'=> $dpt,
                    'id_issues' => $issue,
                    'status' => 'draft',
                    'flag' => 'survey'
                ]
            );

            return response()->json([
                'message' => 'New survey has been created',
                'data' => $survey
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, survey cannot be created',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            // if (!Auth::user()->customHasPermissionTo(12)) {
            //     return response()->json([
            //         'message' => 'FORBIDDEN',
            //     ], Response::HTTP_FORBIDDEN);
            // }
            if ($survey = FormSurvey::with('propinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'fieldForms')->find($id)) {
                return response()->json([
                    'message' => 'Detail Survey.',
                    'data' => $survey
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, Survey not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'judul_survey' => 'required|string|max:255',
            'tingkat_survei' => 'required|string|in:Provinsi,Kota/Kab,Kecamatan,Kelurahan,Dapil',
            'propinsi_id' => 'nullable|numeric',
            'kabupaten_id' => 'nullable|numeric',
            'kecamatan_id' => 'nullable|numeric',
            'kelurahan_id' => 'nullable|numeric',
            'dapil' => 'nullable|string|max:255',
            'target_responden' => 'required|numeric',
            'pembukaan_survey' => 'required|string|max:255',
            'penutupan_survey' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $tim_relawan_id = Auth::user()->current_team_id;
            $dpt = DPT::where('tim_relawan_id', $tim_relawan_id)->value('id');
            $issue = Issue::where('tim_relawan_id', $tim_relawan_id)->value('id');
            $survey = FormSurvey::find($id);
            $survey->forceFill(
                [
                    'judul_survey' => $request->judul_survey,
                    'tingkat_survei' => $request->tingkat_survei,
                    'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'dapil' => $request->dapil,
                    'target_responden' => $request->target_responden,
                    'pembukaan_survey' => $request->pembukaan_survey,
                    'penutupan_survey' => $request->penutupan_survey,
                    'created_by' => Auth::user()->id,
                    'tim_relawan_id' => $tim_relawan_id,
                    'id_dpt'=> $dpt,
                    'id_issues' => $issue,
                    'status' => 'draft',
                    'flag' => 'survey'
                ]
            )->save();
            return response()->json([
                'message' => 'Survey has been updated.',
                'data' => $survey
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, survey cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        try {
            FormSurvey::find($id)->delete();
            return response()->json([
                'message' => 'Survey has been deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, Survey cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function draftPublish($id)
    {
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        try {
            $survey = FormSurvey::find($id);
            $survey->forceFill(
                [
                    'status' => 'publish',
                ]
            )->save();
            return response()->json([
                'message' => 'Survey has been published.',
                'data' => $survey
            ], Response::HTTP_OK);
            // if ($survey->status == 'draft') {
            //     $survey->forceFill(
            //         [
            //             'status' => 'publish',
            //         ]
            //     )->save();
            //     return response()->json([
            //         'message' => 'Survey has been published.',
            //         'data' => $survey
            //     ], Response::HTTP_OK);
            // } else {
            //     $survey->forceFill(
            //         [
            //             'status' => 'draft',
            //         ]
            //     )->save();
            //     return response()->json([
            //         'message' => 'Survey has been published.',
            //         'data' => $survey
            //     ], Response::HTTP_OK);
            // }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, Survey cannot be published.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function hasilSurvey(Request $request, $form_survey_id)
    {
        try {
            if ($request->search != null) {
                $survey = LoLa::with('formSurvey:id,judul_survey', 'lolaFormAnswers.fieldForm')->whereHas('formSurvey', function ($q) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id);
                })->where('form_survey_id', $form_survey_id)
                    ->orderBy('created_at', 'desc')
                    ->where("nama_responden", "LIKE", "%$request->search%")
                    ->paginate(10)->withQueryString();
            } else {
                $survey = LoLa::with('formSurvey:id,judul_survey', 'lolaFormAnswers.fieldForm')->whereHas('formSurvey', function ($q) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id);
                })->where('form_survey_id', $form_survey_id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10)->withQueryString();
            }
            return response()->json([
                'message' => 'List of answered survey.',
                'data' => $survey
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'List not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function hasilSurveyByRelawan(Request $request, $form_survey_id)
    {
        try {
            if ($request->tingkat_koordinator == 'propinsi'  && $request->propinsi_id == null && $request->kabupaten_id == null  && $request->kecamatan_id == null && $request->kelurahan_id == null) {
                $survey = User::with(
                    array('userRoleTim' => function($r)
                    {
                        $r->where("tim_relawan_id", '=',
                        Auth::user()->current_team_id)
                        ->with('role','timRelawan');
                    },
                    'detailUser.tingkatKoordinator',
                    'detailUser.propinsi',
                    'detailUser.kabupaten',
                    'detailUser.kecamatan',
                    'detailUser.kelurahan')
                  )
                    ->whereHas("userRoleTim.role", function ($q) {
                        $q->where("id", '=', 3);
                    })
                    ->whereHas("timRelawans", function ($p) {
                        $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                    })->whereHas('detailUser.tingkatKoordinator', function ($q) {
                        $q->where('nama_tingkat_koordinator', 'Provinsi');
                    })->get();
            } elseif ($request->tingkat_koordinator == 'kabupaten'  && $request->propinsi_id != null && $request->kabupaten_id == null  && $request->kecamatan_id == null && $request->kelurahan_id == null  && $request->rt == null  && $request->rw == null) {
                $survey = User::with(
                    array('userRoleTim' => function($r)
                    {
                        $r->where("tim_relawan_id", '=',
                        Auth::user()->current_team_id)
                        ->with('role','timRelawan');
                    },
                    'detailUser.tingkatKoordinator',
                    'detailUser.propinsi',
                    'detailUser.kabupaten',
                    'detailUser.kecamatan',
                    'detailUser.kelurahan')
                    )
                    ->whereHas("userRoleTim.role", function ($q) {
                        $q->where("id", '=', 3);
                    })
                    ->whereHas("timRelawans", function ($p) {
                        $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                    })->whereHas('detailUser', function ($q) use ($request) {
                        $q->where('propinsi_id', $request->propinsi_id);
                    })->whereHas('detailUser.tingkatKoordinator', function ($q) {
                        $q->where('nama_tingkat_koordinator', 'Kota/Kab');
                    })->get();
            } elseif ($request->tingkat_koordinator == 'kecamatan' && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id == null && $request->kelurahan_id == null  && $request->rt == null  && $request->rw == null) {

                $survey = User::with(
                    array('userRoleTim' => function($r)
                    {
                        $r->where("tim_relawan_id", '=',
                        Auth::user()->current_team_id)
                        ->with('role','timRelawan');
                    },
                    'detailUser.tingkatKoordinator',
                    'detailUser.propinsi',
                    'detailUser.kabupaten',
                    'detailUser.kecamatan',
                    'detailUser.kelurahan')
                    )
                    ->whereHas("userRoleTim.role", function ($q) {
                        $q->where("id", '=', 3);
                    })
                    ->whereHas("timRelawans", function ($p) {
                        $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                    })->whereHas('detailUser', function ($q) use ($request) {
                        $q->where('kabupaten_id', $request->kabupaten_id);
                    })->whereHas('detailUser.tingkatKoordinator', function ($q) {
                        $q->where('nama_tingkat_koordinator', 'kecamatan');
                    })->get();
            } elseif ($request->tingkat_koordinator == 'kelurahan' && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id == null  && $request->rt == null  && $request->rw == null) {
                $survey = User::with(
                    array('userRoleTim' => function($r)
                    {
                        $r->where("tim_relawan_id", '=',
                        Auth::user()->current_team_id)
                        ->with('role','timRelawan');
                    },
                    'detailUser.tingkatKoordinator',
                    'detailUser.propinsi',
                    'detailUser.kabupaten',
                    'detailUser.kecamatan',
                    'detailUser.kelurahan')
                    )
                    ->whereHas("userRoleTim.role", function ($q) {
                        $q->where("id", '=', 3);
                    })
                    ->whereHas("timRelawans", function ($p) {
                        $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                    })->whereHas('detailUser', function ($q) use ($request) {
                        $q->where('kecamatan_id', $request->kecamatan_id);
                    })->whereHas('detailUser.tingkatKoordinator', function ($q) {
                        $q->where('nama_tingkat_koordinator', 'kelurahan');
                    })->get();
            } elseif ($request->tingkat_koordinator == 'rt/rw' && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null  && $request->rt == null  && $request->rw == null) {

                $survey = User::with(
                    array('userRoleTim' => function($r)
                    {
                        $r->where("tim_relawan_id", '=',
                        Auth::user()->current_team_id)
                        ->with('role','timRelawan');
                    },
                    'detailUser.tingkatKoordinator',
                    'detailUser.daftarAnggota',
                    'detailUser.propinsi',
                    'detailUser.kabupaten',
                    'detailUser.kecamatan',
                    'detailUser.kelurahan' )
                    )
                    ->whereHas("userRoleTim.role", function ($q) {
                        $q->where("id", '=', 3);
                    })
                    ->whereHas("timRelawans", function ($p) {
                        $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
                    })->whereHas('detailUser', function ($q) use ($request) {
                        $q->where('kelurahan_id', $request->kelurahan_id);
                    })->whereHas('detailUser.tingkatKoordinator', function ($q) {
                        $q->where('nama_tingkat_koordinator', 'RT/RW');
                    })->get();
            } elseif ($request->tingkat_koordinator == 'rt/rw' && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null  && $request->rt != null  && $request->rw != null) {
                $survey = LoLa::with('formSurvey:id,judul_survey', 'lolaFormAnswers.fieldForm')->whereHas('formSurvey', function ($q) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id);
                })->whereHas('user.userRoleTim.role', function ($q) {
                    $q->where('id', "=", 4);
                })->where('form_survey_id', $form_survey_id)->paginate(10);
            } else {
                return response()->json([
                    'message' => 'Parameter not valid',
                ], Response::HTTP_BAD_REQUEST);
            }
            return response()->json([
                'message' => 'List of answered survey.',
                'data' => $survey
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'List not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showByRelawan($form_survey_id, $relawan_id)
    {
        try {
            $answer = Lola::with('formSurvey:id,judul_survey', 'lolaFormAnswers.fieldForm')->where([['form_survey_id', $form_survey_id], ['user_id', $relawan_id]])
                ->whereHas('user.userRoleTim.role', function ($q) {
                    $q->where('id', "=", 4);
                })->paginate(10);
            return response()->json([
                'message' => 'List Answer Relawan.',
                'data' => $answer
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, Answer not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllResultByRelawan(Request $request)
    {
        try {
            if ($request->type != null) {
                $answer = Lola::with('formSurvey', 'lolaFormAnswers.fieldForm')
                ->whereHas('formSurvey', function ($q) use ($request) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id)
                    ->where('flag', $request->type);
                })->whereHas('user.userRoleTim.role', function ($r) {
                    $r->where('id', "=", 4);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
            }
            if ($request->propinsi_id != null) {
                $answer = Lola::with('formSurvey', 'lolaFormAnswers.fieldForm')
                ->whereHas('formSurvey', function ($q) use ($request) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id)
                    ->where("propinsi_id",$request->propinsi_id);
                })->whereHas('user.userRoleTim.role', function ($r) {
                    $r->where('id', "=", 4);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
            }
            if ($request->type != null && $request->propinsi_id != null) {
                $answer = Lola::with('formSurvey', 'lolaFormAnswers.fieldForm')
                ->whereHas('formSurvey', function ($q) use ($request) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id)
                    ->where('flag', $request->type)
                    ->where("propinsi_id",$request->propinsi_id);
                })->whereHas('user.userRoleTim.role', function ($r) {
                    $r->where('id', "=", 4);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
            }
            if ($request->propinsi_id != null && $request->kabupaten_id != null) {
                $answer = Lola::with('formSurvey', 'lolaFormAnswers.fieldForm')
                ->whereHas('formSurvey', function ($q) use ($request) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id)
                    ->where("propinsi_id",$request->propinsi_id)
                    ->where("kabupaten_id", $request->kabupaten_id);
                })->whereHas('user.userRoleTim.role', function ($r) {
                    $r->where('id', "=", 4);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
            }

            if ($request->type != null && $request->propinsi_id != null && $request->kabupaten_id != null) {
                $answer = Lola::with('formSurvey', 'lolaFormAnswers.fieldForm')
                ->whereHas('formSurvey', function ($q) use ($request) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id)
                    ->where('flag', $request->type)
                    ->where("propinsi_id",$request->propinsi_id)
                    ->where("kabupaten_id", $request->kabupaten_id);
                })->whereHas('user.userRoleTim.role', function ($r) {
                    $r->where('id', "=", 4);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
            }

            if ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null) {
                $answer = Lola::with('formSurvey', 'lolaFormAnswers.fieldForm')
                ->whereHas('formSurvey', function ($q) use ($request) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id)
                    ->where("propinsi_id","LIKE", "%$request->propinsi_id%")
                    ->where("kabupaten_id","LIKE", "%$request->kabupaten_id%")
                    ->where("kecamatan_id","LIKE", "%$request->kecamatan_id%");
                })->whereHas('user.userRoleTim.role', function ($r) {
                    $r->where('id', "=", 4);
                })
                ->orderBy('created_at', 'desc')
                ->where("nama_responden", "LIKE", "%$request->search%")
                ->paginate(10)->withQueryString();
            }
            if ($request->type != null && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null) {
                $answer = Lola::with('formSurvey', 'lolaFormAnswers.fieldForm')
                ->whereHas('formSurvey', function ($q) use ($request) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id)
                    ->where('flag', $request->type)
                    ->where("propinsi_id","LIKE", "%$request->propinsi_id%")
                    ->where("kabupaten_id","LIKE", "%$request->kabupaten_id%")
                    ->where("kecamatan_id","LIKE", "%$request->kecamatan_id%");
                })->whereHas('user.userRoleTim.role', function ($r) {
                    $r->where('id', "=", 4);
                })
                ->orderBy('created_at', 'desc')
                ->where("nama_responden", "LIKE", "%$request->search%")
                ->paginate(10)->withQueryString();
            }
            if ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null) {
                $answer = Lola::with('formSurvey', 'lolaFormAnswers.fieldForm')
                ->whereHas('formSurvey', function ($q) use ($request) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id)
                    ->where("propinsi_id","LIKE", "%$request->propinsi_id%")
                    ->where("kabupaten_id","LIKE", "%$request->kabupaten_id%")
                    ->where("kecamatan_id","LIKE", "%$request->kecamatan_id%")
                    ->where("kelurahan_id","LIKE", "%$request->kelurahan_id%");
                })->whereHas('user.userRoleTim.role', function ($r) {
                    $r->where('id', "=", 4);
                })
                ->orderBy('created_at', 'desc')
                ->where("nama_responden", "LIKE", "%$request->search%")
                ->paginate(10)->withQueryString();
            }
            if ($request->type != null && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null) {
                $answer = Lola::with('formSurvey', 'lolaFormAnswers.fieldForm')
                ->whereHas('formSurvey', function ($q) use ($request) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id)
                    ->where('flag', $request->type)
                    ->where("propinsi_id","LIKE", "%$request->propinsi_id%")
                    ->where("kabupaten_id","LIKE", "%$request->kabupaten_id%")
                    ->where("kecamatan_id","LIKE", "%$request->kecamatan_id%")
                    ->where("kelurahan_id","LIKE", "%$request->kelurahan_id%");
                })->whereHas('user.userRoleTim.role', function ($r) {
                    $r->where('id', "=", 4);
                })
                ->orderBy('created_at', 'desc')
                ->where("nama_responden", "LIKE", "%$request->search%")
                ->paginate(10)->withQueryString();
            }
            if ($request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null) {
                $answer = Lola::with('formSurvey', 'lolaFormAnswers.fieldForm')
                ->whereHas('formSurvey', function ($q) use ($request) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id)
                    ->where("propinsi_id","LIKE", "%$request->propinsi_id%")
                    ->where("kabupaten_id","LIKE", "%$request->kabupaten_id%")
                    ->where("kecamatan_id","LIKE", "%$request->kecamatan_id%")
                    ->where("kelurahan_id","LIKE", "%$request->kelurahan_id%");
                })->whereHas('user.userRoleTim.role', function ($r) {
                    $r->where('id', "=", 4);
                })
                ->orderBy('created_at', 'desc')
                ->where("nama_responden", "LIKE", "%$request->search%")
                ->paginate(10)->withQueryString();
            }
            if ($request->type != null && $request->propinsi_id != null && $request->kabupaten_id != null && $request->kecamatan_id != null && $request->kelurahan_id != null && $request->dapil != null) {
                $answer = Lola::with('formSurvey', 'lolaFormAnswers.fieldForm')
                ->whereHas('formSurvey', function ($q) use ($request) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id)
                    ->where('flag', $request->type)
                    ->where("propinsi_id","LIKE", "%$request->propinsi_id%")
                    ->where("kabupaten_id","LIKE", "%$request->kabupaten_id%")
                    ->where("kecamatan_id","LIKE", "%$request->kecamatan_id%")
                    ->where("kelurahan_id","LIKE", "%$request->kelurahan_id%")
                    ->where("dapil","LIKE", "%$request->dapil%");
                })->whereHas('user.userRoleTim.role', function ($r) {
                    $r->where('id', "=", 4);
                })
                ->orderBy('created_at', 'desc')
                ->where("nama_responden", "LIKE", "%$request->search%")
                ->paginate(10)->withQueryString();
            }
            if ($request->search != null) {
                $answer = Lola::with('formSurvey', 'lolaFormAnswers.fieldForm')
                ->whereHas('formSurvey', function ($q) use ($request) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id);
                })->whereHas('user.userRoleTim.role', function ($r) {
                    $r->where('id', "=", 4);
                })
                ->orderBy('created_at', 'desc')
                ->where("nama_responden", "LIKE", "%$request->search%")
                ->paginate(10)->withQueryString();
            }
            if ($request->type == null && $request->search == null && $request->propinsi_id == null && $request->kabupaten_id == null && $request->kecamatan_id == null && $request->kelurahan_id == null && $request->dapil == null) {
                $answer = Lola::with('formSurvey', 'lolaFormAnswers.fieldForm')
                ->whereHas('formSurvey', function ($q) use ($request) {
                    $q->where('tim_relawan_id', Auth::user()->current_team_id);
                })->whereHas('user.userRoleTim.role', function ($r) {
                    $r->where('id', "=", 4);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
            }
            return response()->json([
                'message' => 'List Answer Relawan.',
                'data' => $answer
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, Answer not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllDapilBySurvey()
    {
        $collctionSurvey = FormSurvey::select('dapil')->distinct()->get()->toArray();
        return response()->json($collctionSurvey);
    }
}
