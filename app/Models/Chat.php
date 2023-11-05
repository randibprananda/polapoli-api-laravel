<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'user_id', 'tim_relawan_id', 'body', 'conversation_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function tim_relawan()
    {
        return $this->belongsTo(TimRelawan::class, 'tim_relawan_id');
    }
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }
}