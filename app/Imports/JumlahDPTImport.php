<?php

namespace App\Imports;

use App\Models\JumlahDpt;
use App\Models\Kelurahan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JumlahDPTImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function __construct($propinsi, $kabupaten, $kecamatan, $findCurrentTeam,$dapil)
    {
        $this->propinsi = $propinsi;
        $this->kabupaten = $kabupaten;
        $this->kecamatan = $kecamatan;
        $this->dapil = $dapil;
        $this->findCurrentTeam = $findCurrentTeam;
        return;
    }
    public function model(array $row)
    {
        return new JumlahDpt([
            'tim_relawan_id' => $this->findCurrentTeam,
            'propinsi_id' => $this->propinsi,
            'kabupaten_id' => $this->kabupaten,
            'kecamatan_id' => $this->kecamatan,
            'kelurahan_id' => Kelurahan::where('name', strtoupper($row["nama_kelurahan"]))->first()->id,
            'dapil' => $this->dapil,
            'laki_laki' => $row["laki_laki"],
            'perempuan' => $row["perempuan"],
        ]);
    }
}