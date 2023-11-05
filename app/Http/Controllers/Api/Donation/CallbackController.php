<?php

namespace App\Http\Controllers\Api\Donation;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationDonor;
use App\Models\DonationOut;
use App\Models\OrderTim;
use App\Models\TimRelawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Xendit\Xendit;

class CallbackController extends Controller
{
    public function inCallback(Request $request)
    {
        Xendit::setApiKey(env('XENDIT_API_KEY'));

        try {

            $donationDonor = DonationDonor::where([
                'external_id' => $request->get('external_id'),
                'status' => 'PENDING'
            ])->first();

            $orderTim = OrderTim::where([
                'invoice_code' => $request->get('external_id'),
                'status' => 'PENDING'
            ])->first();

            if ($donationDonor) {
                DB::beginTransaction();

                if ($request->get('status') == 'PAID' || $request->get('status') == 'SETTLED') {
                    $donationDonor->update([
                        'status' => 'PAID'
                    ]);

                    $findDonation = Donation::find($donationDonor->donation_id);


                    $findDonation->forceFill([
                        'total_amount' => ($findDonation->total_amount + $request->get('amount')),
                    ])->save();

                    if ($findDonation->is_target != 0) {
                        if ($findDonation->total_amount >= $findDonation->target_amount) {
                            $findDonation->forceFill([
                                'is_close' => 1
                            ])->save();
                        }
                    }
                } else {
                    $donationDonor->update([
                        'status' => 'EXPIRED'
                    ]);
                }

                DB::commit();
                return response()->json([
                    'data' => $donationDonor
                ])->setStatusCode(200);
            } elseif ($orderTim) {
                if ($request->get('status') == 'PAID' || $request->get('status') == 'SETTLED') {
                    $orderTim->update([
                        'status' => 'PAID'
                    ]);

                    $findTim = OrderTim::find($orderTim->id);
                    $findTim->forceFill([
                        'amount' => $request->get('amount'),
                    ])->save();

                    $timRelawan = TimRelawan::find($findTim->tim_relawan_id);
                    $timRelawan->forceFill([
                        'is_premium' => 1,
                    ])->save();
                } else {
                    $orderTim->update([
                        'status' => 'EXPIRED'
                    ]);
                }
            }

            return response()->json([
                'data' => 'Data not Found'
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->result = $e->getMessage();
            return false;
        }
    }

    public function outCallback(Request $request)
    {
        Xendit::setApiKey(env('XENDIT_API_KEY'));

        try {
            $donationOut = DonationOut::where([
                'external_id' => $request->get('external_id'),
                'status' => 'PENDING'
            ])->first();

            if ($donationOut) {

                if ($request->get('status') == 'CLAIMED' || $request->get('status') == 'COMPLETED') {
                    $donationOut->update([
                        'status' => 'CLAIMED'
                    ]);

                    $findDonation = Donation::find($donationOut->donation_id);

                    $findDonation->forceFill([
                        'fluktuatif_penarikan_amount' => ($findDonation->fluktuatif_penarikan_amount + $request->get('amount')),
                    ])->save();
                } else {
                    $donationOut->update([
                        'status' => 'EXPIRED'
                    ]);
                }

                DB::commit();
                return response()->json([
                    'data' => $donationOut
                ])->setStatusCode(200);
            }
            return response()->json([
                'data' => 'Data not Found'
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
