<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTim extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'tim_relawan_id',
        'user_id',
        'jenis_paket',
        'tanggal_awal',
        'tanggal_akhir',
        'invoice_code',
        'payment_channel',
        'status',
        'amount',
    ];

    public function timRelawan()
    {
        return $this->belongsTo(TimRelawan::class, 'tim_relawan_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderTimAddon()
    {
        return $this->hasMany(OrderTimAddon::class);
    }
}
