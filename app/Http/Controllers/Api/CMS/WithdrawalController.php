<?php

namespace App\Http\Controllers\Api\CMS;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationOut;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function getAll()
    {
        $donation_outs = DonationOut::with('donation.timRelawan', 'user')->paginate(10);
        if ($donation_outs != null) {
            return response()->json([
                'message' => 'Get all request Withdrawal.',
                'data' => $donation_outs
            ], Response::HTTP_OK);
        }
        return response()->json([
            'message' => 'Data Withdrawal not found.',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function statusWithdrawal(Request $request, $id)
    {
        $donation_out = DonationOut::find($id);
        if ($donation_out != null) {
            if ($donation_out->status == 'APPROVED' || $donation_out->status == "REJECTED") {
                return response()->json([
                    'message' => 'Withdrawalcannot  change status before.',
                ], Response::HTTP_OK);
            }
            $donation_out->status = $request->status;
            if ($request->status == 'APPROVED') {
                $findDonation = Donation::find($donation_out->donation_id);
                $findDonation->forceFill([
                    'fluktuatif_penarikan_amount' => $findDonation->fluktuatif_penarikan_amount + $donation_out->amount,
                ])->save();
            }
            $donation_out->save();
            return response()->json([
                'message' => 'Successfully approve withdrawal.',
            ], Response::HTTP_OK);
        }
        return response()->json([
            'message' => 'Data Withdrawal not found.',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
