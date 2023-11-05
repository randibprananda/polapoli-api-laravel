<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TentangPaslon extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'paslon_id',
        'background',
        'tema_warna',
        'slogan',
        'motto',
        'slug',
        'foto_calon_web_kemenangan',
        'visi'
    ];

    public function misiPaslons()
    {
        return $this->hasMany(MisiPaslon::class);
    }
    public function prokerPaslons()
    {
        return $this->hasMany(ProkerPaslon::class);
    }
    public function parpolPaslons()
    {
        return $this->hasMany(ParpolPaslon::class);
    }

    public function paslon()
    {
        return $this->belongsTo(Paslon::class, 'paslon_id');
    }

    public function pengalamanKerja()
    {
        return $this->hasMany(PengalamanKerja::class, 'tentang_paslon_id');
    }
}
