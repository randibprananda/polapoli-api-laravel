<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickCount extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'tim_relawan_id',
        'metode_pengambilan',
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kelurahan_id',
        'tps',
        'nama_responden',
        'nik',
        'usia',
        'no_hp',
        'no_hp_lain',
        'keterangan',
        'relawan_id',
        'kandidat_calon_anggota_id',
        'kandidat_pilihan_id',
        'kandidat_partai_id',
        'bukti',
    ];

    public function relawan()
    {
        return $this->belongsTo(User::class, 'relawan_id');
    }
    public function kandidatPilihan()
    {
        return $this->belongsTo(Paslon::class, 'kandidat_pilihan_id');
    }
    public function kandidatPartai()
    {
        return $this->belongsTo(Partai::class, 'kandidat_partai_id');
    }

    public function kandidatCalonAnggota()
    {
        return $this->belongsTo(CalonAnggota::class, 'kandidat_calon_anggota_id');
    }

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
}
