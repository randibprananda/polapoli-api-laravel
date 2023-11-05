<?php

namespace App\Http\Controllers\Api\Feed;

use App\Http\Controllers\Controller;
use App\Models\Feed;
use App\Models\ShareFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ShareFeedController extends Controller
{
    public function getAll()
    {
        $data = ShareFeed::with('user','feed')->orderBy('id', 'DESC')->paginate(10)->withQueryString();
        if ($data != null) {
            return response()->json([
                'message' => 'List of feed',
                'data' => $data,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No feeds available',
            ], Response::HTTP_OK);
        }
    }

    public function getByidFeed(Request $request)
    {
        $data = ShareFeed::with('user','feed')->where('id_feed', $request->id_feed)->orderBy('id', 'DESC')->paginate(10)->withQueryString();
        if ($data != null) {
            return response()->json([
                'message' => 'List of feed',
                'data' => $data,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No feeds available',
            ], Response::HTTP_OK);
        }
    }

    public function create(Request $request)
    {
        try {
            $jml = ShareFeed::where('id_feed', $request->id_feed)->value('jml');
            $tambah = $jml + 1;
            $getByID = ShareFeed::where('id_feed', $request->id_feed)->first();
            if ($getByID) {
                $feed = ShareFeed::where('id_feed', $request->id_feed)
                      ->update([
                            'jml' => $tambah,
                        ]);
            } else {
                $feed = ShareFeed::create([
                    'id_user' => Auth::user()->id,
                    'id_feed' => $request->id_feed,
                    'jml' => $tambah,
                ]);
            }
            return response()->json([
                'message' => 'Feed has been created',
                'data' => $feed
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, feed cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
