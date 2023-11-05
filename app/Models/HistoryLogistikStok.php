<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryLogistikStok extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'stok_barang_id',
        'keterangan',
        'stok_awal',
        'stok_akhir',
        'total_masuk',
        'total_keluar',
    ];
}