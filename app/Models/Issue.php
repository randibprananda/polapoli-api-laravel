<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        "jenis_isu_id",
        "dampak_isu",
        "tanggal_isu",
        "keterangan_isu",
        "nama_pelapor",
        "judul_isu",
        "url_isu",
        "foto_isu",
        "propinsi_id",
        "kabupaten_id",
        "kecamatan_id",
        "kelurahan_id",
        "dapil",
        'tanggapan_isu',
        'ditanggapi_pada',
        'is_abaikan',
        'tim_relawan_id',
    ];

    public function kindOfIssue()
    {
        return $this->belongsTo(KindofIssue::class, 'jenis_isu_id', 'id');
    }

    public function propinsi()
    {
        return $this->belongsTo(Propinsi::class, 'propinsi_id', 'id');
    }
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id', 'id');
    }
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id', 'id');
    }
}