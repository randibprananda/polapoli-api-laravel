<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBarang extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'rt',
        'rw',
        'stok_barang_id',
        'pemesanan_barang_id',
        'jumlah_diterima',
        'keterangan',
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kelurahan_id',
        'dapil'
    ];

    public function pemesananBarang()
    {
        return $this->belongsTo(PemesananBarang::class, 'pemesanan_barang_id');
    }

    public function stokBarang()
    {
        return $this->belongsTo(StokBarang::class, 'stok_barang_id');
    }

    public function getTotalMasuk()
    {
        return $this->tasks->sum('jumlah_diterima');
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
