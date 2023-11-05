<?php

namespace App\Http\Controllers\Api\Donation;

use App\Http\Controllers\Controller;
use App\Models\AlokasiDonation;
use App\Models\Donation;
use App\Models\DonationDonor;
use App\Models\DonationOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function getAll()
    {
        // if (!Auth::user()->customHasPermissionTo(3)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $donations = Donation::with(['timRelawan', 'createdBy', 'donationDonors'])->where('tim_relawan_id', Auth::user()->current_team_id)->get();
        if ($donations != null) {
            return response()->json([
                'message' => 'List of donation',
                'data' => $donations,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No donations available',
            ], Response::HTTP_OK);
        }
    }

    public function addDonation(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(3)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'donation_image' => 'required|image|mimes:png,jpg,jpeg',
            'donation_title' => 'required|string',
            'donation_description' => 'required|string',
            'is_target' => 'nullable|boolean',
            'is_batas' => 'nullable|boolean',
            'target_amount' => 'nullable|numeric',
            'batas_akhir' => 'nullable|string',
            'is_close' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $targetAmount = $request->target_amount;
        $batasAkhir = $request->batas_akhir;

        $isTarget = 1;
        $isBatas = 1;
        $isClose = 1;
        if ($request->is_target == 0 || $request->is_target == null) {
            $targetAmount == null;
            $isTarget = 0;
        }
        if ($request->is_batas == 0 || $request->is_batas == null) {
            $targetAmount == null;
            $isBatas = 0;
        }
        if ($request->is_close == 0 || $request->is_close == null) {
            $targetAmount == null;
            $isClose = 0;
        }

        try {

            $createdBy = Auth::user()->id;
            $findTeamRelawanId = Auth::user()->current_team_id;
            if ($findTeamRelawanId  && $request->hasFile('donation_image')) {

                $filename = 'donation_image-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->donation_image->extension();
                $request->file('donation_image')->move('storage/foto-donation/', $filename);
                $donation = Donation::create([
                    'donation_image' => env('APP_URL') . '/storage/foto-donation/' . $filename,
                    'donation_title' => $request->donation_title,
                    'donation_description' => $request->donation_description,
                    'is_target' => $isTarget,
                    'is_batas' =>  $isBatas,
                    'target_amount' => $targetAmount,
                    'batas_akhir' => $batasAkhir,
                    'is_close' =>  $isClose,
                    'tim_relawan_id' => $findTeamRelawanId,
                    'created_by' => $createdBy,
                ]);


                return response()->json([
                    'message' => 'Donation has been created',
                    'data' => $donation
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Sorry, donation cannot be created.',
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, donation cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detailDonation($id)
    {
        // if (!Auth::user()->customHasPermissionTo(3)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $donation = Donation::with(['timRelawan', 'createdBy', 'donationDonors'])->where('tim_relawan_id', Auth::user()->current_team_id)->find($id);
        $totalDonation = DonationDonor::where([['status', 'PAID'], ['donation_id', $id]])->whereHas('donation', function ($query) {
            $query->where('tim_relawan_id', Auth::user()->current_team_id);
        })->select(
            DB::raw('sum(amount) as total_amount'),
        )
            ->first();
        return response()->json([
            'message' => 'Donor Donation detail',
            'totalDonation' => $totalDonation,
            'data' => $donation
        ], Response::HTTP_OK);
    }
    public function detailPayout($id)
    {
        // if (!Auth::user()->customHasPermissionTo(3)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $donation = Donation::with(['timRelawan', 'createdBy', 'donationOuts'])->where('tim_relawan_id', Auth::user()->current_team_id)->find($id);
        $totalPayout = DonationOut::where([['status', 'CLAIMED'], ['donation_id', $id]])->whereHas('donation', function ($query) {
            $query->where('tim_relawan_id', Auth::user()->current_team_id);
        })->select(
            DB::raw('sum(amount) as total_amount'),
        )
            ->first();
        return response()->json([
            'message' => 'Payout Donation detail',
            'totalPayout' => $totalPayout,
            'data' => $donation
        ], Response::HTTP_OK);
    }
    public function detailAlokasi($id)
    {
        // if (!Auth::user()->customHasPermissionTo(3)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $donation = Donation::with(['timRelawan', 'createdBy', 'alokasiDonations'])->where('tim_relawan_id', Auth::user()->current_team_id)->find($id);
        $alokasiDonation = AlokasiDonation::where([['donation_id', $id]])->whereHas('donation', function ($query) {
            $query->where('tim_relawan_id', Auth::user()->current_team_id);
        })->select(
            DB::raw('sum(nominal) as total_amount'),
        )
            ->first();
        return response()->json([
            'message' => 'Alokasi Donation detail',
            'alokasiDonation' => $alokasiDonation,
            'data' => $donation
        ], Response::HTTP_OK);
    }

    public function updateDonation(Request $request, $id)
    {
        // if (!Auth::user()->customHasPermissionTo(3)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'donation_image' => 'nullable|image|mimes:png,jpg,jpeg',
            'donation_title' => 'required|string',
            'donation_description' => 'required|string',
            'is_target' => 'nullable|boolean',
            'is_batas' => 'nullable|boolean',
            'target_amount' => 'nullable|numeric',
            'batas_akhir' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $targetAmount = $request->target_amount;
        $batasAkhir = $request->batas_akhir;

        $isTarget = 1;
        $isBatas = 1;
        if ($request->is_target == 0 || $request->is_target == null) {
            $targetAmount == null;
            $isTarget = 0;
        }
        if ($request->is_batas == 0 || $request->is_batas == null) {
            $targetAmount == null;
            $isBatas = 0;
        }
        try {

            $findTeamRelawanId = Auth::user()->current_team_id;
            if ($findTeamRelawanId) {

                $donation = Donation::find($id);

                $donation->forceFill([
                    'donation_title' => $request->donation_title,
                    'donation_description' => $request->donation_description,
                    'is_target' => $isTarget,
                    'is_batas' =>  $isBatas,
                    'target_amount' => $targetAmount,
                    'batas_akhir' => $batasAkhir,
                ])->save();


                $timeNow = now()->format('d-m-Y');

                if ($donation->is_batas == 1 && $donation->batas_akhir != null) {
                    if ($donation->is_close == 1 && strtotime($donation->batas_akhir) > strtotime($timeNow)) {
                        $donation->forceFill([
                            'is_close' =>  0,
                        ])->save();
                    }
                }

                if ($request->hasFile('donation_image')) {
                    if($donation->donation_image != null)
                    {
                        Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $donation->donation_image));
                    }
                    $filename = 'donation_image-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->donation_image->extension();
                    $request->file('donation_image')->move('storage/foto-donation/', $filename);
                    $donation->forceFill([
                        'donation_image' => env('APP_URL') . '/storage/foto-donation/' . $filename,
                    ])->save();
                }


                return response()->json([
                    'message' => 'Donation has been created',
                    'data' => $donation
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Sorry, donation cannot be created.',
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, donation cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function openCloseDonation(Request $request, $id)
    {
        $donation = Donation::find($id);
        if ($donation->is_close == 0) {
            $donation->forceFill([
                'is_close' =>  1,
            ])->save();

            return response()->json([
                'message' => 'Donation has been closed',
            ], Response::HTTP_OK);
        } else {
            $donation->forceFill([
                'is_close' =>  0,
            ])->save();
            return response()->json([
                'message' => 'Donation has been opened',
            ], Response::HTTP_OK);
        }
    }
    public function deleteDonation($id)
    {
        // if (!Auth::user()->customHasPermissionTo(3)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        try {
            $donation = Donation::find($id);
            if($donation->donation_image != null)
            {
                Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $donation->donation_image));
            }
            $donation->delete();
            return response()->json([
                'message' => 'Donation has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, donation cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
