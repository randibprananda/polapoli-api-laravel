<?php

namespace App\Imports;

use App\Models\TPS;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TPSImport implements ToModel, WithHeadingRow
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
        return new TPS([
            'tim_relawan_id' => $this->findCurrentTeam,
            'propinsi_id' => $this->propinsi,
            'kabupaten_id' => $this->kabupaten,
            'kecamatan_id' => $this->kecamatan,
            'dapil' => $this->dapil,
            'kelurahan' => $row["nama_kelurahan"],
            'jumlah_tps' => $row["jumlah_tps"],
            'keterangan' => $row["keterangan_boleh_dikosongi"],
        ]);
    }
}
