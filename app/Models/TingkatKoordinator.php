<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TingkatKoordinator extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'detail_user_id',
        'nama_tingkat_koordinator',
    ];


    public function detailUser()
    {
        return $this->belongsTo(DetailUser::class, 'detail_user_id');
    }
}