<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class UserRoleTim extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'role_id',
        'tim_relawan_id',
    ];
    public function timRelawan()
    {
        return $this->belongsTo(TimRelawan::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
