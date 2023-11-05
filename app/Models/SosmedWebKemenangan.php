<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SosmedWebKemenangan extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'paslon_id',
        'instagram',
        'url_instagram',
        'facebook',
        'url_facebook',
        'youtube',
        'url_youtube',
        'twitter',
        'url_twitter',
        'tiktok',
        'url_tiktok'
    ];

    public function paslon()
    {
        return $this->belongsTo(Paslon::class, 'paslon_id');
    }
}