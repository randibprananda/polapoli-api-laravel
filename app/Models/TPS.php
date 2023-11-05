<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TPS extends Model
{
    use HasFactory, FormatDates;
    protected $table = 't_p_s';

    protected $fillable = [
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'dapil',
        'kelurahan',
        'jumlah_tps',
        'keterangan',
        'tim_relawan_id'
    ];

    public function timRelawan()
    {
        return $this->belongsTo(TimRelawan::class, 'tim_relawan_id');
    }
    public function propinsi()
    {
        return $this->belongsTo(Propinsi::class, 'propinsi_id');
    }
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }
}