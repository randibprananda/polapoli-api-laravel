<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IvesContactModel extends Model
{
    use HasFactory, FormatDates;
    protected $table = 'ives_contact';
    public $timestamps = false;

    protected $fillable = [
      'nama',
      'email',
      'message',
      'created_at',
      'updated_at'
    ];

   
}