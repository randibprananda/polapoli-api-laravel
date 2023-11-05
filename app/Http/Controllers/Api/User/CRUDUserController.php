<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\RoleTimPermission;
use App\Models\TimRelawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class CRUDUserController extends Controller
{
    public function index()
    {
        $users = User::with(
            array('detailUser', 'userRoleTim' => function($r)
            {
                $r->where("tim_relawan_id", '=',
                Auth::user()->current_team_id)
                ->with('timRelawan','role');
            }))->whereHas("userRoleTim.role", function ($q) {
            $q->whereIn("name", ["Project Manager", "Konsultan"]);
        })
        ->whereHas("timRelawans", function ($p) {
            $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
        })
        ->orderBy('created_at', 'desc')->get();
        if ($users != null) {
            return response()->json([
                'message' => 'List of users',
                'data' => $users,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No users available',
            ], Response::HTTP_OK);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|in:1,2',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $name = $request->name;
            $email = $request->email;
            $password = $request->password;
            $user = User::create(
                [
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt($password),
                ]
            );
            $user->assignRoleTim($request->role);

            return response()->json([
                'message' => 'User has ben created',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, user cannot be created.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['nullable', 'string', 'between:2,100', Rule::unique('users')->ignore($id)],
            'address' => 'nullable|string|between:2,100',
            'phonenumber' => 'nullable|string|between:2,14',
            'name' => 'nullable|string|between:2,100',
            'email' => ['nullable', 'string', 'email', 'max:100', Rule::unique('users')->ignore($id)],
            'role' => 'nullable|in:1,2',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $user = User::find($id);
            $email = $request->email;
            $name = $request->name;
            $username = $request->username;
            $address = $request->address;
            $phonenumber = $request->phonenumber;

            if ($email != null) {
                if (!($user->email === $email)) {
                    $user->forceFill(
                        [
                            'email_verified_at' => null,
                            'email' => $email,
                        ]
                    )->save();
                }
            }
            $user->assignRoleTim($request->role);

            if ($name != null) {
                $user->forceFill(
                    [
                        'name' => $name,
                    ]
                )->save();
            }
            if ($username != null) {
                $user->forceFill(
                    [
                        'username' => $username,
                    ]
                )->save();
            }
            if ($address != null) {
                $user->forceFill(
                    [
                        'address' => $address,
                    ]
                )->save();
            }
            if ($phonenumber != null) {
                $user->forceFill(
                    [
                        'phonenumber' => $phonenumber,
                    ]
                )->save();
            }

            return response()->json([
                'message' => 'User has ben updated.',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, user cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            if ($user = User::with(
                array('userRoleTim' => function($r)
                {
                    $r->where("tim_relawan_id", '=',
                    Auth::user()->current_team_id)
                    ->with('timRelawan','role');
                }))
            ->whereHas("userRoleTim.role", function ($q) {
                $q->whereIn("id", [1, 2]);
            })
            ->whereHas("timRelawans", function ($p) {
                $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
            })
            ->orderBy('created_at', 'desc')->find($id)) {
                return response()->json([
                    'message' => 'Detail User.',
                    'data' => $user
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, user not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCurrentTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_team_id' => 'nullable|numeric',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $user = User::find(Auth::user()->id);

            $user->forceFill(
                [
                    'current_team_id' => $request->current_team_id,
                ]
            )->save();

            $currentTeamId = $request->current_team_id;

            $permission = RoleTimPermission::where([['role_id', Auth::user()->userRoleTim->first()->role_id],
            ['tim_relawan_id', $request->current_team_id]])->select('id', 'permission_id')->get();

            if ($user->hasRoleTimV2(1) || $user->hasRoleTimV2(2)) {
                $user = User::with(
                    array('userRoleTim' => function($r) use ($currentTeamId)
                    {
                        $r->where("tim_relawan_id", '=',
                        $currentTeamId)
                        ->with('role');
                    }
                    )
                )->find(Auth::user()->id);
            }else if ($user->hasRoleTimV2(3)) {
                $user = User::with(
                    array('userRoleTim' => function($r) use ($currentTeamId)
                    {
                        $r->where("tim_relawan_id", '=',
                        $currentTeamId)
                        ->with('role');
                    },
                    'detailUser.tingkatKoordinator',
                    'detailUser.propinsi',
                    'detailUser.kabupaten',
                    'detailUser.kecamatan',
                    'detailUser.kelurahan'
                    )
                )->find(Auth::user()->id);
            }else if ($user->hasRoleTimV2(4) || $user->hasRoleTimV2(5)) {
                $user = User::with(
                    array('userRoleTim' => function($r) use ($currentTeamId)
                    {
                        $r->where("tim_relawan_id", '=',
                        $currentTeamId)
                        ->with('role');
                    },
                    'detailUser.propinsi',
                    'detailUser.kabupaten',
                    'detailUser.kecamatan',
                    'detailUser.kelurahan')
                )->find(Auth::user()->id);
            }else{
                $user = User::with(
                    array('userRoleTim' => function($r) use ($currentTeamId)
                    {
                        $r->where("tim_relawan_id", '=',
                        $currentTeamId)
                        ->with('role');
                    },
                    'detailUser.propinsi',
                    'detailUser.kabupaten',
                    'detailUser.kecamatan',
                    'detailUser.kelurahan')
                )->find(Auth::user()->id);
            }
            return response()->json([
                'message' => 'Tim relawan has been updated.',
                'user' => $user,
                'permission' => $permission
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, current team id cannot be updated.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            return response()->json([
                'message' => 'User has ben deleted.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, user cannot be deleted.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCurrentTeam()
    {
        try {
            $currentTeamId = Auth::user()->current_team_id;
            $permission = RoleTimPermission::where([
                ['role_id', Auth::user()->userRoleTim->first()->role_id],
                ['tim_relawan_id', Auth::user()->current_team_id]
            ])->select('id', 'permission_id')->get();

            if ($currentTimRelawan = TimRelawan::with(array('orderTim' => function($r){
                $r->where('status', 'SETTLED')
                ->orWhere('status', 'PAID')->with('orderTimAddon');
            }))->find($currentTeamId)) {
                return response()->json([
                    'message' => 'Current Team.',
                    'data' => $currentTimRelawan,
                    'permission' => $permission
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => "You haven't joined the team yet",
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, current team user not found.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
