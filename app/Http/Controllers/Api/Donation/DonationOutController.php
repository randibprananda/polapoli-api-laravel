<?php

namespace App\Http\Controllers\Api\Donation;

use App\Http\Controllers\Controller;
use App\Models\DonationOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class DonationOutController extends Controller
{
    public function getAll()
    {
        $donation_outs = DonationOut::whereHas("donation", function ($q) {
            $q->where("tim_relawan_id", Auth::user()->current_team_id);
        })->with('donation')->get();
        if ($donation_outs != null) {
            return response()->json([
                'message' => 'Successfully get all donation outs.',
                'data' => $donation_outs
            ], Response::HTTP_OK);
        }
        return response()->json([
            'message' => 'Data not found.',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // public function createInvoice(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'amount' => 'required|numeric',
    //         'donation_id' => 'required|numeric',
    //         'bank_code' => 'required|string',
    //         'account_number' => 'required|string',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }
    //     try {
    //         DB::beginTransaction();
    //         Xendit::setApiKey(env('XENDIT_API_KEY'));
    //         $donation = Donation::find($request->donation_id);
    //         $externalId = 'disb-' . uniqid() . time();
    //         $params = [
    //             "external_id" => $externalId,
    //             'amount' => $request->amount,
    //             'bank_code' => $request->bank_code,
    //             'account_holder_name' => 'Tim Donation - ' . $donation->donation_title,
    //             'account_number' => $request->account_number,
    //             'description' => 'Disbursement for donation'
    //         ];
    //         $createDisbursements = \Xendit\Disbursements::create($params);

    //         $insertTransToDb = DonationOut::insert([
    //             'donation_id' => $request->donation_id,
    //             'external_id' => $externalId,
    //             'amount' => $request->amount,
    //             'account_number' => $request->account_number,
    //             'bank_code' => $request->bank_code,
    //         ]);
    //         DB::commit();

    //         return response()->json([
    //             'data' => [$createDisbursements, $insertTransToDb]
    //         ])->setStatusCode(200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'Invoice out cannot be created',
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    public function createInvoice(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'donation_id' => 'required|numeric',
            'bank_code' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            DB::beginTransaction();
            $externalId = 'disb-' . Str::uuid() . time();
            $insertTransToDb = DonationOut::insert([
                'donation_id' => $request->donation_id,
                'user_id' => Auth::user()->id,
                'tim_relawan_id' => Auth::user()->current_team_id,
                'external_id' => $externalId,
                'amount' => $request->amount,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
                'bank_code' => $request->bank_code,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::commit();

            return response()->json([
                'message' => 'Invoice out has been requested',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Invoice out cannot be created',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
