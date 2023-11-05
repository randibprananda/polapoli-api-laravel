<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory, FormatDates;

    protected $fillable = [
        'tim_relawan_id',
        'judul_feed',
        'isi',
        'foto_feed'
    ];

    public function timRelawan()
    {
        return $this->belongsTo(TimRelawan::class, 'tim_relawan_id');
    }

    public function shareFeed()
    {
        return $this->hasMany(ShareFeed::class, 'id_feed', 'id');
    }
}
