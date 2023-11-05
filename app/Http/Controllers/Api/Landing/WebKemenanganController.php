<?php

namespace App\Http\Controllers\Api\Landing;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Paslon;
use App\Models\AlokasiDonation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WebKemenanganController extends Controller
{
    public function getAllWebKemenangan()
    {
        $webKemenangans = Paslon::with(
            'contactWebKemenangan',
            'sosmedWebKemenangan',
            'tentangPaslon',
            'tentangPaslon.prokerPaslons',
            'tentangPaslon.misiPaslons',
            'tentangPaslon.parpolPaslons',
            'tentangPaslon.pengalamanKerja',
            'tentangPaslon.pengalamanKerja.detail_pengalaman'
        )->where('is_usung', 1)->get();
        if ($webKemenangans != null) {
            return response()->json([
                'message' => 'List of data web kemenangan',
                'data' => $webKemenangans,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No data web kemenangan available',
            ], Response::HTTP_OK);
        }
    }
    public function getWebKemenanganBySlug($slug)
    {
        $webKemenangan = Paslon::with(
            'contactWebKemenangan',
            'sosmedWebKemenangan',
            'tentangPaslon',
            'tentangPaslon.prokerPaslons',
            'tentangPaslon.misiPaslons',
            'tentangPaslon.parpolPaslons',
            'galeriPaslon',
            'tentangPaslon.pengalamanKerja',
            'tentangPaslon.pengalamanKerja.detail_pengalaman'

        )->where('is_usung', 1)->whereHas('tentangPaslon', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->first();

        $donations = Donation::with(['timRelawan', 'donationDonors'])->where([['tim_relawan_id', $webKemenangan->tim_relawan_id],['is_close',0]])->get();
        if ($webKemenangan != null) {
            return response()->json([
                'message' => 'Detail data web kemenangan',
                'data' => $webKemenangan,
                'donation_data' => $donations,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No data web kemenangan available',
            ], Response::HTTP_OK);
        }
    }

    public function getAlokasiDonasi($slug, $donasi_id)
    {
        $webKemenangan = Paslon::where('is_usung', 1)->whereHas('tentangPaslon', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->first();

        $donations = Donation::with('donationDonors','alokasiDonations')
        ->where([['tim_relawan_id', $webKemenangan->tim_relawan_id],['is_close',0]])
        ->find($donasi_id);
        if ($webKemenangan != null) {
            return response()->json([
                'message' => 'Detail alokasi donasi web kemenangan',
                'detail_donasi' => $donations,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No data alokasi donasi available',
            ], Response::HTTP_OK);
        }
    }
}
