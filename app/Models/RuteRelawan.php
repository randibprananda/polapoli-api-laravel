<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuteRelawan extends Model
{
    use HasFactory, FormatDates;
    protected $table = 'rute_relawan';

    protected $fillable = [
        'jenis_survey',
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kelurahan_id',
        'rt',
        'rw',
        'user_id',
        'tim_relawan_id',
        'jadwal_kunjungan',
        'keterangan'
    ];

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
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function timRelawan()
    {
        return $this->belongsTo(TimRelawan::class, 'tim_relawan_id');
    }
}
