<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    use HasFactory, FormatDates;
    protected $table = "stok_barangs";
    protected $fillable = [
        'rt',
        'rw',
        'nama_barang',
        'harga_satuan',
        'nama_satuan',
        'stok_awal',
        'stok_akhir',
        'tim_relawan_id',
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'stok_barang_id',
        'kelurahan_id',
        'keterangan',
        'dapil'
    ];

    public function pemesananBarangs()
    {
        return $this->hasMany(PemesananBarang::class);
    }
    public function historyLogistikStok()
    {
        return $this->hasMany(HistoryLogistikStok::class);
    }
    public function penerimaanBarangs()
    {
        return $this->hasMany(PenerimaanBarang::class);
    }
    public function pengeluaranBarang()
    {
        return $this->hasMany(PengeluaranBarang::class);
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
