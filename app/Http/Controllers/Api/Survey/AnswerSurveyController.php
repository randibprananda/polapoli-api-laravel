<?php

namespace App\Http\Controllers\Api\Survey;

use App\Http\Controllers\Controller;
use App\Models\FieldForm;
use App\Models\FormAnswer;
use App\Models\LoLa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class AnswerSurveyController extends Controller
{
    public function store(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(12)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'form_survey_id' => 'required|numeric',
            'longitude_latitude' => 'required|string',
            'field_form_id.*' => 'nullable|numeric',
            'nama_responden' => 'required|string',
            'alamat' => 'required|string',
            'jawaban.*' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            DB::beginTransaction();
            $lola = LoLa::create([
                'user_id' => Auth::user()->id,
                'form_survey_id' => $request['form_survey_id'],
                'longitude_latitude' => $request['longitude_latitude'],
                'nama_responden' => $request['nama_responden'],
                'alamat' => $request['alamat'],
            ]);
            for ($i = 0; $i < count($request->field_form_id); $i++) {
                $findTipeField = FieldForm::find($request['field_form_id'][$i]);
                $jawaban = $request['jawaban'][$i];
                if ($findTipeField->tipe == 'GAMBAR') {
                    if ($request->hasFile('jawaban')) {
                        foreach ($request->file('jawaban') as $file) {
                            $filegambar = 'gambar_survey_' . Str::uuid() . '.' . $file->extension();
                            $file->move('storage/gambar_survey/', $filegambar);
                            $jawaban =  env('APP_URL') . '/storage/gambar_survey/' . $filegambar;
                        }
                    }
                }
                $answerSurvey[$i] = FormAnswer::create(
                    [
                        'form_survey_id' => $request['form_survey_id'],
                        'field_form_id' => $request['field_form_id'][$i],
                        'jawaban' => $jawaban,
                        'lo_la_id' => $lola->id,
                    ]
                );
            }
            DB::commit();
            return response()->json([
                'message' => 'Survey has been submited.',
                'data' => $answerSurvey
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Sorry, survey cannot be submited. check your input data',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lo_la_id' => 'required|string',
            'longitude_latitude' => 'required|string',
            'nama_responden' => 'required|string',
            'alamat' => 'required|string',
            'field_form_id.*' => 'nullable|numeric',
            'jawaban.*' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            DB::beginTransaction();
            FormAnswer::where('lo_la_id', $request->lo_la_id)->delete();

            $lola = LoLa::find($request->lo_la_id)->forceFill([
                'user_id' => Auth::user()->id,
                'longitude_latitude' => $request['longitude_latitude'],
                'nama_responden' => $request['nama_responden'],
                'alamat' => $request['alamat']
            ])->save();
            for ($i = 0; $i < count($request->field_form_id); $i++) {
                $findTipeField = FieldForm::find($request['field_form_id'][$i]);
                $jawaban = $request['jawaban'][$i];
                if ($findTipeField->tipe == 'GAMBAR') {
                    if ($request->hasFile('jawaban')) {
                        foreach ($request->file('jawaban') as $file) {
                            $filegambar = 'gambar_survey_' . Str::uuid() . '.' . $file->extension();
                            $file->move('storage/gambar_survey/', $filegambar);
                            $jawaban =  env('APP_URL') . '/storage/gambar_survey/' . $filegambar;
                        }
                    }
                }
                $answerSurvey[$i] = FormAnswer::create(
                    [
                        'form_survey_id' => $request['form_survey_id'],
                        'field_form_id' => $request['field_form_id'][$i],
                        'jawaban' => $jawaban,
                        'lo_la_id' => $request->lo_la_id,
                    ]
                );
            }
            DB::commit();
            return response()->json([
                'message' => 'Survey has been updated.',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Sorry, survey cannot be submited. check your input data',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function show(Request $request)
    {
        try {
            $answer = Lola::with('lolaFormAnswers.fieldForm')->find($request->lo_la_id);
            return response()->json([
                'message' => 'Detail Answer.',
                'data' => $answer
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, Detail Answer not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request)
    {
        try {
            Lola::find($request->lo_la_id)->delete();
            return response()->json([
                'message' => 'Answer has been deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, Answer cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
