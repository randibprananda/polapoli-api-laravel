<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananBarang extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'rt',
        'rw',
        'stok_barang_id',
        'jumlah_pesanan',
        'jumlah_diterima',
        'sisa_pesanan',
        'estimasi_harga_total',
        'keterangan',
        'is_complete',
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kelurahan_id',
        'dapil'
    ];

    public function penerimaanBarangs()
    {
        return $this->hasMany(PenerimaanBarang::class);
    }

    public function stokBarang()
    {
        return $this->belongsTo(StokBarang::class, 'stok_barang_id');
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
