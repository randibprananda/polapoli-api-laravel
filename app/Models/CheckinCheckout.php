<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckinCheckout extends Model
{
    use HasFactory, FormatDates;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'checkin_at',
        'checkout_at',
        'date',
        'tim_relawan_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}