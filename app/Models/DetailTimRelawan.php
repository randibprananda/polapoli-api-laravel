<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTimRelawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pm',
        'tim_relawan_id'
    ];
    public function pm()
    {
        return $this->belongsTo(User::class, 'pm');
    }
    public function timRelawan()
    {
        return $this->belongsTo(TimRelawan::class, 'tim_relawan_id');
    }
}