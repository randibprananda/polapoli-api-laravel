<?php

namespace App\Http\Controllers\Api\Mobile\Chat;

use App\Http\Controllers\Controller;
use App\Models\DaftarAnggota;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ListChatUserController extends Controller
{
    public function index()
    {
        try {

            $getUserKoor = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('role');
                }, 'detailUser.tingkatKoordinator', 'detailUser.daftarAnggota.user.userRoleTim.role')
            )
                ->whereHas("timRelawans", function ($q) {
                    $q->where("tim_relawan_id", Auth::user()->current_team_id);
                })
                ->find(Auth::user()->id);
            if (Auth::user()->hasRoleTim(4,Auth::user()->current_team_id)
            || Auth::user()->hasRoleTim(5,Auth::user()->current_team_id)) {
                $listUser = DaftarAnggota::where('user_id', Auth::user()->id)
                    ->whereHas("detailUser.user.timRelawans", function ($q) {
                        $q->where("tim_relawan_id", Auth::user()->current_team_id);
                    })->with(
                        array('detailUser.user.userRoleTim' => function($r)
                        {
                            $r->where("tim_relawan_id", '=',
                            Auth::user()->current_team_id)
                            ->with('role');
                        },'detailUser.user.chatsV2One',
                        'detailUser.user.chatsV2Two')
                    )
                    ->get();
            } else if (Auth::user()->hasRoleTim(3,Auth::user()->current_team_id)
            && $getUserKoor->detailUser->tingkatKoordinator->nama_tingkat_koordinator == 'RT/RW') {
                $kelurahan = User::whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],
                    ['tim_relawan_id', '=', Auth::user()->current_team_id]]);
                })->whereHas("detailUser.tingkatKoordinator", function ($p) {
                    $p->whereIn("nama_tingkat_koordinator", ['Kelurahan']);
                })->with('userRoleTim.role', 'chatsV2One','chatsV2Two')->get();

                $listUser = User::with(
                    array('detailUser.daftarAnggota.user.userRoleTim' => function($r)
                    {
                        $r->where("tim_relawan_id", '=',
                        Auth::user()->current_team_id)
                        ->with('role');
                    }, 'detailUser.tingkatKoordinator',
                    'detailUser.daftarAnggota.user.chatsV2One',
                    'detailUser.daftarAnggota.user.chatsV2Two')
                    )
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
                })->with(
                    array('userRoleTim' => function($r)
                    {
                        $r->where("tim_relawan_id", '=',
                        Auth::user()->current_team_id)
                        ->with('role');
                    },
                    'chatsV2One',
                    'chatsV2Two')
                    )->get();
            } else if (Auth::user()->hasRoleTim(3,Auth::user()->current_team_id) && $getUserKoor->detailUser->tingkatKoordinator->nama_tingkat_koordinator == 'Kecamatan') {
                $listUser = User::whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],
                    ['tim_relawan_id', '=', Auth::user()->current_team_id]]);
                })->whereHas("detailUser.tingkatKoordinator", function ($p) {
                    $p->whereIn("nama_tingkat_koordinator", ['Kelurahan', 'Kota/Kab']);
                })->with(
                    array('userRoleTim' => function($r)
                    {
                        $r->where("tim_relawan_id", '=',
                        Auth::user()->current_team_id)
                        ->with('role');
                    },
                    'chatsV2One',
                    'chatsV2Two')
                )->get();
            } else if (Auth::user()->hasRoleTim(3,Auth::user()->current_team_id) && $getUserKoor->detailUser->tingkatKoordinator->nama_tingkat_koordinator == 'Kota/Kab') {
                $listUser = User::whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],
                    ['tim_relawan_id', '=', Auth::user()->current_team_id]]);
                })->whereHas("detailUser.tingkatKoordinator", function ($p) {
                    $p->whereIn("nama_tingkat_koordinator", ['Kecamatan', 'Provinsi']);
                })->with(
                    array('userRoleTim' => function($r)
                    {
                        $r->where("tim_relawan_id", '=',
                        Auth::user()->current_team_id)
                        ->with('role');
                    },
                    'chatsV2One',
                    'chatsV2Two')
                )->get();
            } else if (Auth::user()->hasRoleTim(3,Auth::user()->current_team_id) && $getUserKoor->detailUser->tingkatKoordinator->nama_tingkat_koordinator == 'Provinsi') {
                $listUser = User::whereHas("userRoleTim", function ($q) {
                    $q->where([["role_id", '=', 3],
                    ['tim_relawan_id', '=', Auth::user()->current_team_id]]);
                })->whereHas("detailUser.tingkatKoordinator", function ($p) {
                    $p->whereIn("nama_tingkat_koordinator", ['Kota/Kab']);
                })->with(
                    array('userRoleTim' => function($r)
                    {
                        $r->where("tim_relawan_id", '=',
                        Auth::user()->current_team_id)
                        ->with('role');
                    },
                    'chatsV2One',
                    'chatsV2Two')
                )->get();
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
}
