<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatDates;

class ChatV2 extends Model
{
    use HasFactory,FormatDates;
    protected $table = 'chat_v2_s';
    protected $fillable = ['tim_relawan_id', 'user_one', 'user_two', 'is_read', 'body'];

    public function tim_relawan()
    {
        return $this->belongsTo(TimRelawan::class, 'tim_relawan_id');
    }
    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two');
    }

}
