<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partai extends Model
{
    use HasFactory, FormatDates;
    protected $table = 'partai';
    protected $fillable = [
       'nama_partai',
       'logo',
       'status',
       'tim_relawan_id'
    ];
    public function timRelawan()
    {
        return $this->belongsTo(TimRelawan::class, 'tim_relawan_id');
    }

}
