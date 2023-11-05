<?php

namespace App\Http\Controllers\Api\Mobile\Chat;

use App\Http\Controllers\Controller;
use App\Models\TimRelawan;
use App\Models\User;
use App\Models\UserRoleTim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        try {
            $user = User::with(
            array('userRoleTim' => function($query)
                {
                    $query->where("tim_relawan_id", '=', Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                }, 'detailUser.tingkatKoordinator', 'detailUser.daftarAnggota'),
            )
                ->find(Auth::user()->id);
            if ($user) {
                return response()->json([
                    'message' => 'Current User.',
                    'data' => $user
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, token not valid.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
