<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimRelawan extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'nama_tim_relawan',
        'photo_tim_relawan',
        'tanggal_pemilihan',
        'jenis_pencalonan',
        'link_video',
    ];

    public function users()
    {
        return $this->belongsToMany(
            TimRelawan::class,
            'timrelawans_users',
            'tim_relawan_id',
            'user_id'
        );
    }
    public function roleTimPermission()
    {
        return $this->belongsToMany(
            RoleTimPermission::class,
            'roles_tims_permissions',
            'tim_relawan_id',
            'role_id',
            'permission_id'
        );
    }

    public function orderTim()
    {
        return $this->hasMany(OrderTim::class);
    }

    public function detailTimRelawan()
    {
        return $this->hasOne(DetailTimRelawan::class);
    }
}
