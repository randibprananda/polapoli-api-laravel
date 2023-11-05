<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormAnswer extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'form_survey_id',
        'field_form_id',
        'lo_la_id',
        'user_id',
        'jawaban',
    ];

    public function formSurvey()
    {
        return $this->belongsTo(FormSurvey::class, 'form_survey_id');
    }

    public function lola()
    {
        return $this->belongsTo(LoLa::class, 'lo_la_id');
    }

    public function fieldForm()
    {
        return $this->belongsTo(FieldForm::class, 'field_form_id');
    }
}