<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KindofIssue extends Model
{
    use HasFactory, FormatDates;

    protected $fillable = ['nama_jenis_isu'];

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}