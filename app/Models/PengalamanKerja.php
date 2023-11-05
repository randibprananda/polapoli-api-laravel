<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengalamanKerja extends Model
{
    use HasFactory, FormatDates;

    protected $table = 'pengalaman_kerjas';
    protected $fillable = [
        'tentang_paslon_id',
        'name',
    ];

    public function tentangPaslon()
    {
        return $this->belongsTo(TentangPaslon::class, 'tentang_paslon_id');
    }

    public function detail_pengalaman()
    {
        return $this->hasMany(DetailPengalamanKerja::class, 'id_pengalaman_kerja');
    }
}
