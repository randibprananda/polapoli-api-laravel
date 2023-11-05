<?php

namespace App\Http\Controllers\Api\Paslon;

use App\Http\Controllers\Controller;
use App\Models\Paslon;
use App\Models\SosmedWebKemenangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SosmedWebKemenanganController extends Controller
{
    public function addorUpdateSosmed(Request $request)
    {
        // manajemen_paslon
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        $validator = Validator::make($request->all(), [
            'instagram' => 'nullable|string',
            'url_instagram' => 'nullable|url',
            'facebook' => 'nullable|string',
            'url_facebook' => 'nullable|url',
            'youtube' => 'nullable|string',
            'url_youtube' => 'nullable|url',
            'twitter' => 'nullable|string',
            'url_twitter' => 'nullable|url',
            'tiktok' => 'nullable|string',
            'url_tiktok' => 'nullable|url',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $isDataSosmedExist = Paslon::with('sosmedWebKemenangan')
                ->where([['is_usung', 1], ['tim_relawan_id', Auth::user()
                    ->current_team_id]])
                ->first();

            $instagram = $request->instagram;
            $url_instagram = $request->url_instagram;
            $facebook = $request->facebook;
            $url_facebook = $request->url_facebook;
            $youtube = $request->youtube;
            $url_youtube = $request->url_youtube;
            $twitter = $request->twitter;
            $url_twitter = $request->url_twitter;
            $tiktok = $request->tiktok;
            $url_tiktok = $request->url_tiktok;
            if ($isDataSosmedExist->sosmedWebKemenangan == null) {

                $paslon = Paslon::with('tentangPaslon')->where([['is_usung', 1], ['tim_relawan_id', Auth::user()->current_team_id]])->first();
                $paslonId = $paslon->id;
                $sosmedWebKemenangan = SosmedWebKemenangan::create(
                    [
                        'paslon_id' => $paslonId,
                        'instagram' => $instagram,
                        'url_instagram' => $url_instagram,
                        'facebook' => $facebook,
                        'url_facebook' => $url_facebook,
                        'youtube' => $youtube,
                        'url_youtube' => $url_youtube,
                        'twitter' => $twitter,
                        'url_twitter' => $url_twitter,
                        'tiktok' => $tiktok,
                        'url_tiktok' => $url_tiktok,
                    ]
                );
            } else if ($isDataSosmedExist->sosmedWebKemenangan != null) {
                $sosmedWebKemenangan = SosmedWebKemenangan::find($isDataSosmedExist->sosmedWebKemenangan->id);

                $sosmedWebKemenangan->forceFill(
                    [
                        'instagram' => $instagram,
                        'url_instagram' => $url_instagram,
                        'facebook' => $facebook,
                        'url_facebook' => $url_facebook,
                        'youtube' => $youtube,
                        'url_youtube' => $url_youtube,
                        'twitter' => $twitter,
                        'url_twitter' => $url_twitter,
                        'tiktok' => $tiktok,
                        'url_tiktok' => $url_tiktok,
                    ]
                )->save();
            }
            return response()->json([
                'message' => 'Sosmed web kemenangan has been created',
                'data' => $sosmedWebKemenangan
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, sosmed web kemenangan cannot be created.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSosmed()
    {
        // if (!Auth::user()->customHasPermissionTo(1)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $timrelawan = SosmedWebKemenangan::with('paslon.timRelawan')->whereHas('paslon', function ($q) {
            $q->where('tim_relawan_id', Auth::user()->current_team_id);
        })->first();
        return response()->json([
            'message' => 'Sosmed web kemenangan detail',
            'data' => $timrelawan
        ], Response::HTTP_OK);
    }
}