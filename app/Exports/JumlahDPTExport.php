<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class JumlahDPTExport implements FromArray, ShouldAutoSize
{
    private $propinsi;
    private $kabupaten;
    private $kecamatan;
    private $dapil;
    public function __construct($propinsi, $kabupaten, $kecamatan,$dapil)
    {
        $this->propinsi = $propinsi;
        $this->kabupaten = $kabupaten;
        $this->kecamatan = $kecamatan;
        $this->dapil = $dapil;
        return;
    }
    public function array(): array
    {
        return [
            ['no', 'nama_kelurahan', 'laki_laki', 'perempuan', '', '', 'Informasi Wilayah',],
            ['', '', '', '', '', '', 'Propinsi', $this->propinsi],
            ['', '', '', '', '', '', 'Kabupaten', $this->kabupaten],
            ['', '', '', '', '', '', 'Kecamatan', $this->kecamatan],
            ['', '', '', '', '', '', 'Dapil', $this->dapil],

        ];
    }
}
