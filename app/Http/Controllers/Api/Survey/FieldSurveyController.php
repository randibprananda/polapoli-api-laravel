<?php

namespace App\Http\Controllers\Api\Survey;

use App\Http\Controllers\Controller;
use App\Models\FieldForm;
use App\Models\FormSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class FieldSurveyController extends Controller
{
    public function index()
    {
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $fieldForms = FieldForm::all();
        if ($fieldForms != null) {
            return response()->json([
                'message' => 'List of Field Form',
                'data' => $fieldForms,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No Field available',
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
            'form_survey_id' => 'required|numeric',
            'tipe.*' => 'required|string|in:TEXT,PILIHAN GANDA,CHECKLIST,GAMBAR',
            'label_inputan.*' => 'required|string|max:255',
            'option.*' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            $survey = FormSurvey::find($request->form_survey_id);
            if ($survey->status == 'draft') {
                $dataFieldForm = [];
                DB::beginTransaction();
                $fieldSurvey =  FieldForm::where('form_survey_id', $request->form_survey_id);
                if ($fieldSurvey->count() > 0) {
                    $fieldSurvey->delete();
                }
                for ($i = 0; $i < count($request->all()) - 1; $i++) {
                    $dataFieldForm[$i] = FieldForm::create(
                        [
                            'form_survey_id' => $request['form_survey_id'],
                            'tipe' => $request[$i]['tipe'],
                            'label_inputan' => $request[$i]['label_inputan'],
                            'option' => json_encode($request[$i]['option'], true),
                        ]
                    );
                }

                DB::commit();
                return response()->json([
                    'message' => 'Field of survey has been saved',
                    'data' => $dataFieldForm
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Survey cannot be updated again because it has been published or closed',
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Sorry, field of survey cannot be saved',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detail(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'form_survey_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            if ($detailFieldsurvey = FieldForm::with('formSurvey:id,judul_survey,status')->where('form_survey_id', $request->form_survey_id)->get()) {
                return response()->json([
                    'message' => 'Detail Field Survey.',
                    'data' => $detailFieldsurvey
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, Field survey not found.',
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
}