<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareFeed extends Model
{
    use HasFactory;

    protected $fillable = ['id_user','id_feed', 'jml'];

    public function feed()
    {
        return $this->belongsTo(Feed::class, 'id_feed');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
