<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationOut extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'donation_id',
        'user_id',
        'tim_relawan_id',
        'external_id',
        'status',
        'amount',
        'account_number',
        'account_name',
        'bank_code',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donation_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function tim_relawan()
    {
        return $this->belongsTo(User::class, 'tim_relawan_id');
    }
}