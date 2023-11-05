<?php

namespace App\Http\Controllers\Api\Donation;

use App\Http\Controllers\Controller;
use App\Models\DonationDonor;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Xendit\Xendit;

class DonationDonorController extends Controller
{
    public function createInvoice(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'amount' => 'required|numeric',
            'donation_id' => 'required|numeric',
            'message' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            DB::beginTransaction();
            Xendit::setApiKey(env('XENDIT_API_KEY'));
            $externalId = 'external_payment_id_' . uniqid() . time();
            $params = [
                "external_id" => $externalId,
                "payer_email" => $request->email,
                'amount' => $request->amount,
                'invoice_duration' => 1200,
                'description' => $request->message,

            ];
            $createTransaction = \Xendit\Invoice::create($params);

            $insertTransToDb = DonationDonor::insert([
                'donation_id' => $request->donation_id,
                'external_id' => $externalId,
                'payment_channel' => 'Payment Link',
                'email' => $request->email,
                'name' => $request->name,
                'amount' => $request->amount,
                'message' => $request->message,
            ]);
            DB::commit();

            return response()->json([
                'data' => [$createTransaction, $insertTransToDb]
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Invoice donor cannot be created',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}