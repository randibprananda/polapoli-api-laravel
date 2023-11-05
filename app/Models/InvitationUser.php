<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class InvitationUser extends Model
{
    use HasFactory, FormatDates;
    use Notifiable;
    protected $fillable = [
        'email',
        'token',
        'role',
        'user_id',
    ];
}