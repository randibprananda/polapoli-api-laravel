<?php

namespace App\Http\Controllers\Api\User\Tagihan;

use App\Http\Controllers\Controller;
use App\Models\TagihanUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Xendit\Xendit;

class TagihanController extends Controller
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
                'on_demand_link' => 'https://bdd6-2001-448a-50a0-89a4-45e6-6f96-a17d-86f2.ap.ngrok.io/api/v1/user/tagihan',

            ];
            $createTransaction = \Xendit\Invoice::create($params);

            $insertTransToDb = TagihanUser::insert([
                'user_id' => Auth::user()->id,
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
        }
    }
}