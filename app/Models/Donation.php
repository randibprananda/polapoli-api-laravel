<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'tim_relawan_id',
        'created_by',
        'donation_image',
        'donation_title',
        'donation_description',
        'is_target',
        'is_batas',
        'total_amount',
        'target_amount',
        'batas_akhir',
        'is_close',
        'fluktuatif_penarikan_amount',
        'fluktuatif_alokasi_amount',
    ];

    public function donationDonors()
    {
        return $this->hasMany(DonationDonor::class);
    }
    public function donationOuts()
    {
        return $this->hasMany(DonationOut::class);
    }
    public function alokasiDonations()
    {
        return $this->hasMany(AlokasiDonation::class);
    }

    public function timRelawan()
    {
        return $this->belongsTo(TimRelawan::class, 'tim_relawan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}