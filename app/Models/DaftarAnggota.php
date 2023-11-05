<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarAnggota extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'detail_user_id',
        'user_id',
    ];


    public function detailUser()
    {
        return $this->belongsTo(DetailUser::class, 'detail_user_id');
    }
    public function detailUserAtasan()
    {
        return $this->belongsTo(DetailUser::class, 'detail_user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
