<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoLa extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'form_survey_id',
        'longitude_latitude',
        'nama_responden',
        'alamat',
        'user_id'
    ];

    public function lolaFormAnswers()
    {
        return $this->hasMany(FormAnswer::class);
    }

    public function formSurvey()
    {
        return $this->belongsTo(FormSurvey::class, 'form_survey_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
