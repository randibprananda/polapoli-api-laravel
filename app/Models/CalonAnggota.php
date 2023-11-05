<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonAnggota extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'id_partai',
        'jenis_pencalonan',
        'foto',
        'no_urut',
        'nama_calon',
        'is_usung',
        'tim_relawan_id'
    ];

    public function partai()
    {
        return $this->belongsTo(Partai::class, 'id_partai');
    }
    public function timRelawan()
    {
        return $this->belongsTo(TimRelawan::class, 'tim_relawan_id');
    }
}
