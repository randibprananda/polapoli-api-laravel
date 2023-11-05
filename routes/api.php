<?php

use App\Http\Controllers\Api\Anggota\CalonAnggotaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CMS\OrderController;
use App\Http\Controllers\Api\CMS\TimController;
use App\Http\Controllers\Api\CMS\WithdrawalController;
use App\Http\Controllers\Api\Count\QuickCountController;
use App\Http\Controllers\Api\Count\RealCountController;
use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\Api\Donation\AlokasiDonationController;
use App\Http\Controllers\Api\Donation\CallbackController;
use App\Http\Controllers\Api\Donation\DonationController;
use App\Http\Controllers\Api\Donation\DonationDonorController;
use App\Http\Controllers\Api\Donation\DonationOutController;
use App\Http\Controllers\Api\DPT\DPTController;
use App\Http\Controllers\Api\DPT\FileExportDPTController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Api\User\UpdateProfileController;
use App\Http\Controllers\Api\DPT\FileImportController as DPTImport;
use App\Http\Controllers\Api\Educate\AnswerEducateController;
use App\Http\Controllers\Api\Educate\EducateController;
use App\Http\Controllers\Api\Educate\FieldEducateController;
use App\Http\Controllers\Api\Feed\CRUDFeedController;
use App\Http\Controllers\Api\Feed\ShareFeedController;
use App\Http\Controllers\Api\Issue\IssueController;
use App\Http\Controllers\Api\Issue\ResponseIssueController;
use App\Http\Controllers\Api\JumlahDPT\FileExportJumlahDPTController;
use App\Http\Controllers\Api\JumlahDPT\FileImportJumlahDPTController;
use App\Http\Controllers\Api\JumlahDPT\JumlahDPTController;
use App\Http\Controllers\Api\Landing\WebKemenanganController as LandingWebKemenanganController;
use App\Http\Controllers\Api\Logistik\PemesananBarangController;
use App\Http\Controllers\Api\Logistik\PenerimaanBarangController;
use App\Http\Controllers\Api\Logistik\PengeluaranBarangController;
use App\Http\Controllers\Api\Logistik\StokBarangController;
use App\Http\Controllers\Api\Mobile\Chat\ChatUserController;
use App\Http\Controllers\Api\Mobile\Chat\ChatV2Controller;
use App\Http\Controllers\Api\Mobile\Chat\ConversationController;
use App\Http\Controllers\Api\Mobile\Chat\ListChatUserController;
use App\Http\Controllers\Api\Mobile\Chat\MeController;
use App\Http\Controllers\Api\Mobile\KoordinatorFindWilayahController;
use App\Http\Controllers\Api\Order\OrderTimController;
use App\Http\Controllers\Api\Paslon\ContactWebKemenanganController;
use App\Http\Controllers\Api\Paslon\GaleriPaslonController;
use App\Http\Controllers\Api\Paslon\PaslonController;
use App\Http\Controllers\Api\Paslon\SosmedWebKemenanganController;
use App\Http\Controllers\Api\Paslon\WebKemenanganController;
use App\Http\Controllers\Api\Pendukung\PendukungController;
use App\Http\Controllers\Api\Rekapitulasi\RekapitulasiDataTpsController;
use App\Http\Controllers\Api\Rekapitulasi\RekapitulasiKoordinatorController;
use App\Http\Controllers\Api\Rekapitulasi\RekapitulasiPemilihTetapController;
use App\Http\Controllers\Api\Rekapitulasi\RekapitulasiPemilihTetapManualController;
use App\Http\Controllers\Api\Rekapitulasi\RekapitulasiPendukungController;
use App\Http\Controllers\Api\Rekapitulasi\RekapitulasiRelawanController;
use App\Http\Controllers\Api\Rekapitulasi\RekapitulasiSaksiController;
use App\Http\Controllers\Api\Relawan\TimRelawanController;
use App\Http\Controllers\Api\SimulasiKemenangan\SimulasiTargetKemenanganController;
use App\Http\Controllers\Api\Survey\AnswerSurveyController;
use App\Http\Controllers\Api\Survey\FieldSurveyController;
use App\Http\Controllers\Api\Survey\SurveyController;
use App\Http\Controllers\Api\TPS\FileImportController as TPSImport;
use App\Http\Controllers\Api\TPS\FileExportController as TPSExport;
use App\Http\Controllers\Api\TPS\TPSController;
use App\Http\Controllers\Api\User\CheckinController;
use App\Http\Controllers\Api\User\CRUDUserController;
use App\Http\Controllers\Api\User\Konsultan\KonsultanController;
use App\Http\Controllers\Api\User\Koordinator\KoordinatorController;
use App\Http\Controllers\Api\User\PermissionController;
use App\Http\Controllers\Api\User\Relawan\RelawanController;
use App\Http\Controllers\Api\User\ResetPassowrdCurrentUserController;
use App\Http\Controllers\Api\User\RoleController;
use App\Http\Controllers\Api\User\Saksi\SaksiController;
use App\Http\Controllers\Api\User\ShowProfileController;
use App\Http\Controllers\Api\Wilayah\WilayahController;
use App\Http\Controllers\Api\Partai\PartaiController;
use App\Http\Controllers\Api\PengalamanKerja\PengalamanKerjaController;
use App\Http\Controllers\Api\RuteHarian\RuteHarianController;
use App\Http\Controllers\Api\RuteRelawan\RuteRelawanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('submit-sponsor-ives', [AuthController::class, 'submitRegistIves']);
    Route::post('submit-contact-ives', [AuthController::class, 'submitContact']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);
        Route::get('verify-email', [EmailVerificationController::class, 'verify'])->name('verification.verify');

        Route::middleware(['verified','cors'])->group(function () {

            Route::get('wilayah/propinsi', [WilayahController::class, 'propinsi']);

            Route::get('wilayah/propinsi/detail/{id_propinsi}', [WilayahController::class, 'findPropinsi']);
            Route::get('wilayah/kabupaten/{id_propinsi}', [WilayahController::class, 'findKabbyPropinsi']);

            Route::get('wilayah/kabupaten/detail/{id_kabupaten}', [WilayahController::class, 'findKabupaten']);
            Route::get('wilayah/kecamatan/{id_kabupaten}', [WilayahController::class, 'findKecbyKab']);

            Route::get('wilayah/kecamatan/detail/{id_kecamatan}', [WilayahController::class, 'findKecamatan']);
            Route::get('wilayah/kelurahan/{id_kecamatan}', [WilayahController::class, 'findKelbyKec']);
            Route::get('wilayah/kelurahan/detail/{id_kelurahan}', [WilayahController::class, 'findKelurahan']);
            // Project Manager

            // Role
            Route::resource('role', RoleController::class)->except(['edit', 'create']);

            // Permission
            Route::get('permission', [PermissionController::class, 'index']);
            Route::put('role-permission/{role_id}', [PermissionController::class, 'addOrUpdate']);
            Route::get('role-permission/{role_id}', [PermissionController::class, 'detail']);

            // User
            Route::resource('user', CRUDUserController::class)->except(['edit', 'create']);
            Route::get('get-current-team', [CRUDUserController::class, 'getCurrentTeam']);
            Route::post('current-team', [CRUDUserController::class, 'updateCurrentTeam']);
            Route::post('user/reset-password', [ResetPassowrdCurrentUserController::class, '__invoke']);

            // Tim Relawan
            Route::post('tim-relawan/update', [TimRelawanController::class, 'updateCurrentTimRelawan']);
            Route::prefix('tim-relawan')->group(function () {
                Route::post('/', [TimRelawanController::class, 'addTimRelawan']);
                Route::get('detail/{id}', [TimRelawanController::class, 'showTimRelawan']);
                Route::get('list', [TimRelawanController::class, 'listTimRelawan']);
                // Route::post('updates/{id}', [TimRelawanController::class, 'updateTimRelawan']);
                Route::delete('detail/{id}', [TimRelawanController::class, 'deleteTimRelawan']);

                // Paslon
                Route::post('paslon', [PaslonController::class, 'addPaslon']);
                Route::get('show-paslon/{id}', [PaslonController::class, 'showPaslon']);
                Route::get('list-paslon', [PaslonController::class, 'listPaslon']);
                Route::post('paslon/update/{id}', [PaslonController::class, 'updatePaslon']);
                Route::delete('paslon/{id}', [PaslonController::class, 'deletePaslon']);
            });

            // User Konsultan
            Route::prefix('user-konsultan')->group(function () {
                Route::post('/', [KonsultanController::class, 'addKonsultan']);
                Route::get('/{id}', [KonsultanController::class, 'showKonsultan']);
                Route::get('/', [KonsultanController::class, 'listKonsultan']);
                Route::post('/{id}', [KonsultanController::class, 'updateKonsultan']);
                Route::delete('/{id}', [KonsultanController::class, 'deleteKonsultan']);
            });
            // User Relawan
            Route::prefix('user-relawan')->group(function () {
                Route::post('/', [RelawanController::class, 'addRelawan']);
                Route::get('/{id}', [RelawanController::class, 'showRelawan']);
                Route::get('/', [RelawanController::class, 'listRelawan']);
                Route::post('/{id}', [RelawanController::class, 'updateRelawan']);
                Route::delete('/{id}', [RelawanController::class, 'deleteRelawan']);
                Route::get('log/presensi', [RelawanController::class, 'logPresensiRelawan']);
            });

            // User Saksi
            Route::prefix('user-saksi')->group(function () {
                Route::post('/', [SaksiController::class, 'addSaksi']);
                Route::get('/{id}', [SaksiController::class, 'showSaksi']);
                Route::get('/', [SaksiController::class, 'listSaksi']);
                Route::post('/{id}', [SaksiController::class, 'updateSaksi']);
                Route::delete('/{id}', [SaksiController::class, 'deleteSaksi']);
                Route::get('log/presensi', [SaksiController::class, 'logPresensiSaksi']);
            });

            // User Koordinator
            Route::prefix('user-koordinator')->group(function () {
                Route::post('/', [KoordinatorController::class, 'addKoordinator']);
                Route::get('/new-password/{user}', function (Request $request) {
                    if (!$request->hasValidSignature()) {
                        abort(401);
                    }
                })->name('newpassword.koordinator');
                Route::get('/{id}', [KoordinatorController::class, 'showKoordinator']);
                Route::get('/', [KoordinatorController::class, 'listKoordinator']);
                Route::post('/{id}', [KoordinatorController::class, 'updateKoordinator']);
                Route::delete('/{id}', [KoordinatorController::class, 'deleteKoordinator']);
                Route::get('log/presensi', [KoordinatorController::class, 'logPresensiKoordinator']);
                Route::get('list-relawan/kelurahan', [KoordinatorController::class, 'listRelawanKelurahan']);
                Route::get('list-saksi/kelurahan', [KoordinatorController::class, 'listSaksiKelurahan']);
            });

            // Web Kemenangan
            Route::prefix('web-kemenangan')->group(function () {
                Route::get('/', [WebKemenanganController::class, 'showWebKemenangan']);
                Route::post('update-background', [WebKemenanganController::class, 'updateBackground']);
                Route::post('update-foto-calon', [WebKemenanganController::class, 'updateFotoCalon']);
                Route::post('update-warna-tema', [WebKemenanganController::class, 'updateWarnaTema']);
                Route::post('update-info-calon', [WebKemenanganController::class, 'updateInfoCalon']);
                Route::get('get-link-halaman', [WebKemenanganController::class, 'getLinkHalaman']);
                Route::post('update-visi', [WebKemenanganController::class, 'updateVisi']);
                Route::post('update-daftar-parpol/{id}', [WebKemenanganController::class, 'updateDaftarParpol']);
                Route::post('tambah-daftar-parpol', [WebKemenanganController::class, 'tambahDaftarParpol']);
                Route::delete('delete-daftar-parpol/{id}', [WebKemenanganController::class, 'deleteDaftarParpol']);
                Route::post('update-misi', [WebKemenanganController::class, 'updateMisi']);
                Route::post('update-program-kerja', [WebKemenanganController::class, 'updateProker']);

                // galeri web kemenangan paslon
                Route::prefix('galeri')->group(function () {
                    Route::get('/', [GaleriPaslonController::class, 'listGaleri']);
                    Route::post('/', [GaleriPaslonController::class, 'addGaleri']);
                    Route::get('/show/{id}', [GaleriPaslonController::class, 'showGaleri']);
                    Route::post('/update/{id}', [GaleriPaslonController::class, 'updateGaleri']);
                    Route::delete('/{id}', [GaleriPaslonController::class, 'deleteGaleri']);
                });
                Route::prefix('contact')->group(function () {
                    Route::post('/', [ContactWebKemenanganController::class, 'addorUpdateContact']);
                    Route::get('/', [ContactWebKemenanganController::class, 'getContact']);
                });
                Route::prefix('social-media')->group(function () {
                    Route::post('/', [SosmedWebKemenanganController::class, 'addorUpdateSosmed']);
                    Route::get('/', [SosmedWebKemenanganController::class, 'getSosmed']);
                });

                // Pengalaman Kerja
                Route::prefix('pengalaman-kerja')->group(function() {
                    Route::get('list-all', [PengalamanKerjaController::class, 'getAll']);
                    Route::post('add-pengalaman', [PengalamanKerjaController::class, 'addPengalaman']);
                    Route::post('update-pengalaman/{id}', [PengalamanKerjaController::class, 'updatePengalaman']);
                    Route::delete('delete-pengalaman/{id}', [PengalamanKerjaController::class, 'delPengalaman']);
                });
            });


            // TPS
            Route::prefix('tps')->group(function () {
                Route::post('file-import-tps', [TPSImport::class, '__invoke']);
                Route::post('file-export-tps', [TPSExport::class, '__invoke']);
                Route::get('list-all', [TPSController::class, 'getAll']);
                Route::post('add-tps', [TPSController::class, 'addTps']);
                Route::get('detail/{id}', [TPSController::class, 'detailTps']);
                Route::put('update/{id}', [TPSController::class, 'updateTps']);
                Route::delete('delete/{id}', [TPSController::class, 'deleteTps']);
            });

            //partai
            Route::prefix('partai')->group(function () {
                Route::get('list-all', [PartaiController::class, 'getAll']);
                Route::post('add-partai', [PartaiController::class, 'addPartai']);
                Route::get('detail/{id}', [PartaiController::class, 'detailPartai']);
                Route::post('update/{id}', [PartaiController::class, 'updatePartai']);
                Route::delete('delete/{id}', [PartaiController::class, 'deletePartai']);
            });

            //rute relawan
            Route::prefix('rute-relawan')->group(function () {
                Route::get('list-all', [RuteRelawanController::class, 'listRuteRelawan']);
                Route::post('add-rute', [RuteRelawanController::class, 'addRuteRelawan']);
                Route::get('detail/{id}', [RuteRelawanController::class, 'detailRuteRelawan']);
                Route::post('update/{id}', [RuteRelawanController::class, 'updateRute']);
                Route::delete('delete/{id}', [RuteRelawanController::class, 'deleteRute']);
            });

            // Rute Harian
            Route::prefix('rute-harian')->group(function () {
                Route::get('jadwal-rute', [RuteHarianController::class, 'getJadwalRute']);
                Route::get('riwayat-rute', [RuteHarianController::class, 'getRiwayatRute']);
            });

            // Jumlah DPT
            Route::prefix('jumlah-dpt')->group(function () {
                Route::post('file-import-jumlah-dpt', [FileImportJumlahDPTController::class, '__invoke']);
                Route::post('file-export-jumlah-dpt', [FileExportJumlahDPTController::class, '__invoke']);
                Route::get('list-all', [JumlahDPTController::class, 'getAll']);
                Route::post('add-jumlah-dpt', [JumlahDPTController::class, 'addJumlahDpt']);
                Route::get('detail/{id}', [JumlahDPTController::class, 'detailJumlahDpt']);
                Route::put('update/{id}', [JumlahDPTController::class, 'updateJumlahDpt']);
                Route::delete('delete/{id}', [JumlahDPTController::class, 'deleteJumlahDpt']);
            });

            // DPT
            Route::prefix('dpt')->group(function () {
                Route::post('file-import-dpt', [DPTImport::class, '__invoke']);
                Route::post('file-export-dpt', [FileExportDPTController::class, '__invoke']);
                Route::get('list-dpt/{id_kelurahan}', [DPTController::class, 'getAllByKel']);
                Route::get('list-dpt-by-dapil/{dapil}', [DPTController::class, 'getAllByDapil']);
                Route::post('add-dpt', [DPTController::class, 'addDpt']);
                Route::get('detail/{id}', [DPTController::class, 'detailDpt']);
                Route::post('update/{id}', [DPTController::class, 'updateDpt']);
                Route::delete('delete/{id}', [DPTController::class, 'deleteDpt']);
                Route::get('detail-by-nik', [DPTController::class, 'showDPTByNIK']);
                Route::get('detail-nik-filter', [DPTController::class, 'showDPTByNIKFilter']);
                Route::get('get-dapil', [DPTController::class, 'getAllDapilByDPT']);
            });

            // Data Pendukung
            Route::prefix('data-pendukung')->group(function () {
                Route::get('list-pendukung/{id_kelurahan}', [PendukungController::class, 'getAllByKel']);
                Route::get('list-pendukung-by-dapil/{dapil}', [PendukungController::class, 'getAllByDapil']);
                Route::post('add-pendukung', [PendukungController::class, 'addPendukung']);
                Route::get('detail/{id}', [PendukungController::class, 'detailPendukung']);
                Route::post('update/{id}', [PendukungController::class, 'updatePendukung']);
                Route::delete('delete/{id}', [PendukungController::class, 'deletePendukung']);
                Route::get('get-dapil', [PendukungController::class, 'getAllDapilByDPT']);
            });

            // Issue
            Route::get('jenis-isu', [IssueController::class, 'jenisIsu']);
            Route::prefix('issue')->group(function () {
                Route::get('list-all', [IssueController::class, 'getAll']);
                Route::post('add-issue', [IssueController::class, 'addIssue']);
                Route::get('detail/{id}', [IssueController::class, 'detailIssue']);
                Route::post('update/{id}', [IssueController::class, 'updateIssue']);
                Route::delete('delete/{id}', [IssueController::class, 'deleteIssue']);
                Route::prefix('response')->group(function () {
                    Route::put('update/{id}', [ResponseIssueController::class, 'updateResponseIssue']);
                    Route::put('abaikan/{id}', [ResponseIssueController::class, 'abaikanResponseIssue']);
                    Route::delete('delete/{id}', [ResponseIssueController::class, 'deleteResponseIssue']);
                });
            });

            // Feed
            Route::prefix('feed')->group(function () {
                Route::get('list-all', [CRUDFeedController::class, 'getAll']);
                Route::post('add-feed', [CRUDFeedController::class, 'addFeed']);
                Route::get('detail/{id}', [CRUDFeedController::class, 'detailFeed']);
                Route::post('update/{id}', [CRUDFeedController::class, 'updateFeed']);
                Route::delete('delete/{id}', [CRUDFeedController::class, 'deleteFeed']);
                Route::get('download-image-feed/{id}', [CRUDFeedController::class, 'downloadImageFeed']);

                Route::get('all-share-feed', [ShareFeedController::class, 'getAll']);
                Route::get('share-feed', [ShareFeedController::class, 'getByidFeed']);
                Route::post('share-feed', [ShareFeedController::class, 'create']);
            });

            // Quick Count
            Route::prefix('quick-count')->group(function () {
                Route::get('hasil', [QuickCountController::class, 'hasilQuickCount']);
                Route::get('hasil-partai', [QuickCountController::class, 'hasilQuickCountPartai']);
                Route::get('list', [QuickCountController::class, 'listQuickCount']);
                Route::post('add', [QuickCountController::class, 'addQuickCount']);
                Route::get('detail/{id}', [QuickCountController::class, 'detailQuickCount']);
                Route::post('update/{id}', [QuickCountController::class, 'updateQuickCount']);
                Route::delete('delete/{id}', [QuickCountController::class, 'deleteQuickCount']);
                Route::get('list-tps-quick-count', [QuickCountController::class, 'listTPSQuickCount']);
            });

            // Real Count
            Route::prefix('real-count')->group(function () {
                Route::get('hasil', [RealCountController::class, 'hasilRealCount']);
                Route::get('hasil-partai', [RealCountController::class, 'hasilRealCountPartai']);
                Route::get('hasil-calon-anggota', [RealCountController::class, 'hasilRealCountCalonAnggota']);
                Route::get('list', [RealCountController::class, 'listRealCount']);
                Route::post('add', [RealCountController::class, 'addRealCount']);
                Route::get('detail/{id}', [RealCountController::class, 'detailRealCount']);
                Route::post('update/{id}', [RealCountController::class, 'updateRealCount']);
                Route::delete('delete/{id}', [RealCountController::class, 'deleteRealCount']);
            });

            // Donation
            Route::prefix('donation')->group(function () {
                Route::get('list-all', [DonationController::class, 'getAll']);
                Route::post('add-donation', [DonationController::class, 'addDonation']);
                Route::post('update-donation/{id}', [DonationController::class, 'updateDonation']);
                Route::post('open-close-donation/{id}', [DonationController::class, 'openCloseDonation']);
                Route::get('detail-donation/{id}', [DonationController::class, 'detailDonation']);
                Route::get('detail-payout/{id}', [DonationController::class, 'detailPayout']);
                Route::get('detail-alokasi/{id}', [DonationController::class, 'detailAlokasi']);
                Route::delete('delete/{id}', [DonationController::class, 'deleteDonation']);
            });

            // Donation Donor
            Route::prefix('donation-donor')->group(function () {
                Route::get('detail/{id}', [DonationDonorController::class, 'detailDonation']);
            });

            // Donation Out
            Route::prefix('donation-out')->group(function () {
                Route::get('list', [DonationOutController::class, 'getAll']);
                Route::post('add-invoice', [DonationOutController::class, 'createInvoice']);
            });

            // Alokasi Dana Donasi
            Route::post('alokasi-donasi', [AlokasiDonationController::class, 'alokasi']);
            Route::get('list-alokasi', [AlokasiDonationController::class, 'listAlokasi']);
            Route::post('update-alokasi/{id}', [AlokasiDonationController::class, 'updateAlokasi']);
            Route::get('detail-alokasi/{id}', [AlokasiDonationController::class, 'detailAlokasi']);

            // Logistik
            Route::prefix('logistik')->group(function () {
                Route::resource('stok-barang', StokBarangController::class)->except(['edit', 'create']);
                Route::resource('pemesanan-barang', PemesananBarangController::class)->except(['edit', 'create', 'destroy']);
                Route::resource('penerimaan-barang', PenerimaanBarangController::class)->except(['edit', 'create', 'update', 'destroy']);
                Route::resource('pengeluaran-barang', PengeluaranBarangController::class)->except(['edit', 'create', 'update', 'destroy']);
            });


            // Survey
            Route::prefix('survey')->group(function () {
                Route::resource('form', SurveyController::class);
                Route::get('list-all/by-wilayah', [SurveyController::class, 'getAllForRelawan']);
                Route::get('result/{form_survey_id}', [SurveyController::class, 'hasilSurvey']);
                Route::post('form-change-status/{id}', [SurveyController::class, 'draftPublish']);
                Route::resource('field', FieldSurveyController::class)->except(['edit', 'create', 'show', 'update', 'index']);
                Route::get('field', [FieldSurveyController::class, 'detail']);
                Route::post('field/update', [FieldSurveyController::class, 'update']);
                Route::post('answer', [AnswerSurveyController::class, 'store']);
                Route::post('update-answer', [AnswerSurveyController::class, 'update']);
                Route::get('show-answer', [AnswerSurveyController::class, 'show']);
                Route::delete('delete-answer', [AnswerSurveyController::class, 'destroy']);
                Route::get('get-all/result/relawan', [SurveyController::class, 'getAllResultByRelawan']);
                Route::get('all-dapil', [SurveyController::class, 'getAllDapilBySurvey']);
            });

            // Educate
            Route::prefix('educate')->group(function () {
                Route::resource('form', EducateController::class);
                Route::get('list-all/by-wilayah', [EducateController::class, 'getAllForRelawan']);
                Route::get('result/{form_survey_id}', [EducateController::class, 'hasilSurvey']);
                Route::post('form-change-status/{id}', [EducateController::class, 'draftPublish']);
                Route::resource('field', FieldEducateController::class)->except(['edit', 'create', 'show', 'update', 'index']);
                Route::get('field', [FieldEducateController::class, 'detail']);
                Route::post('field/update', [FieldEducateController::class, 'update']);
                Route::post('answer', [AnswerEducateController::class, 'store']);
                Route::post('update-answer', [AnswerEducateController::class, 'update']);
                Route::get('show-answer', [AnswerEducateController::class, 'show']);
                Route::delete('delete-answer', [AnswerEducateController::class, 'destroy']);
                Route::get('get-all/result/relawan', [EducateController::class, 'getAllResultByRelawan']);
                Route::get('all-dapil', [EducateController::class, 'getAllDapilBySurvey']);
            });

            // Simulasi Target Suara Kemenangan
            Route::get("simulasi-target-suara-kemenangan", [SimulasiTargetKemenanganController::class, 'index']);

            // Rekapitulasi
            Route::prefix('rekapitulasi')->group(function () {
                Route::get('koordinator', [RekapitulasiKoordinatorController::class, 'rekapitulasiKoordinator']);
                Route::get('relawan', [RekapitulasiRelawanController::class, 'rekapitulasiRelawan']);
                Route::get('saksi', [RekapitulasiSaksiController::class, 'rekapitulasiSaksi']);
                Route::get('tps', [RekapitulasiDataTpsController::class, 'rekapitulasiTps']);
                Route::get('dpt-manual', [RekapitulasiPemilihTetapManualController::class, 'rekapitulasiDptManual']);
                Route::get('pemilih-tetap', [RekapitulasiPemilihTetapController::class, 'rekapitulasiPemilihTetap']);
                Route::get('pemilih-pendukung', [RekapitulasiPendukungController::class, 'rekapitulasiPemilihPendukung']);
            });

            // Dashboard
            Route::prefix('dashboard')->group(function () {
                Route::get('user', [DashboardController::class, 'dashboardUser']);
                Route::get('default', [DashboardController::class, 'dashboardDefault']);
                Route::post('checkin', [CheckinController::class, 'checkin']);
                Route::post('checkout', [CheckinController::class, 'checkout']);
                Route::get('get-data-check', [CheckinController::class, 'getDataCheck']);
            });

            // Mobile

            // Survey By Relawan
            Route::prefix('survey')->group(function () {
                Route::get('result/by-relawan/{form_survey_id}', [SurveyController::class, 'hasilSurveyByRelawan']);
                Route::get('result/detail/by-relawan/{form_survey_id}/{relawan_id}', [SurveyController::class, 'showByRelawan']);
            });

            // Educate By Relawan
            Route::prefix('educate')->group(function () {
                Route::get('result/by-relawan/{form_survey_id}', [EducateController::class, 'hasilSurveyByRelawan']);
                Route::get('result/detail/by-relawan/{form_survey_id}/{relawan_id}', [EducateController::class, 'showByRelawan']);
            });

            // Koordinator
            Route::prefix('koordinator')->group(function () {
                Route::get('find-by-wilayah', [KoordinatorFindWilayahController::class, 'findByWilayah']);
            });

            // Chat
            Route::prefix('chat')->group(function () {
                Route::get('me', MeController::class);

                Route::get('user', [ChatUserController::class, 'index']);
                Route::get('user/{id}', [ChatUserController::class, 'show']);
                Route::get('conversation/{user_two}', [ConversationController::class, 'show'])->name('conversation.show');
                Route::get('conversation/last-chat/{user_two}', [ConversationController::class, 'lastChat'])->name('conversation.last.chat');
                Route::post('conversation/{conversation}/message', [ConversationController::class, 'store'])->name('conversation.store');
            });

            // Chat
            Route::prefix('chat-v2')->group(function () {

                Route::get('user', [ListChatUserController::class, 'index']);
                Route::get('conversation/{user_two}', [ChatV2Controller::class, 'show'])->middleware(['throttle:global']);
                Route::get('conversation/trigger/{user_two}', [ChatV2Controller::class, 'triggerShow'])->middleware(['throttle:global']);
                Route::post('conversation/{user_two}/message', [ChatV2Controller::class, 'store'])->middleware(['throttle:global']);
            });

            // Order
            Route::prefix('order')->group(function () {
                Route::get('list', [OrderTimController::class, 'list']);
                Route::get('detail/{id}', [OrderTimController::class, 'detail']);
                Route::post('add-order-tim', [OrderTimController::class, 'createInvoice']);
            });

            Route::post('update-profile', [UpdateProfileController::class, '__invoke']);
            Route::get('user-profile', [ShowProfileController::class, '__invoke']);

            // Calon Anggota
            Route::prefix('calon-anggota')->group(function () {
                Route::get('list-all', [CalonAnggotaController::class, 'getAll']);
                Route::post('add-data', [CalonAnggotaController::class, 'store']);
                Route::post('update-data/{id}', [CalonAnggotaController::class, 'update']);
                Route::delete('delete-data/{id}', [CalonAnggotaController::class, 'destroy']);
            });
        });
    });
    Route::get('feed/{id}', [CRUDFeedController::class, 'ShareFeeds']);
    Route::get('web-kemenangan/get-all', [LandingWebKemenanganController::class, 'getAllWebKemenangan']);
    Route::get('web-kemenangan/{slug}', [LandingWebKemenanganController::class, 'getWebKemenanganBySlug']);
    Route::get('web-kemenangan/{slug}/donasi/{donasi_id}', [LandingWebKemenanganController::class, 'getAlokasiDonasi']);
    Route::prefix('donation-donor')->group(function () {
        Route::post('add-invoice', [DonationDonorController::class, 'createInvoice']);
    });
    Route::post('in-callback', [CallbackController::class, 'inCallback']);
    // Route::post('out-callback', [CallbackController::class, 'outCallback']);

    Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
    Route::post('reset-password', [NewPasswordController::class, 'reset']);

    Route::prefix('cms')->group(function () {
        Route::get('list/all/order', [OrderController::class, 'getAll']);
        Route::get('list/all/tim', [TimController::class, 'getAll']);

        Route::get('list/all/request/withdrawal', [WithdrawalController::class, 'getAll']);
        Route::post('status/request/withdrawal/{id}', [WithdrawalController::class, 'statusWithdrawal']);
    });
});
