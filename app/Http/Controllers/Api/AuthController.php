<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\IvesModel;
use App\Models\IvesContactModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $email = $request->email;
            $name = $request->name;
            $user = User::create(
                [
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt($request->password),
                ]
            );

            $token = $user->createToken('authtoken');
            return response()->json([
                'message' => 'Thanks for signing up! Please check your email to complete your registration.',
                'data' => ['token' => $token->plainTextToken, 'user' => $user]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, user cannot be registered.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email',$request->email)->first();

        if (empty($user)) {
            return response()->json(['error' => 'Email not registered'], 401);
        }
        try {
            $request->authenticate();
            $token = $request->user()->createToken('authtoken');

            $user = User::with('roles.role',"detailUser", 'detailUser.propinsi', 'detailUser.kabupaten', 'detailUser.kecamatan', 'detailUser.kelurahan')->find(Auth::user()->id);
            return response()->json(
                [
                    'message' => 'Logged Success',

                    'data' => [
                        'token' => $token->plainTextToken,
                        'user' => $user,
                    ],
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Credentials not valid'], 401);
        }
    }

    public function logout(Request $request)
    {
        $findCurrentTeam = User::find(Auth::user()->id);
        try {
            $findCurrentTeam->forceFill([
                'current_team_id' => null,
            ])->save();
            $request->user()->tokens()->delete();

            return response()->json(
                [
                    'message' => 'Logged out'
                ]
            );
        } catch (\Exception $e) {
            $findCurrentTeam->forceFill([
                'current_team_id' => null,
            ])->save();
            return response()->json(['error' => 'Login expired'], 401);
        }
    }

    public function submitRegistIves(Request $request)
    {
         $validator = Validator::make($request->all(), [
           'fullname' => 'required|string',
            'hp' => 'required|string',
            'company' => 'required|string',
            'job_level' => 'required|string',
            'job_title' => 'required|string',
            'email' => 'required|string',
            'city' => 'required|string',
            'industry' => 'required|string'

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

              $dataSponsorIves = IvesModel::create(
                [
                  'fullname' => $request->fullname,
                  'hp' => $request->hp,
                  'company' => $request->company,
                  'job_level' => $request->job_level,
                  'job_title' => $request->job_title,
                  'email' => $request->email,
                  'city' => $request->city,
                  'industry' => $request->industry,
                  'created_at' => date('Y-m-d H:i:s'),
                  'updated_at' => date('Y-m-d H:i:s'),
                ]
            );

            return response()->json([
                'message' => 'Submit data berhasil',
                'data' => $dataSponsorIves
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json(['error' => 'Wrong'], 400);
        }
    }

    public function submitContact(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'email' => 'required|string',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

              $dataContactIves = IvesContactModel::create(
                [
                  'nama' => $request->nama,
                  'email' => $request->email,
                  'message' => $request->message,
                  'created_at' => date('Y-m-d H:i:s'),
                  'updated_at' => date('Y-m-d H:i:s'),
                ]
            );

            return response()->json([
                'message' => 'Submit data berhasil',
                'data' => $dataContactIves
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json(['error' => 'Wrong'], 400);
        }
    }
}
