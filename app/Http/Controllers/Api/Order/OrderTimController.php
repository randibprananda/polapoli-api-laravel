<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\AddOnCMS;
use App\Models\OrderTim;
use App\Models\OrderTimAddon;
use App\Models\PricingCMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Xendit\Xendit;
use Illuminate\Support\Str;

class OrderTimController extends Controller
{
    public function createInvoice(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'jenis_paket_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            DB::beginTransaction();
            Xendit::setApiKey(env('XENDIT_API_KEY'));

            $pricing = PricingCMS::find($request->jenis_paket_id);
            $addon = AddOnCMS::whereIn('id', $request->jenis_addon_id)->get();

            $sumAddon = AddOnCMS::whereIn('id', $request->jenis_addon_id)->sum('price');

            $email = Auth::user()->email;
            $amount = $pricing->price + $sumAddon;

            $jenisPaket = $pricing->title;
            $description = "Pembelian Akun Premium";
            $tanggalAkhir = Carbon::now()->addMonth($pricing->duration);
            $tanggalAwal = now();

            // Checking Previous Order
            // $orderTimCheck = OrderTim::where('user_id', Auth::user()->id)->where('status', 'PAID')
            // ->orderBy('created_at','desc')->first();

            // if ($orderTimCheck != null) {
            //     $tanggalAwal = $orderTimCheck->tanggal_akhir;
            //     $tanggalAkhir = Carbon::parse($tanggalAwal)->addMonth($data->duration);
            // }

            $externalId = 'premium_order_' . Str::uuid() . time();
            $params = [
                "external_id" => $externalId,
                "payer_email" => $email,
                'amount' => $amount,
                'invoice_duration' => 1200,
                'description' => $description,

            ];
            $createTransaction = \Xendit\Invoice::create($params);

            $order = OrderTim::create([
                    'tim_relawan_id' => Auth::user()->current_team_id,
                    'user_id' => Auth::user()->id,
                    'jenis_paket' => $jenisPaket,
                    'tanggal_awal' => $tanggalAwal,
                    'tanggal_akhir' => $tanggalAkhir,
                    'invoice_code' => $externalId,
                    'payment_channel' => 'Payment Link',
                    'amount' => $amount,
                ]);
            foreach ($addon as $value) {
                OrderTimAddon::create([
                    'order_tim_id' => $order->id,
                    'title'        => $value['title'],
                    'price'        => $value['price'],
                    'periode'      => $value['periode'],
                    'description'  => $value['description'],
                ]);
            }
            DB::commit();

            return response()->json([
                'message' => 'Order tim has been created',
                'data' => [$createTransaction]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Order tim cannot be created',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function list()
    {
        $data = OrderTim::with('orderTimAddon')->where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->get();
        return response()->json([
            'message' => 'List Order',
            'data' => $data
        ])->setStatusCode(200);
    }
}
