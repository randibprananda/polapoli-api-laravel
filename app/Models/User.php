<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;
use App\Traits\FormatDates;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, FormatDates;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phonenumber',
        'address',
        'profile_photo_path',
        'email_verified_at',
    ];

    protected $date = [
        'last_sign_in_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {

        $url = env('APP_URL') . '/reset-password?token=' . $token;

        $this->notify(new ResetPasswordNotification($url));
    }

    public function detailUser()
    {
        return $this->hasOne(DetailUser::class);
    }
    public function timRelawans()
    {
        return $this->belongsToMany(
            TimRelawan::class,
            'timrelawans_users',
            'user_id',
            'tim_relawan_id'
        );
    }

    public function customHasPermissionTo($permission): bool
    {

        if (config('permission.enable_wildcard_permission', false)) {
            return $this->hasWildcardPermission($permission, $this->getDefaultGuardName());
        }

        $permissionClass = new RoleTimPermission;

        if (is_int($permission)) {
            $data = $permissionClass->where('permission_id', '=', $permission)->first();
        }
        // if (!$this->getGuardNames()->contains($permission->guard_name)) {
        //     throw GuardDoesNotMatch::create($permission->guard_name, $this->getGuardNames());
        // }

        if ($data != null) {
            if ($data->permission_id == $permission && $data->tim_relawan_id == Auth::user()->current_team_id) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function roleTimPermission()
    {
        return $this->hasMany(RoleTimPermission::class);
    }

    public function userRoleTim()
    {
        return $this->hasMany(UserRoleTim::class);
    }

    public function roles()
    {
        return $this->hasMany(UserRoleTim::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function chatsV2One()
    {
        return $this->hasMany(ChatV2::class,'user_one');
    }
    public function chatsV2Two()
    {
        return $this->hasMany(ChatV2::class,'user_two');
    }

    public function daftarAnggotas()
    {
        return $this->hasMany(DaftarAnggota::class);
    }
    public function daftarAnggotaAtasan()
    {
        return $this->hasOne(DaftarAnggota::class);
    }

    public function hasRoleTim($role_id, $tim_relawan_id)
    {
        $data = $this->userRoleTim()->where([['role_id', '=', $role_id],['tim_relawan_id', '=', $tim_relawan_id]])->first();
        if ($data != null) {
            return true;
        }
        return false;
    }

    public function hasRoleTimV2($role_id)
    {
        $data = $this->userRoleTim()->where([['role_id', '=', $role_id],['tim_relawan_id', '=', Auth::user()->current_team_id]])->first();
        if ($data != null) {
            return true;
        }
        return false;
    }

    public function getRoleTim()
    {
        return $this->userRoleTim()
        ->where('tim_relawan_id', '=', Auth::user()->current_team_id)->first()->role;
    }

    public function assignRoleTim($role_id)
    {
            UserRoleTim::create([
                'user_id' => Auth::user()->id,
                'role_id' => $role_id,
                'tim_relawan_id' => Auth::user()->current_team_id,
            ]);
    }
}
