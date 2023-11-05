<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuaraCalonAnggotaRealCount extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'real_count_id',
        'paslon_id',
        'suara_sah_paslon'
    ];

    public function realCount()
    {
        return $this->belongsTo(RealCount::class, 'real_count_id');
    }

    public function paslon()
    {
        return $this->belongsTo(CalonAnggota::class, 'paslon_id');
    }
}
