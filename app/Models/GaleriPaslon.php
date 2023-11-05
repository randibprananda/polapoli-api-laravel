<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriPaslon extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'paslon_id',
        'foto_galeri_paslon',
        'keterangan'
    ];

    public function paslon()
    {
        return $this->belongsTo(Paslon::class, 'paslon_id');
    }
}