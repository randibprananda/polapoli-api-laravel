<?php

namespace App\Imports;

use App\Models\DPT;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class DPTImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $propinsi_id;
    private $kabupaten_id;
    private $kecamatan_id;
    private $kelurahan_id;
    private $dapil;

    public function __construct($propinsi_id, $kabupaten_id, $kecamatan_id, $kelurahan_id,$dapil)
    {
        $this->propinsi_id = $propinsi_id;
        $this->kabupaten_id = $kabupaten_id;
        $this->kecamatan_id = $kecamatan_id;
        $this->kelurahan_id = $kelurahan_id;
        $this->dapil = $dapil;
        return;
    }

    public function collection(Collection $rows)
    {
        Validator::make($rows->toArray(), [

            '*.nik' => 'required|min:16|max:16|unique:d_p_t',
            '*.nama_lengkap' => 'required|string|max:255',
            '*.tempat_lahir' => 'required|max:255',
            '*.tanggal_lahir' => 'required',
            '*.jenis_kelamin' => 'in:L,P|required',
            '*.alamat' => 'required|string|max:255',
            '*.tps' => 'required|numeric',
            '*.rt' => 'required|numeric',
            '*.rw' => 'required|numeric',

        ])->validate();

        foreach ($rows as $row) {
            $currentTeamId = Auth::user()->current_team_id;
            DPT::create([
                'tim_relawan_id' => $currentTeamId,
                'propinsi_id' => $this->propinsi_id,
                'kabupaten_id' => $this->kabupaten_id,
                'kecamatan_id' => $this->kecamatan_id,
                'kelurahan_id' => $this->kelurahan_id,
                'dapil' => $this->dapil,
                'nama' => $row["nama_lengkap"],
                'nik' => $row["nik"],
                'tempat_lahir' => $row["tempat_lahir"],
                'tanggal_lahir' => $row['tanggal_lahir'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'alamat' => $row['alamat'],
                'tps' => $row['tps'],
                'rt' => $row['rt'],
                'rw' => $row['rw'],
            ]);
        }
    }
}
