<?php

namespace App\Models;

use App\Traits\FormatDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DPT extends Model
{
    use HasFactory, FormatDates;
    protected $table = 'd_p_t';

    protected $fillable = [
        'propinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'kelurahan_id',
        'dapil',
        'nik',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'tps',
        'rt',
        'rw',
        'tim_relawan_id',
        'is_pendukung',
        'agama',
        'suku',
        'keterangan',
        'referal_relawan',
        'no_hp',
        'no_hp_lainnya',
        'email',
        'foto',
        'foto_ktp',
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
    public function user()
    {
        return $this->belongsTo(User::class, 'referal_relawan');
    }
}
