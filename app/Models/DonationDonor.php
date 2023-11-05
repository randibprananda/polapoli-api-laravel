<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationDonor extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'donation_id',
        'external_id',
        'payment_channel',
        'status',
        'amount',
        'email',
        'name'
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donation_id');
    }
}