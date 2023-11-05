<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IvesModel extends Model
{
    use HasFactory, FormatDates;
    protected $table = 'ives_sponsor';
    public $timestamps = false;

    protected $fillable = [
      'fullname',
      'hp',
      'company',
      'job_level',
      'job_title',
      'email',
      'city',
      'industry',
      'created_at',
      'updated_at'
    ];

   
}