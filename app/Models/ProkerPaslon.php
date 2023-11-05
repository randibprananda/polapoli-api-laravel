<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProkerPaslon extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'tentang_paslon_id',
        'isi_proker'
    ];

    public function tentangPaslon()
    {
        return $this->belongsTo(TentangPaslon::class, 'tentang_paslon_id');
    }
}