<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSurvey extends Model
{
    use HasFactory, FormatDates;
    protected $fillable = [
        'tim_relawan_id',
        'created_by',
        'judul_survey',
        'tingkat_survei',
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kelurahan_id',
        'dapil',
        'target_responden',
        'pembukaan_survey',
        'penutupan_survey',
        'id_dpt',
        'id_issues',
        'status',
        'flag'
    ];

    public function timRelawan()
    {
        return $this->belongsTo(TimRelawan::class, 'tim_relawan_id');
    }
    public function propinsi()
    {
        return $this->belongsTo(Propinsi::class, 'propinsi_id');
    }
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function fieldForms()
    {
        return $this->hasMany(FieldForm::class);
    }
    public function lola()
    {
        return $this->hasMany(LoLa::class);
    }
    public function dpt()
    {
        return $this->belongsTo(DPT::class, 'id_dpt');
    }

    public function issue()
    {
        return $this->belongsTo(Issue::class, 'id_issues');
    }
}
