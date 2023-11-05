<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactWebKemenangan extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'paslon_id',
        'alamat',
        'email',
        'telepon',
        'whatsapp'
    ];

    public function paslon()
    {
        return $this->belongsTo(Paslon::class, 'paslon_id');
    }
}