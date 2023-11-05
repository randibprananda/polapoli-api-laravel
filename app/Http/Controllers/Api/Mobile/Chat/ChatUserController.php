<?php

namespace App\Http\Controllers\Api\Mobile\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\DaftarAnggota;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChatUserController extends Controller
{
    public function index()
    {
        try {

            $getUserKoor = User::with('detailUser.tingkatKoordinator',
            'detailUser.daftarAnggota.user.userRoleTim.role')
                ->whereHas("timRelawans", function ($q) {
                    $q->where("tim_relawan_id", Auth::user()->current_team_id);
                })
                ->find(Auth::user()->id);
            if (Auth::user()->hasRoleTim(4,Auth::user()->current_team_id) || hasRoleTim(5,Auth::user()->current_team_id)) {
                $listUser = DaftarAnggota::where('user_id', Auth::user()->id)
                    ->whereHas("detailUser.user.timRelawans", function ($q) {
                        $q->where("tim_relawan_id", Auth::user()->current_team_id);
                    })->with('detailUser.user.userRoleTim.role', 'detailUser.user.chats.conversation.chats')->get();
            } else if (Auth::user()->hasRoleTim(3,Auth::user()->current_team_id) && $getUserKoor->detailUser->tingkatKoordinator->nama_tingkat_koordinator == 'RT/RW') {
                $kelurahan = User::
                whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],
                    ['tim_relawan_id', '=', Auth::user()->current_team_id]]);
                })->whereHas("detailUser.tingkatKoordinator", function ($p) {
                    $p->whereIn("nama_tingkat_koordinator", ['Kelurahan']);
                })->with('userRoleTim.role', 'chats.conversation.chats')->get();

                $listUser = User::with('detailUser.tingkatKoordinator',
                'detailUser.daftarAnggota.user.userRoleTim.role',
                'detailUser.daftarAnggota.user.chats.conversation.chats')
                    ->whereHas("timRelawans", function ($s) {
                        $s->where("tim_relawan_id", Auth::user()->current_team_id);
                    })
                    ->find(Auth::user()->id);
                $listUser =  $listUser->detailUser->daftarAnggota;
                return response()->json([
                    'message' => 'List your user chat.',
                    'atasan' => $kelurahan,
                    'bawahan' => $listUser
                ], Response::HTTP_OK);
            } else if (Auth::user()->hasRoleTim(3,Auth::user()->current_team_id) && $getUserKoor->detailUser->tingkatKoordinator->nama_tingkat_koordinator == 'Kelurahan') {
                $listUser = User::whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],
                    ['tim_relawan_id', '=', Auth::user()->current_team_id]]);
                })->whereHas("detailUser.tingkatKoordinator", function ($p) {
                    $p->whereIn("nama_tingkat_koordinator", ['RT/RW', 'Kecamatan']);
                })->with('userRoleTim.role', 'chats.conversation.chats')->get();
            } else if (Auth::user()->hasRoleTim(3,Auth::user()->current_team_id) && $getUserKoor->detailUser->tingkatKoordinator->nama_tingkat_koordinator == 'Kecamatan') {
                $listUser = User::whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],
                    ['tim_relawan_id', '=', Auth::user()->current_team_id]]);
                })->whereHas("detailUser.tingkatKoordinator", function ($p) {
                    $p->whereIn("nama_tingkat_koordinator", ['Kelurahan', 'Kota/Kab']);
                })->with('userRoleTim.role', 'chats.conversation.chats')->get();
            } else if (Auth::user()->hasRoleTim(3,Auth::user()->current_team_id) && $getUserKoor->detailUser->tingkatKoordinator->nama_tingkat_koordinator == 'Kota/Kab') {
                $listUser = User::whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],
                    ['tim_relawan_id', '=', Auth::user()->current_team_id]]);
                })->whereHas("detailUser.tingkatKoordinator", function ($p) {
                    $p->whereIn("nama_tingkat_koordinator", ['Kecamatan', 'Provinsi']);
                })->with('userRoleTim.role', 'chats.conversation.chats')->get();
            } else if (Auth::user()->hasRoleTim(3,Auth::user()->current_team_id) && $getUserKoor->detailUser->tingkatKoordinator->nama_tingkat_koordinator == 'Provinsi') {
                $listUser = User::whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],
                    ['tim_relawan_id', '=', Auth::user()->current_team_id]]);
                })->whereHas("detailUser.tingkatKoordinator", function ($p) {
                    $p->whereIn("nama_tingkat_koordinator", ['Kota/Kab']);
                })->with('userRoleTim.role', 'chats.conversation.chats')->get();
            } else {
                return response()->json([
                    'message' => 'You are not authorized to access this resource.'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return response()->json([
                'message' => 'List your user chat.',
                'data' => $listUser
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'data' => 'You are not authorized to access this resource.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $current_user = User::with(
            array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                }, 'detailUser')
                )->find($id);

        return response()->json([
            'message' => 'Detail your user chat.',
            'data' => $current_user
        ], Response::HTTP_OK);
    }
}
