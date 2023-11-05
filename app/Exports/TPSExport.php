<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TPSExport implements FromArray, ShouldAutoSize
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
            ['no', 'nama_kelurahan', 'jumlah_tps', 'keterangan_boleh_dikosongi', '', '', 'Informasi Wilayah',],
            ['1', 'GEDANG SEWU [kapital]', '12 [contoh]', 'ini keterangan [contoh]', '', '', 'Propinsi', $this->propinsi],
            ['', '', '', '', '', '', 'Kabupaten', $this->kabupaten],
            ['', '', '', '', '', '', 'Kecamatan', $this->kecamatan],
            ['', '', '', '', '', '', 'Dapil', $this->dapil]

        ];
    }
}
