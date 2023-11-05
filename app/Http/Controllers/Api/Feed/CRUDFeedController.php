<?php

namespace App\Http\Controllers\Api\Feed;

use App\Http\Controllers\Controller;
use App\Models\Feed;
use App\Models\ShareFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class CRUDFeedController extends Controller
{

    public function getAll(Request $request)
    {
        if ($request->search != null) {
            $feeds = Feed::with('timRelawan')
                    ->leftjoin('share_feeds as S', 'S.id_feed', 'feeds.id')
                    ->select('feeds.*', 'S.jml')
                    ->where('tim_relawan_id', Auth::user()->current_team_id)
                    ->where("judul_feed", "LIKE", "%".$request->search."%")
                    ->orderBy('id', 'desc')
                    ->paginate(10)->withQueryString();
        } else {
            $feeds = Feed::with('timRelawan')
                    ->leftjoin('share_feeds as S', 'S.id_feed', 'feeds.id')
                    ->select('feeds.*', 'S.jml')
                    ->where('tim_relawan_id', Auth::user()->current_team_id)
                    ->orderBy('id', 'desc')
                    ->paginate(10)->withQueryString();
        }

        if ($feeds != null) {
            return response()->json([
                'message' => 'List of feed',
                'data' => $feeds,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No feeds available',
            ], Response::HTTP_OK);
        }
    }

    public function addFeed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_feed' => 'required|string',
            'isi' => 'required|string',
            'foto_feed' => 'required|image|mimes:png,jpg,jpeg'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

            $findTeamRelawanId = Auth::user()->current_team_id;
            if ($findTeamRelawanId  && $request->hasFile('foto_feed')) {

                $filename = 'foto_feed-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->foto_feed->extension();
                $request->file('foto_feed')->move('storage/foto-feed/', $filename);
                $feed = Feed::create([
                    'judul_feed' => $request->judul_feed,
                    'isi' => $request->isi,
                    'foto_feed' => env('APP_URL') . '/storage/foto-feed/' . $filename,
                    'tim_relawan_id' => $findTeamRelawanId,
                ]);


                return response()->json([
                    'message' => 'Feed has been created',
                    'data' => $feed
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Sorry, feed cannot be created.',
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, feed cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detailFeed($id)
    {
        // if (!Auth::user()->customHasPermissionTo(15)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $feed = Feed::with('timRelawan')->where('tim_relawan_id', Auth::user()->current_team_id)->find($id);
        return response()->json([
            'message' => 'Feed detail',
            'data' => $feed
        ], Response::HTTP_OK);
    }

    public function updateFeed(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul_feed' => 'nullable|string',
            'isi' => 'nullable|string',
            'foto_feed' => 'nullable|image|mimes:png,jpg,jpeg'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $feed = Feed::find($id);

            $feed->forceFill([
                'judul_feed' => $request->judul_feed,
                'isi' => $request->isi,
            ])->save();

            if ($request->hasFile('foto_feed')) {
                if($feed->foto_feed != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $feed->foto_feed));
                }
                $filename = 'foto_feed-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->foto_feed->extension();
                $request->file('foto_feed')->move('storage/foto-feed/', $filename);
                $feed->forceFill([
                    'foto_feed' => env('APP_URL') . '/storage/foto-feed/' . $filename,
                ])->save();
            }

            return response()->json([
                'message' => 'Feed has ben updated.',
                'data' => $feed
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, feed cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteFeed($id)
    {
        try {
            $feed = Feed::where('id', $id)->select('id')->get();
            ShareFeed::where('id_feed', $feed[0]->id)->delete();
            $del_feed = Feed::find($id);
            if($del_feed->foto_feed != null)
            {
                Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $del_feed->foto_feed));
            }
            $del_feed->delete();
            return response()->json([
                'message' => 'Feed has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, feed cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function shareFeed(Request $request, $id)
    {
        try {
            $jml = Feed::where('id',$id)->value('jumlah');
            $tambah = $jml + $request->jumlah;
            // return $tambah;
            $feed = Feed::where('id', $id)->update([
                'jumlah' => $tambah,
            ]);
            return response()->json([
                'message' => 'Feed has ben share.'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Sorry, feed cannot be share.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function ShareFeeds($id)
    {
        $feed = Feed::find($id);
        return response()->json([
            'message' => 'Feed detail',
            'data' => $feed
        ]);
    }

    public function downloadImageFeed($id)
    {
        try {
            $feed = Feed::find($id);
            return Storage::disk('public')->download(str_replace(env('APP_URL').'/storage', '', $feed->foto_feed));
        } catch(\Exception $e){
            return response()->json([
                'message' => 'Sorry, cant download this image',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
