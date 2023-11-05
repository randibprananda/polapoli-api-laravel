<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengalamanKerja extends Model
{
    use HasFactory, FormatDates;

    protected $table = 'detail_pengalaman_kerjas';
    protected $fillable = [
        'id_pengalaman_kerja',
        'description',
        'start',
        'end',
    ];

    public function pengalaman()
    {
        return $this->belongsTo(PengalamanKerja::class);
    }
}
