<?php

namespace App\Http\Controllers\Api\RuteRelawan;

use App\Http\Controllers\Controller;
use App\Models\DetailTimRelawan;
use App\Models\RoleTimPermission;
use App\Models\TimRelawan;
use App\Models\TimRole;
use App\Models\User;
use App\Models\UserRoleTim;
use App\Models\RuteRelawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;


class RuteRelawanController extends Controller
{

	public function listRuteRelawan(Request $request)
    {
        $search = '%'.$request->search.'%';

        if($search != null)
        {
            $ruterelawans = RuteRelawan::with('timRelawan','propinsi','kabupaten','kecamatan','kelurahan','user')
                ->where(function($q) use($search){
                    return $q->where('jenis_survey', 'LIKE', $search)
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'LIKE', $search);
                    });
                })
                ->where('tim_relawan_id', Auth::user()->current_team_id)
                ->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString();
        }
        else
        {
            $ruterelawans = RuteRelawan::with('timRelawan','propinsi','kabupaten','kecamatan','kelurahan','user')
                ->where('tim_relawan_id', Auth::user()->current_team_id)
                ->orderBy('created_at', 'desc')
                ->paginate(10)->withQueryString(); 
        }

        if ($ruterelawans != null) {
            return response()->json([
                'message' => 'List of rute relawan',
                'data' => $ruterelawans,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No ruterelawans available',
            ], Response::HTTP_OK);
        }
    }

    public function detailRuteRelawan($id)
    {

        $rute = RuteRelawan::with('timRelawan','propinsi','kabupaten','kecamatan','kelurahan','user')->find($id);
        return response()->json([
            'message' => 'Rute detail',
            'data' => $rute
        ], Response::HTTP_OK);
    }


    public function addRuteRelawan(Request $request)
    {
    	// code...
    	$validator = Validator::make($request->all(), [
            'jenis_survey' => 'required|string',
            'user_id' => 'required|numeric',
            'propinsi_id' => 'required|numeric',
            'kabupaten_id' => 'required|numeric',
            'kecamatan_id' => 'required|numeric',
            'kelurahan_id' => 'required|numeric',
            'jadwal_kunjungan' => 'required|string',
            'keterangan' => 'required'

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

        	$rute = RuteRelawan::create(
                [
                    'jenis_survey' => $request->jenis_survey,
		            'user_id' => $request->user_id,
		            'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'rt' => $request->rt,
                    'rw' => $request->rw,
		            'tim_relawan_id' => $request->tim_relawan_id,
		            'jadwal_kunjungan' => $request->jadwal_kunjungan,
		            'keterangan' => $request->keterangan
		        ]
            );

        	return response()->json([
                'message' => 'Rute has been created',
                'data' => $rute
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
        	return response()->json([
                'message' => 'Sorry, Rute cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateRute(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
           'jenis_survey' => 'required|string',
            'user_id' => 'required|numeric',
            'propinsi_id' => 'required|numeric',
            'kabupaten_id' => 'required|numeric',
            'kecamatan_id' => 'required|numeric',
            'kelurahan_id' => 'required|numeric',
            'jadwal_kunjungan' => 'required|string',
            'keterangan' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

            $rute = RuteRelawan::find($id);

            $rute->forceFill(
                [
                    'jenis_survey' => $request->jenis_survey,
		            'user_id' => $request->user_id,
		            'propinsi_id' => $request->propinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'rt' => $request->rt,
                    'rw' => $request->rw,
                    'tim_relawan_id' => $request->tim_relawan_id,
		            'jadwal_kunjungan' => $request->jadwal_kunjungan,
		            'keterangan' => $request->keterangan
                ]
            )->save();

            return response()->json([
                'message' => 'Rute has ben updated.',
                'data' => $rute
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, rute cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteRute($id)
    {
        try {
            RuteRelawan::find($id)->delete();
            return response()->json([
                'message' => 'Rute has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, Rute cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
