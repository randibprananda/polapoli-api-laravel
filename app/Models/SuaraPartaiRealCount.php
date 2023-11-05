<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuaraPartaiRealCount extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'real_count_id',
        'partai_id',
        'suara_sah_partai'
    ];

    public function realCount()
    {
        return $this->belongsTo(RealCount::class, 'real_count_id');
    }
    public function paslon()
    {
        return $this->belongsTo(Partai::class, 'partai_id');
    }
}
