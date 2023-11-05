<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class TimRole extends Model
{
    use HasFactory, FormatDates;
    protected $table = 'tims_roles';
    protected $fillable = [
        'tim_relawan_id',
        'role_id',
        'gaji',
        'metode_gaji',
    ];

    public function timRelawan()
    {
        return $this->belongsTo(TimRelawan::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}