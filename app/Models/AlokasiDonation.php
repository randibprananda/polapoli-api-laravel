<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlokasiDonation extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'donation_id',
        'bukti_alokasi',
        'nominal',
        'keterangan',
    ];
    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donation_id');
    }
}