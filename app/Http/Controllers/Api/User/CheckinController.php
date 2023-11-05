<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\CheckinCheckout;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckinController extends Controller
{
    public function checkin()
    {

        //get currently signed in users username

        $userId = Auth::user()->id;
        $currentTeamId = Auth::user()->current_team_id;

        //Initialise the carbon date object.

        $date = Carbon::now();

        //check if the user has already checked in for today.

        $result = CheckinCheckout::where([['date', '=', $date->toDateString()], ['user_id', '=', $userId], ['tim_relawan_id', $currentTeamId]])->first();

        if ($result) {
            $message = "You have already checked in. Thankyou.";
            $checks = CheckinCheckout::where('date', '=', $date->toDateString())->get();

            return response()->json([
                'message' => $message,
            ], Response::HTTP_OK);
        }


        //fix the carbon chickin time
        //and check out.

        $checks = array(
            array(

                'user_id' => $userId,
                'tim_relawan_id' => $currentTeamId,
                'date' => $date->toDateString(),
                'checkin_at' => $date->toTimeString(),
            )
        );

        //Mysql date doesnt support Carbons toFormattedDateString(). Why?
        CheckinCheckout::insert($checks);

        $checks = CheckinCheckout::where('date', '=', $date->toDateString())->get();

        return response()->json([
            'message' => 'Checked in successfully, have a nice day',
        ], Response::HTTP_OK);
    }

    public function checkout()
    {
        $userId = Auth::user()->id;
        $currentTeamId = Auth::user()->current_team_id;
        $date = Carbon::now();

        CheckinCheckout::where([['date', $date->toDateString()], ['user_id', '=', $userId], ['tim_relawan_id', $currentTeamId]])->update(['checkout_at' => $date->toTimeString()]);

        $checks = CheckinCheckout::where('date', '=', $date->toDateString())->get();

        return response()->json([
            'message' => 'Checked out successfully, Thankyou.',
        ], Response::HTTP_OK);
    }

    public function getDataCheck()
    {
        $currentTeamId = Auth::user()->current_team_id;
        $date = Carbon::now();
        $check = CheckinCheckout::where([['date', $date->toDateString()], ['user_id', Auth::user()->id], ['tim_relawan_id', $currentTeamId]])->first();

        return response()->json([
            'message' => 'Data Checkin Chekcout today',
            'data' => $check
        ], Response::HTTP_OK);
    }
}