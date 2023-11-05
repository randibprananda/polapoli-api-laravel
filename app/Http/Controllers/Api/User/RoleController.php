<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\TimRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function index()
    {
        // if (!Auth::user()->customHasPermissionTo(10)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $roles = TimRole::where('tim_relawan_id',
        Auth::user()->current_team_id)->with('role')->get();
        if ($roles != null) {
            return response()->json([
                'message' => 'List of role',
                'data' => $roles,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No roles available',
            ], Response::HTTP_OK);
        }
    }

    public function store(Request $request)
    {
        // if (!Auth::user()->customHasPermissionTo(10)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'gaji' => 'required|numeric',
            'metode_gaji' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $name = $request->name;
            if ($request->metode_gaji != null && $request->gaji != null) {
                DB::beginTransaction();
                $role = Role::create(
                    [
                        'name' => $name,
                        'guard_name' => 'api'
                    ]
                );

                TimRole::create([
                    'tim_relawan_id' => Auth::user()->current_team_id,
                    'role_id' => $role->id,
                    'gaji' => $request->gaji,
                    'metode_gaji' => $request->metode_gaji,
                ]);
                DB::commit();
            } else {
                $role = Role::create(
                    [
                        'name' => $name,
                        'guard_name' => 'api'
                    ]
                );
                TimRole::create([
                    'tim_relawan_id' => Auth::user()->current_team_id,
                    'role_id' => $role->id,
                    'gaji' => $request->gaji,
                    'metode_gaji' => $request->metode_gaji,
                ]);
            }

            return response()->json([
                'message' => 'Role has been created',
                'data' => $role
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, role cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            // if (!Auth::user()->customHasPermissionTo(10)) {
            //     return response()->json([
            //         'message' => 'FORBIDDEN',
            //     ], Response::HTTP_FORBIDDEN);
            // }

            if ($role = TimRole::where('tim_relawan_id', Auth::user()->current_team_id)->with('role')->find($id)) {
                return response()->json([
                    'message' => 'Detail Role.',
                    'data' => $role
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, role not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        // if (!Auth::user()->customHasPermissionTo(10)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|between:2,100',
            'gaji' => 'required|numeric',
            'metode_gaji' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $findRole = Role::find($id);

            $name = $request->name;
            if ($findRole->id != 1 && $findRole->id != 2 && $findRole->id != 3 && $findRole->id != 4 && $findRole->id != 5) {
                if ($name!=null) {
                    $findRole->update(
                        [
                            'name' => $name,
                        ]
                    );
                }
                TimRole::where([['role_id', $id], ['tim_relawan_id', Auth::user()->current_team_id]])->update([
                    'gaji' => $request->gaji,
                    'metode_gaji' => $request->metode_gaji,
                ]);
            } else if ($findRole->id == 1 || $findRole->id == 2 || $findRole->id == 3 || $findRole->id == 4 || $findRole->id == 5) {
                if ($name != null) {
                    return response()->json([
                        'message' => 'Sorry, primary role cannot update name.',
                    ], Response::HTTP_BAD_REQUEST);
                }
                TimRole::where([['role_id', $id], ['tim_relawan_id', Auth::user()->current_team_id]])->update([
                    'gaji' => $request->gaji,
                    'metode_gaji' => $request->metode_gaji,
                ]);
            }
            return response()->json([
                'message' => 'Role has ben updated.',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, role cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        // if (!Auth::user()->customHasPermissionTo(10)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        try {

            $role = Role::find($id);
            if ($role == null) {
                return response()->json([
                    'message' => 'Sorry, role not found.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            if ($role->id != 1 && $role->id != 2 && $role->id != 3 && $role->id != 4 && $role->id != 5) {
                $role->delete();
            } else {
                return response()->json([
                    'message' => 'Sorry, primary role cannot delete.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return response()->json([
                'message' => 'Role has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Sorry, role cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
