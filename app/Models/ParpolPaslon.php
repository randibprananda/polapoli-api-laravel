<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParpolPaslon extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'tentang_paslon_id',
        'foto_parpol',
        'nama_parpol'
    ];

    public function tentangPaslon()
    {
        return $this->belongsTo(TentangPaslon::class, 'tentang_paslon_id');
    }
}