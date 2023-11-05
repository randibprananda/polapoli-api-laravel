<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Exceptions\GuardDoesNotMatch;

class RoleTimPermission extends Model
{
    use HasFactory, FormatDates;
    protected $table = 'roles_tims_permissions';
    protected $fillable = [
        'role_id',
        'tim_relawan_id',
        'permission_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function tim_relawan()
    {
        return $this->belongsTo(TimRelawan::class);
    }
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}