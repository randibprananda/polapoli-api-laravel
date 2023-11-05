<?php

namespace App\Http\Controllers\Api\Donation;

use App\Http\Controllers\Controller;
use App\Models\AlokasiDonation;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class AlokasiDonationController extends Controller
{
    public function alokasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donation_id' => 'required|numeric',
            'keterangan' => 'required|string',
            'nominal' => 'required|numeric',
            'bukti_alokasi' => 'required|image|mimes:png,jpg,jpeg',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['bukti_alokasi'] = $request->file('bukti_alokasi')->store('bukti_alokasi');
            $data['bukti_alokasi'] = env('APP_URL') . '/storage/' . $data['bukti_alokasi'];
            $request->file('bukti_alokasi')->move('storage/bukti_alokasi',  $data['bukti_alokasi']);
            $alokasi = AlokasiDonation::create($data);

            $donation = Donation::find($request->donation_id);
            $donation->forceFill([
                'fluktuatif_alokasi_amount' => $donation->fluktuatif_alokasi_amount + $request->nominal,
            ])->save();
            DB::commit();

            return response()->json([
                'data' => $alokasi
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, alokasi cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function listAlokasi()
    {
        // if (!Auth::user()->customHasPermissionTo(3)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $alokasiDonation = AlokasiDonation::whereHas('donation', function ($query) {
            $query->where('tim_relawan_id', Auth::user()->current_team_id);
        })->get();
        return response()->json([
            'message' => 'List Alokasi',
            'alokasiDonation' => $alokasiDonation,
        ], Response::HTTP_OK);
    }

    public function updateAlokasi(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'keterangan' => 'required|string',
            'bukti_alokasi' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            $alokasi = AlokasiDonation::find($id);


            DB::beginTransaction();

            $alokasi->forceFill([
                'keterangan' => $request->keterangan,
            ])->save();

            if ($request->hasFile('bukti_alokasi')) {
                if($alokasi->bukti_alokasi != null)
                {
                    Storage::disk('public')->delete(str_replace(env('APP_URL').'/storage/', '', $alokasi->bukti_alokasi));
                }
                $filename = 'bukti_alokasi-' . uniqid() . strtolower(Str::random(10)) . '.' . $request->bukti_alokasi->extension();
                $request->file('bukti_alokasi')->move('storage/bukti_alokasi/', $filename);
                $alokasi->forceFill([
                    'bukti_alokasi' => env('APP_URL') . '/storage/bukti_alokasi/' . $filename,
                ])->save();
            }
            DB::commit();

            return response()->json([
                'data' => $alokasi
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, alokasi cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detailAlokasi($id)
    {
        $alokasi = AlokasiDonation::with('donation')->find($id);
        return response()->json([
            'data' => $alokasi
        ])->setStatusCode(200);
    }
}
