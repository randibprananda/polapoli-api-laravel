<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DptExport implements FromArray, ShouldAutoSize
{
    private $propinsi;
    private $kabupaten;
    private $kecamatan;
    private $kelurahan;
    private $dapil;
    public function __construct($propinsi, $kabupaten, $kecamatan, $kelurahan,$dapil)
    {
        $this->propinsi = $propinsi;
        $this->kabupaten = $kabupaten;
        $this->kecamatan = $kecamatan;
        $this->kelurahan = $kelurahan;
        $this->dapil = $dapil;
        return;
    }
    public function array(): array
    {
        return [
            ['no', 'nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'tps', 'rt', 'rw', '', '', 'Informasi Wilayah'],
            ['1', '3527483726478882 [contoh]', 'Michael Sri Rokimin [contoh]', 'Malang [contoh]', '2000-11-29 [contoh]', 'L [masukkan L / P]', 'Jl. Bagus no.123 [contoh]', '03 [contoh]', '012 [contoh]', '04 [contoh]', '', '', 'Provinsi :', $this->propinsi],
            ['', '', '', '', '', '', '', '', '', '', '', '', 'Kabupaten :', $this->kabupaten],
            ['', '', '', '', '', '', '', '', '', '', '', '', 'Kecamatan :', $this->kecamatan],
            ['', '', '', '', '', '', '', '', '', '', '', '', 'Kelurahan :', $this->kelurahan],
            ['', '', '', '', '', '', '', '', '', '', '', '', 'Dapil :', $this->dapil]

        ];
    }
}
