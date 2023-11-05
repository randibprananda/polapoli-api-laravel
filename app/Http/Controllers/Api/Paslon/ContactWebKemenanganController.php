<?php

namespace App\Http\Controllers\Api\Paslon;

use App\Http\Controllers\Controller;
use App\Models\ContactWebKemenangan;
use App\Models\Paslon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class ContactWebKemenanganController extends Controller
{
    public function addorUpdateContact(Request $request)
    {
        // manajemen_paslon
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        $validator = Validator::make($request->all(), [
            'alamat' => 'nullable|string',
            'email' => 'nullable|string',
            'telepon' => 'nullable|string',
            'whatsapp' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $isDataContactExist = Paslon::with('contactWebKemenangan')
                ->where([['is_usung', 1], ['tim_relawan_id', Auth::user()
                    ->current_team_id]])
                ->first();

            $alamat = $request->alamat;
            $email = $request->email;
            $telepon = $request->telepon;
            $whatsapp = $request->whatsapp;
            if ($isDataContactExist->contactWebKemenangan == null) {

                $paslon = Paslon::with('tentangPaslon')->where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();
                $paslonId = $paslon->id;
                $contactwebkemenangan = ContactWebKemenangan::create(
                    [
                        'paslon_id' => $paslonId,
                        'alamat' => $alamat,
                        'email' => $email,
                        'telepon' => $telepon,
                        'whatsapp' => $whatsapp
                    ]
                );
            } else if ($isDataContactExist->contactWebKemenangan != null) {
                $contactwebkemenangan = ContactWebKemenangan::find($isDataContactExist->contactWebKemenangan->id);

                $contactwebkemenangan->forceFill(
                    [
                        'alamat' => $alamat,
                        'email' => $email,
                        'telepon' => $telepon,
                        'whatsapp' => $whatsapp
                    ]
                )->save();
            }
            return response()->json([
                'message' => 'Contact web kemenangan has been created',
                'data' => $contactwebkemenangan
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, contact web kemenangan cannot be created.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getContact()
    {
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $timrelawan = ContactWebKemenangan::with('paslon.timRelawan')->whereHas('paslon', function ($q) {
            $q->where('tim_relawan_id', Auth::user()->current_team_id);
        })->first();
        return response()->json([
            'message' => 'Contact web kemenangan detail',
            'data' => $timrelawan
        ], Response::HTTP_OK);
    }
}