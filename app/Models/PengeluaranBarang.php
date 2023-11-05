<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranBarang extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'rt',
        'rw',
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kelurahan_id',
        'stok_barang_id',
        'jumlah_pengeluaran',
        'keterangan',
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kelurahan_id',
        'dapil'
    ];

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
