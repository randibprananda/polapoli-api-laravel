<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealCount extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kelurahan_id',
        'tps',
        'saksi_relawan_id',
        'keterangan',
        'suara_sah',
        'suara_tidak_sah',
        'foto_form',
        'partai_id',
        'suara_sah_partai',
        'suara_tidak_sah_partai',
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
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }
    public function saksiRelawan()
    {
        return $this->belongsTo(User::class, 'saksi_relawan_id');
    }

    function suaraPaslon()
    {
        return $this->hasMany(SuaraPaslonRealCount::class);
    }

    public function suaraCalonAnggota()
    {
        return $this->hasMany(SuaraCalonAnggotaRealCount::class);
    }

    public function partai()
    {
        return $this->belongsTo(Partai::class);
    }
    // function suaraPaslonSum()
    // {
    //     return SuaraPaslonRealCount::where('real_count_id', $this->id)->sum('suara_sah_paslon')->groupBy('paslon_id')->get();
    // }
}
