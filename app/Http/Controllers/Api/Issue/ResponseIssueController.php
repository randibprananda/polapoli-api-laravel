<?php

namespace App\Http\Controllers\Api\Issue;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\ResponseofIssue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ResponseIssueController extends Controller
{
    public function updateResponseIssue(Request $request, $id)
    {
        // manajemen_isu
        // if (!Auth::user()->customHasPermissionTo(13)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'tanggapan_isu' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $responseIssue = Issue::find($id);
        try {
            if ($responseIssue) {
                $responseIssue->forceFill([
                    'tanggapan_isu' =>  $request->tanggapan_isu,
                    'ditanggapi_pada' => Carbon::now()
                ])->save();

                return response()->json([
                    'message' => 'Response of issue has been updated',
                    'data' => $responseIssue
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Data not found.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, response of issue cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function abaikanResponseIssue($id)
    {
        // if (!Auth::user()->customHasPermissionTo(13)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }



        $responseIssue = Issue::find($id);
        $is_abaikan = $responseIssue->is_abaikan;

        if ($responseIssue->is_abaikan == 1) {
            $is_abaikan = 0;
        } else {
            $is_abaikan = 1;
        }
        try {
            if ($responseIssue) {
                $responseIssue->forceFill([
                    'is_abaikan' =>  $is_abaikan,
                    'ditanggapi_pada' => Carbon::now()
                ])->save();

                return response()->json([
                    'message' => 'Isu telah diabaikan',
                    'data' => $responseIssue
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, response of issue cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteResponseIssue($id)
    {
        # code...
    }
}