<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\RoleTimPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        // if (!Auth::user()->customHasPermissionTo(10)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $permissions =  Permission::all();
        if ($permissions) {
            return response()->json([
                'message' => 'List of permission',
                'data' => $permissions,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No permission available',
            ], Response::HTTP_OK);
        }
    }

    public function detail($role)
    {
        // if (!Auth::user()->customHasPermissionTo(10)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }
        $permission = RoleTimPermission::with('tim_relawan')->where(
            [['role_id', $role], ['tim_relawan_id', Auth::user()->current_team_id]]
        )->get();
        if ($permission) {
            return response()->json([
                'message' => 'Detail of permission',
                'data' => $permission,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Permission not found',
            ], Response::HTTP_OK);
        }
    }
    public function addOrUpdate(Request $request, $role)
    {
        // if (!Auth::user()->customHasPermissionTo(10)) {
        //     return response()->json([
        //         'message' => 'FORBIDDEN',
        //     ], Response::HTTP_FORBIDDEN);
        // }


        $validator = Validator::make($request->all(), [
            'id_permission.*' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $data = $request->id_permission;

            if ($role != null) {

                DB::beginTransaction();

                $rolePermission = RoleTimPermission::where([['role_id', $role], ['tim_relawan_id', Auth::user()->current_team_id]])->delete();
                foreach ($data as $key => $value) {
                    RoleTimPermission::create([
                        'role_id' => $role,
                        'tim_relawan_id' => Auth::user()->current_team_id,
                        'permission_id' => $value,
                    ]);
                }

                DB::commit();
                return response()->json([
                    'message' => 'Role permission has been updated',
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, role permission cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
