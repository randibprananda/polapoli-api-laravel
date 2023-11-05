<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory, FormatDates;

    protected $fillable = [
        'user_one',
        'user_two',
    ];
    public function chats()
    {
        return $this->hasMany(Chat::class)->orderBy('created_at', 'asc');
    }
}