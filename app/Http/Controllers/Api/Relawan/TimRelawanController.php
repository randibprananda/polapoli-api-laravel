<?php

namespace App\Http\Controllers\Api\Relawan;

use App\Http\Controllers\Controller;
use App\Models\DetailTimRelawan;
use App\Models\RoleTimPermission;
use App\Models\TimRelawan;
use App\Models\TimRole;
use App\Models\User;
use App\Models\UserRoleTim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class TimRelawanController extends Controller
{
    public function listTimRelawan()
    {
        // if (!Auth::user()->customHasPermissionTo(7)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $timrelawans = User::with('timRelawans')->find(Auth::user()->id);
        if ($timrelawans != null) {
            return response()->json([
                'message' => 'List of tim relawan',
                'data' => $timrelawans,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No timrelawans available',
            ], Response::HTTP_OK);
        }
    }

    public function addTimRelawan(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(7)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }

        $validator = Validator::make($request->all(), [
            'nama_tim_relawan' => 'required|string|between:2,100',
            'photo_tim_relawan' => 'required|image|mimes:png,jpg,jpeg',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $nama_tim_relawan = $request->nama_tim_relawan;
            $pm = Auth::user()->id;
            DB::beginTransaction();
            if ($request->hasFile('photo_tim_relawan')) {
                DB::beginTransaction();
                $filename = 'tim_relawan_' . uniqid() . strtolower(Str::random(5)) . '.' . $request->photo_tim_relawan->extension();
                $request->file('photo_tim_relawan')->move('storage/tim-relawan-image/', $filename);
                $timrelawan = TimRelawan::create(
                    [
                        'nama_tim_relawan' => $nama_tim_relawan,
                        'photo_tim_relawan' => env('APP_URL') . '/storage/tim-relawan-image/' . $filename,
                    ]
                );
                DetailTimRelawan::create([
                    'tim_relawan_id' => $timrelawan->id,
                    'pm' => $pm,
                ]);
                $timrelawan->users()->attach($pm);
                DB::commit();
            }

            $user = User::find($pm);
            $user->forceFill(
                [
                    'current_team_id' => $timrelawan->id,
                ]
            )->save();

            // Relation Tim Role
            TimRole::create([
                'tim_relawan_id' => $timrelawan->id,
                'role_id' => 1,
            ]);
            TimRole::create([
                'tim_relawan_id' => $timrelawan->id,
                'role_id' => 2,
            ]);

            TimRole::create([
                'tim_relawan_id' => $timrelawan->id,
                'role_id' => 3,
            ]);

            TimRole::create([
                'tim_relawan_id' => $timrelawan->id,
                'role_id' => 4,
            ]);

            TimRole::create([
                'tim_relawan_id' => $timrelawan->id,
                'role_id' => 5,
            ]);

            // Assign Permission
            for ($i = 1; $i <= 74; $i++) {
                RoleTimPermission::create([
                    'role_id' => 1,
                    'tim_relawan_id' => $timrelawan->id,
                    'permission_id' => $i,
                ]);
            }
            for ($i = 1; $i <= 74; $i++) {
                if (
                    $i != 1 && $i != 2 && $i != 3 && $i != 12
                    && $i != 54 && $i != 55 && $i != 56 && $i != 57
                    && $i != 71 && $i != 72 && $i != 73 && $i != 74
                ) {
                    RoleTimPermission::create([
                        'role_id' => 2,
                        'tim_relawan_id' => $timrelawan->id,
                        'permission_id' => $i,
                    ]);
                }
            }
            for ($i = 1; $i <= 74; $i++) {
                if (
                    $i == 13 || $i == 15 || $i == 16 || $i == 17
                    || $i == 18 || $i == 23 || $i == 28 || $i == 21
                    || $i == 22 || $i == 25 || $i == 26 || $i == 27
                    || $i == 29 || $i == 30 || $i == 31 || $i == 32
                    || $i == 33 || $i == 34 || $i == 35 || $i == 36
                    || $i == 38 || $i == 39 || $i == 47 || $i == 48
                    || $i == 49 || $i == 51 || $i == 53 || $i == 68
                    || $i == 69 || $i == 70
                ) {
                    RoleTimPermission::create([
                        'role_id' => 3,
                        'tim_relawan_id' => $timrelawan->id,
                        'permission_id' => $i,
                    ]);
                }
            }
            for ($i = 1; $i <= 74; $i++) {
                if (
                    $i == 13 || $i == 29 || $i == 30 || $i == 31
                    || $i == 32 || $i == 38 || $i == 39 || $i == 48
                    || $i == 51 || $i == 53 || $i == 68 || $i == 69
                    || $i == 70

                ) {
                    RoleTimPermission::create([
                        'role_id' => 4,
                        'tim_relawan_id' => $timrelawan->id,
                        'permission_id' => $i,
                    ]);
                }
            }
            for ($i = 1; $i <= 74; $i++) {
                if (
                    $i == 13 || $i == 31
                    || $i == 32 || $i == 38 || $i == 39 || $i == 50
                    || $i == 51 || $i == 52 || $i == 53 || $i == 68 || $i == 69
                    || $i == 70

                ) {
                    RoleTimPermission::create([
                        'role_id' => 5,
                        'tim_relawan_id' => $timrelawan->id,
                        'permission_id' => $i,
                    ]);
                }
            }

            // Relation User Role Tim Relawan
            UserRoleTim::create([
                'user_id' => Auth::user()->id,
                'tim_relawan_id' => $timrelawan->id,
                'role_id' => 1,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Tim relawan has been created',
                'data' => $timrelawan
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, tim relawan cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showTimRelawan($id)
    {
        // if (!Auth::user()->customHasPermissionTo(7)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $timrelawan = TimRelawan::find($id);
        return response()->json([
            'message' => 'Tim relawan detail',
            'data' => $timrelawan
        ], Response::HTTP_OK);
    }

    // public function updateTimRelawan(Request $request, $id)
    // {
    //     // if (!Auth::user()->customHasPermissionTo(7)) {
    //     //     return response()->json([
    //     //         'message' => 'FORBIDDEN',
    //     //     ], Response::HTTP_FORBIDDEN);
    //     // }

    //     $validator = Validator::make($request->all(), [
    //         'nama_tim_relawan' => 'required|string|between:2,100',
    //         'photo_tim_relawan' => 'nullable|image|mimes:png,jpg,jpeg',
    //         'tanggal_pemilihan' => 'required|string',
    //         'link_video' => 'nullable|url',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     try {
    //         $timrelawan = TimRelawan::find($id);
    //         $nama_tim_relawan = $request->nama_tim_relawan;
    //         $tanggal_pemilihan = $request->tanggal_pemilihan;
    //         $jenis_pencalonan = $request->jenis_pencalonan;
    //         $link_video = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i", "<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>", $request->link_video);

    //         if ($request->hasFile('photo_tim_relawan')) {
    //             $filename = 'tim_relawan_image-' . uniqid() . strtolower(Str::random(5)) . '.' . $request->photo_tim_relawan->extension();
    //             $request->file('photo_tim_relawan')->move('storage/tim-relawan-image/', $filename);
    //             $timrelawan->forceFill(
    //                 [
    //                     'photo_tim_relawan' => env('APP_URL') . '/storage/tim-relawan-image/' . $filename,
    //                 ]
    //             )->save();
    //         }

    //         if ($request->link_video != null) {
    //             $timrelawan->forceFill(
    //                 [
    //                     'link_video' => $request->link_video,
    //                 ]
    //             )->save();
    //         }
    //         $timrelawan->forceFill(
    //             [
    //                 'nama_tim_relawan' => $nama_tim_relawan,
    //                 'tanggal_pemilihan' => $tanggal_pemilihan,
    //                 'jenis_pencalonan' => $jenis_pencalonan,
    //             ]
    //         )->save();

    //         return response()->json([
    //             'message' => 'Tim relawan has ben updated.',
    //             'data' => $timrelawan,
    //         ], Response::HTTP_OK);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Sorry, tim relawan cannot be updated.',
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    public function deleteTimRelawan($id)
    {
        try {

            DB::beginTransaction();
            User::find(Auth::user()->id)->forceFill(
                [
                    'current_team_id' => null,
                ]
            )->save();
            $timRelawan = TimRelawan::find($id);
            $timRelawan->users()->detach();
            $timRelawan->roleTimPermission()->detach();
            $timRelawan->delete();

            DB::commit();
            return response()->json([
                'message' => 'Tim relawan has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, tim relawan cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCurrentTimRelawan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_tim_relawan' => 'required|string|between:2,100',
            'photo_tim_relawan' => 'nullable|image|mimes:png,jpg,jpeg',
            'tanggal_pemilihan' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            DB::beginTransaction();
            $timrelawan = TimRelawan::find(Auth::user()->current_team_id);
            $nama_tim_relawan = $request->nama_tim_relawan;
            $tanggal_pemilihan = $request->tanggal_pemilihan;
            $jenis_pencalonan = $request->jenis_pencalonan;
            if($request->link_video == 'null'){
                $link_video = null;
            }else{
                $link_video = $request->link_video;
            }

            if ($request->hasFile('photo_tim_relawan')) {
                if($timrelawan->photo_tim_relawan != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $timrelawan->photo_tim_relawan));
                }
                $filename = 'tim_relawan_image-' . uniqid() . strtolower(Str::random(5)) . '.' . $request->photo_tim_relawan->extension();
                $request->file('photo_tim_relawan')->move('storage/tim-relawan-image/', $filename);
                $timrelawan->forceFill(
                    [
                        'photo_tim_relawan' => env('APP_URL') . '/storage/tim-relawan-image/' . $filename,
                    ]
                )->save();
            }
            // if ($request->link_video != null) {
            //     $timrelawan->forceFill(
            //         [
            //             'link_video' => $request->link_video,
            //         ]
            //     )->save();
            // }

            $timrelawan->update([
                'nama_tim_relawan' => $nama_tim_relawan,
                'tanggal_pemilihan' => $tanggal_pemilihan,
                'jenis_pencalonan' => $jenis_pencalonan,
                'link_video' => $link_video
            ]);

            $permission = RoleTimPermission::where('tim_relawan_id', Auth::user()->current_team_id)->first();
            DB::commit();
            return response()->json([
                'message' => 'Tim relawan has ben updated.',
                'data' => $timrelawan,
                'permission' => $permission
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, tim relawan cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
