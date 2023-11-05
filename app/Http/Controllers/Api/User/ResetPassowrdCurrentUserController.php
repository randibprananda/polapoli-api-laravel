<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ResetPassowrdCurrentUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:8',
            'password' => 'required|string|confirmed|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $currentUser = Auth::user()->id;
            $user = User::find($currentUser);

            if (Hash::check($request->current_password, $user->password)) {

                $user->forceFill(
                    [
                        'password' =>  bcrypt($request->password),
                    ]
                )->save();

                return response()->json([
                    'message' => 'User password has ben updated.',
                    'data' => $user
                ], Response::HTTP_OK);
            }

            return response()->json([
                'message' => 'Current password doesnt match with our record.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, user password cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
