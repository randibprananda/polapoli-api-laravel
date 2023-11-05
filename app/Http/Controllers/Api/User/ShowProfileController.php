<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\TimRelawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ShowProfileController extends Controller
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
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                }))->find(Auth::user()->id);
            $currentTeam = TimRelawan::find($user->current_team_id);
            if ($user) {
                return response()->json([
                    'message' => 'Detail User.',
                    'currentTeam' => $currentTeam,
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
