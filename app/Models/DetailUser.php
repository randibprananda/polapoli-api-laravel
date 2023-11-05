<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DetailUser extends Model
{
    use HasFactory, FormatDates;
    use Notifiable;

    protected $fillable = [
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kelurahan_id',
        'no_hp',
        'keterangan',
        'user_id',
        'jenis_kelamin',
        'rt',
        'rw',
        'tps',
        'status_invitation',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function tingkatKoordinator()
    {
        return $this->hasOne(TingkatKoordinator::class);
    }

    public function daftarAnggota()
    {
        return $this->hasMany(DaftarAnggota::class);
    }
}