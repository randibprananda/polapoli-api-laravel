<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paslon extends Model
{
    use HasFactory, FormatDates;
    protected $table = 'paslons';
    protected $fillable = [
        'tim_relawan_id',
        'jenis_pencalonan',
        'nomor_urut',
        'nama_paslon',
        'nama_wakil_paslon',
        'is_usung',
        'paslon_profile_photo'
    ];

    public function tentangPaslon()
    {
        return $this->hasOne(TentangPaslon::class);
    }

    public function contactWebKemenangan()
    {
        return $this->hasOne(ContactWebKemenangan::class);
    }

    public function sosmedWebKemenangan()
    {
        return $this->hasOne(SosmedWebKemenangan::class);
    }

    public function timRelawan()
    {
        return $this->belongsTo(TimRelawan::class, 'tim_relawan_id');
    }

    public function quickCounts()
    {
        return $this->hasMany(QuickCount::class, 'kandidat_pilihan_id');
    }

    public function suaraRealPaslon()
    {
        return $this->hasMany(SuaraPaslonRealCount::class, 'paslon_id');
    }

    public function galeriPaslon()
    {
        return $this->hasMany(GaleriPaslon::class);
    }
}
