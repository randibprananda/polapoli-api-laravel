<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldForm extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'form_survey_id',
        'tipe',
        'label_inputan',
        'option'
    ];
    public function formSurvey()
    {
        return $this->belongsTo(FormSurvey::class, 'form_survey_id');
    }

    public function formAnswers()
    {
        return $this->hasMany(FormAnswer::class);
    }
}