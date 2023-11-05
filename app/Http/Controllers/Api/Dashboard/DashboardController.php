<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DPT;
use App\Models\FormSurvey;
use App\Models\JumlahDpt;
use App\Models\LoLa;
use App\Models\Paslon;
use App\Models\SuaraPaslonRealCount;
use App\Models\TimRelawan;
use App\Models\TimRole;
use App\Models\TPS;
use App\Models\User;
use App\Models\IvesModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function listPaslonDashboard()
    {
        $paslons = Paslon::where('tim_relawan_id', Auth::user()->current_team_id)->orderBy('nomor_urut', 'asc')->get();

        return $paslons;
    }

    public function jumlahDptDashboard()
    {
        $jumlahDPT = JumlahDpt::where('tim_relawan_id', Auth::user()->current_team_id)->selectRaw('sum(laki_laki + perempuan) as total_jumlah_dpt')->get();

        return $jumlahDPT;
    }
    public function jumlahTpsDashboard()
    {
        $totalTPS = TPS::where('tim_relawan_id', Auth::user()->current_team_id)->selectRaw('sum(jumlah_tps) as total_tps')->get();

        return $totalTPS;
    }

    public function jumlahPendukungDashboard()
    {
        $pendukungs = DPT::where([['tim_relawan_id', Auth::user()->current_team_id], ['is_pendukung', 1]])->count();

        return $pendukungs;
    }

    public function jumlahRelawanDashboard()
    {
        $relawans = User::whereHas("userRoleTim", function ($q) {
            $q->whereIn("role_id", [4]);
        })->whereHas("timRelawans", function ($r) {
            $r->whereIn("tim_relawan_id", [Auth::user()->current_team_id]);
        })->count();

        return $relawans;
    }

    public function jumlahSaksiDashboard()
    {
        $relawans = User::whereHas("userRoleTim", function ($q) {
            $q->whereIn("role_id", [5]);
        })->whereHas("timRelawans", function ($r) {
            $r->whereIn("tim_relawan_id", [Auth::user()->current_team_id]);
        })->count();

        return $relawans;
    }

    public function jumlahSurvei()
    {
        $surveis = FormSurvey::where([
            ['tim_relawan_id', Auth::user()->current_team_id],
            ['status', 'publish']
        ])->get();
        // Provinsi,Kota/Kab,Kecamatan,Kelurahan,Dapil
        $counting = 0;

        $user = User::with('detailUser', 'timRelawans')
            ->whereHas("timRelawans", function ($p) {
                $p->where("tim_relawan_id", '=', [Auth::user()->current_team_id]);
            })->find(Auth::user()->id);

        for ($i = 0; $i < count($surveis); $i++) {
            if ($surveis[$i]->tingkat_survei == 'Provinsi') {
                $propinsiUser = $user->detailUser->propinsi_id;
                if ($propinsiUser == $surveis[$i]->propinsi_id) {
                    $counting += 1;
                }
            } else if ($surveis[$i]->tingkat_survei == 'Kota/Kab') {
                $kabUser = $user->detailUser->kabupaten_id;
                if ($kabUser == $surveis[$i]->kabupaten_id) {
                    $counting += 1;
                }
            } elseif ($surveis[$i]->tingkat_survei == 'Kecamatan') {
                $kecUser = $user->detailUser->kecamatan_id;
                if ($kecUser == $surveis[$i]->kecamatan_id) {
                    $counting += 1;
                }
            } elseif ($surveis[$i]->tingkat_survei == 'Kelurahan') {
                $kelUser = $user->detailUser->kelurahan_id;
                if ($kelUser == $surveis[$i]->kelurahan_id) {
                    $counting += 1;
                }
            } elseif ($surveis[$i]->tingkat_survei == 'Dapil') {
                $dapilUser = $user->detailUser->kabupaten_id;
                if ($dapilUser == $surveis[$i]->kabupaten_id) {
                    $counting += 1;
                }
            } else {
                return 0;
            }
        }
        return $counting;
    }

    public function totalDonasiDashboard()
    {
        $totalDonasi = Donation::where("tim_relawan_id", Auth::user()->current_team_id)->selectRaw('sum(total_amount) as total_donasi')->get();

        return $totalDonasi;
    }

    public function hasilQuickCount()
    {
        $quickCount  = Paslon::where([
            ['tim_relawan_id', Auth::user()->current_team_id],
        ])->withCount('quickCounts as total_quick_count')->get();

        return $quickCount;
    }
    public function hasilRealCount()
    {
        $realCount  = Paslon::where([
            ['tim_relawan_id', Auth::user()->current_team_id],
        ])->addSelect([
            'suara_paslon' => SuaraPaslonRealCount::selectRaw('CONVERT(ifnull(sum(suara_sah_paslon), 0), SIGNED) as total')
                ->whereColumn('paslon_id', 'paslons.id')
        ])->get();

        return $realCount;
    }

    public static function tanggalPemilihan()
    {
        $timrelawan = TimRelawan::where('id', Auth::user()->current_team_id)->get();
        return $timrelawan;
    }

    public static function gaji()
    {

        $userRoles = Auth::user()->userRoleTim;
        $roleId = $userRoles->pluck('role_id')->toArray();

        $gaji = TimRole::where('tim_relawan_id', Auth::user()->current_team_id)
            ->whereIn('role_id', $roleId)->get();

        $rangeMonth = date_diff(now(), Auth::user()->created_at)->m;
        $totalKunjungan = DashboardController::totalKunjunganRelawan();
        $totalGaji = 0;

        for ($i = 0; $i < count($gaji); $i++) {
            if ($gaji[$i]->metode_gaji == '1x Gaji') {
                $totalGaji += $gaji[$i]->gaji;
            } else if ($gaji[$i]->metode_gaji == 'Per Bulan') {
                $totalGaji += ($gaji[$i]->gaji) * $rangeMonth;
            } else if ($gaji[$i]->metode_gaji == 'Per Kunjungan/Survei') {
                $totalGaji += ($gaji[$i]->gaji) * $totalKunjungan;
            }
        }

        return $totalGaji;
    }

    public static function totalKunjunganRelawanHariIni()
    {
        $totalKunjungan = LoLa::where([
            ['user_id', '=', Auth::user()->id],
        ])->whereDate('created_at', Carbon::today())->whereHas("formSurvey.timRelawan", function ($q) {
            $q->whereIn("id", [Auth::user()->current_team_id]);
        })->count();
        return $totalKunjungan;
    }

    public static function totalKunjunganRelawan()
    {
        $totalKunjungan = LoLa::where('user_id', '=', Auth::user()->id)->whereHas("formSurvey.timRelawan", function ($q) {
            $q->whereIn("id", [Auth::user()->current_team_id]);
        })->count();
        return $totalKunjungan;
    }

    public static function timChecker()
    {
        $timRelawan = TimRelawan::with('orderTim')->find(Auth::user()->current_team_id);
        if ($timRelawan->is_premium == 1) {
            $endTime = $timRelawan->orderTim->first()->tanggal_akhir;
            if (strtotime(now()) > strtotime($endTime)) {
                $timRelawan->is_premium = 0;
                $timRelawan->save();
            }
            return true;
        }
        return true;
    }

    public function donasiChecker()
    {
        $donasi = Donation::where([
            ['tim_relawan_id', Auth::user()->current_team_id],
            ['is_batas', '=', '1'],
            ['is_close', '=', '0'],
        ])->get();


        $timeNow = now()->format('d-m-Y');
        for ($i = 0; $i < count($donasi); $i++) {
            if (strtotime($donasi[$i]->batas_akhir) < strtotime($timeNow)) {
                $donasi[$i]->forceFill([
                    'is_close' =>  1,
                ])->save();
            }
        }
        return true;
    }

    public function dashboardUser()
    {
        if (Auth::user()->hasRoleTim(1, Auth::user()->current_team_id)) {
            // DashboardController::timChecker();
            DashboardController::donasiChecker();
            $paslons = DashboardController::listPaslonDashboard();
            $jumlahDPT = DashboardController::jumlahDptDashboard();
            $totalTPS = DashboardController::jumlahTpsDashboard();
            $pendukungs = DashboardController::jumlahPendukungDashboard();
            $relawans = DashboardController::jumlahRelawanDashboard();
            $totalDonasi = DashboardController::totalDonasiDashboard();
            $quickCount  = DashboardController::hasilQuickCount();
            $realCount = DashboardController::hasilRealCount();
            $tanggalpemilihan = DashboardController::tanggalPemilihan();

            return response()->json([
                'message' => 'Tanggal Pemilihan',
                'data' => [
                    'id_role' => 1,
                    'paslons' => $paslons,
                    'jumlahDPT' => $jumlahDPT,
                    'totalTPS' => $totalTPS,
                    'pendukungs' => $pendukungs,
                    'relawans' => $relawans,
                    'totalDonasi' => $totalDonasi,
                    'quickCount' => $quickCount,
                    'realCount' => $realCount,
                    'tanggalpemilihan' => $tanggalpemilihan,
                ],
            ], Response::HTTP_OK);
        } else if (Auth::user()->hasRoleTim(2, Auth::user()->current_team_id)) {
            // DashboardController::timChecker();
            DashboardController::donasiChecker();
            $paslons = DashboardController::listPaslonDashboard();
            $jumlahDPT = DashboardController::jumlahDptDashboard();
            $totalTPS = DashboardController::jumlahTpsDashboard();
            $pendukungs = DashboardController::jumlahPendukungDashboard();
            $relawans = DashboardController::jumlahRelawanDashboard();
            $totalDonasi = DashboardController::totalDonasiDashboard();
            $quickCount  = DashboardController::hasilQuickCount();
            $realCount = DashboardController::hasilRealCount();
            $tanggalpemilihan = DashboardController::tanggalPemilihan();

            return response()->json([
                'message' => 'Tanggal Pemilihan',
                'data' => [
                    'id_role' => 2,
                    'paslons' => $paslons,
                    'jumlahDPT' => $jumlahDPT,
                    'totalTPS' => $totalTPS,
                    'pendukungs' => $pendukungs,
                    'relawans' => $relawans,
                    'totalDonasi' => $totalDonasi,
                    'quickCount' => $quickCount,
                    'realCount' => $realCount,
                    'tanggalpemilihan' => $tanggalpemilihan,
                ],
            ], Response::HTTP_OK);
        } else if (Auth::user()->hasRoleTim(3, Auth::user()->current_team_id)) {
            // DashboardController::timChecker();
            DashboardController::donasiChecker();
            $relawans = DashboardController::jumlahRelawanDashboard();
            $saksis = DashboardController::jumlahSaksiDashboard();
            $surveis = DashboardController::jumlahSurvei();
            $quickCount  = DashboardController::hasilQuickCount();
            $realCount = DashboardController::hasilRealCount();
            $tanggalpemilihan = DashboardController::tanggalPemilihan();
            $gaji = DashboardController::gaji();

            return response()->json([
                'message' => 'Tanggal Pemilihan',
                'data' => [
                    'id_role' => 3,
                    'jumlah_relawans' => $relawans,
                    'jumlah_surveis' => $surveis,
                    'jumlah_saksis' => $saksis,
                    'quickCount' => $quickCount,
                    'realCount' => $realCount,
                    'tanggalpemilihan' => $tanggalpemilihan,
                    'gaji' => $gaji,
                ],
            ], Response::HTTP_OK);
        } else if (Auth::user()->hasRoleTim(4, Auth::user()->current_team_id)) {
            // DashboardController::timChecker();
            DashboardController::donasiChecker();
            $totalKunjungan = DashboardController::totalKunjunganRelawan();
            $totalKunjunganHariIni = DashboardController::totalKunjunganRelawanHariIni();
            $surveis = DashboardController::jumlahSurvei();
            $quickCount  = DashboardController::hasilQuickCount();
            $realCount = DashboardController::hasilRealCount();
            $tanggalpemilihan = DashboardController::tanggalPemilihan();
            $gaji = DashboardController::gaji();

            return response()->json([
                'message' => 'Tanggal Pemilihan',
                'data' => [
                    'id_role' => 4,
                    'total_kunjungan' => $totalKunjungan,
                    'total_kunjungan_hari_ini' => $totalKunjunganHariIni,
                    'jumlah_surveis' => $surveis,
                    'quickCount' => $quickCount,
                    'realCount' => $realCount,
                    'tanggalpemilihan' => $tanggalpemilihan,
                    'gaji' => $gaji,
                ],
            ], Response::HTTP_OK);
        } else if (Auth::user()->hasRoleTim(5, Auth::user()->current_team_id)) {
            // DashboardController::timChecker();
            DashboardController::donasiChecker();
            $quickCount  = DashboardController::hasilQuickCount();
            $realCount = DashboardController::hasilRealCount();
            $tanggalpemilihan = DashboardController::tanggalPemilihan();
            $gaji = DashboardController::gaji();

            return response()->json([
                'message' => 'Tanggal Pemilihan',
                'data' => [
                    'id_role' => 5,
                    'quickCount' => $quickCount,
                    'realCount' => $realCount,
                    'tanggalpemilihan' => $tanggalpemilihan,
                    'gaji' => $gaji,
                ],
            ], Response::HTTP_OK);
        } else {
            // DashboardController::timChecker();
            DashboardController::donasiChecker();
            $getRole = Auth::user()->userRoleTim->first()->role_id;
            $quickCount  = DashboardController::hasilQuickCount();
            $realCount = DashboardController::hasilRealCount();
            $tanggalpemilihan = DashboardController::tanggalPemilihan();
            $gaji = DashboardController::gaji($getRole);

            return response()->json([
                'message' => 'Tanggal Pemilihan',
                'data' => [
                    'quickCount' => $quickCount,
                    'realCount' => $realCount,
                    'tanggalpemilihan' => $tanggalpemilihan,
                ],
            ], Response::HTTP_OK);
        }
    }

    public function submitRegistIves(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string',
            'hp' => 'required|string',
            'company' => 'required|string',
            'job_level' => 'required|string',
            'job_title' => 'required|string',
            'email' => 'required|string',
            'city' => 'required|string',
            'industry' => 'required|string'

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {

            $dataSponsorIves = IvesModel::create(
                [
                    'fullname' => $request->fullname,
                    'hp' => $request->hp,
                    'company' => $request->company,
                    'job_level' => $request->job_level,
                    'job_title' => $request->job_title,
                    'email' => $request->email,
                    'city' => $request->city,
                    'industry' => $request->industry,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            );

            return response()->json([
                'message' => 'Submit data berhasil',
                'data' => $dataSponsorIves
            ], Response::HTTP_OK);
        } catch (Exception $e) {
        }
    }
}
