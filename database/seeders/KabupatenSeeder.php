<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::disableQueryLog();
        $kabupatens = [
            [
                "id" => 1101,
                "propinsi_id" => 11,
                "name" => "KABUPATEN SIMEULUE"
            ],
            [
                "id" => 1102,
                "propinsi_id" => 11,
                "name" => "KABUPATEN ACEH SINGKIL"
            ],
            [
                "id" => 1103,
                "propinsi_id" => 11,
                "name" => "KABUPATEN ACEH SELATAN"
            ],
            [
                "id" => 1104,
                "propinsi_id" => 11,
                "name" => "KABUPATEN ACEH TENGGARA"
            ],
            [
                "id" => 1105,
                "propinsi_id" => 11,
                "name" => "KABUPATEN ACEH TIMUR"
            ],
            [
                "id" => 1106,
                "propinsi_id" => 11,
                "name" => "KABUPATEN ACEH TENGAH"
            ],
            [
                "id" => 1107,
                "propinsi_id" => 11,
                "name" => "KABUPATEN ACEH BARAT"
            ],
            [
                "id" => 1108,
                "propinsi_id" => 11,
                "name" => "KABUPATEN ACEH BESAR"
            ],
            [
                "id" => 1109,
                "propinsi_id" => 11,
                "name" => "KABUPATEN PIDIE"
            ],
            [
                "id" => 1110,
                "propinsi_id" => 11,
                "name" => "KABUPATEN BIREUEN"
            ],
            [
                "id" => 1111,
                "propinsi_id" => 11,
                "name" => "KABUPATEN ACEH UTARA"
            ],
            [
                "id" => 1112,
                "propinsi_id" => 11,
                "name" => "KABUPATEN ACEH BARAT DAYA"
            ],
            [
                "id" => 1113,
                "propinsi_id" => 11,
                "name" => "KABUPATEN GAYO LUES"
            ],
            [
                "id" => 1114,
                "propinsi_id" => 11,
                "name" => "KABUPATEN ACEH TAMIANG"
            ],
            [
                "id" => 1115,
                "propinsi_id" => 11,
                "name" => "KABUPATEN NAGAN RAYA"
            ],
            [
                "id" => 1116,
                "propinsi_id" => 11,
                "name" => "KABUPATEN ACEH JAYA"
            ],
            [
                "id" => 1117,
                "propinsi_id" => 11,
                "name" => "KABUPATEN BENER MERIAH"
            ],
            [
                "id" => 1118,
                "propinsi_id" => 11,
                "name" => "KABUPATEN PIDIE JAYA"
            ],
            [
                "id" => 1171,
                "propinsi_id" => 11,
                "name" => "KOTA BANDA ACEH"
            ],
            [
                "id" => 1172,
                "propinsi_id" => 11,
                "name" => "KOTA SABANG"
            ],
            [
                "id" => 1173,
                "propinsi_id" => 11,
                "name" => "KOTA LANGSA"
            ],
            [
                "id" => 1174,
                "propinsi_id" => 11,
                "name" => "KOTA LHOKSEUMAWE"
            ],
            [
                "id" => 1175,
                "propinsi_id" => 11,
                "name" => "KOTA SUBULUSSALAM"
            ],
            [
                "id" => 1201,
                "propinsi_id" => 12,
                "name" => "KABUPATEN NIAS"
            ],
            [
                "id" => 1202,
                "propinsi_id" => 12,
                "name" => "KABUPATEN MANDAILING NATAL"
            ],
            [
                "id" => 1203,
                "propinsi_id" => 12,
                "name" => "KABUPATEN TAPANULI SELATAN"
            ],
            [
                "id" => 1204,
                "propinsi_id" => 12,
                "name" => "KABUPATEN TAPANULI TENGAH"
            ],
            [
                "id" => 1205,
                "propinsi_id" => 12,
                "name" => "KABUPATEN TAPANULI UTARA"
            ],
            [
                "id" => 1206,
                "propinsi_id" => 12,
                "name" => "KABUPATEN TOBA SAMOSIR"
            ],
            [
                "id" => 1207,
                "propinsi_id" => 12,
                "name" => "KABUPATEN LABUHAN BATU"
            ],
            [
                "id" => 1208,
                "propinsi_id" => 12,
                "name" => "KABUPATEN ASAHAN"
            ],
            [
                "id" => 1209,
                "propinsi_id" => 12,
                "name" => "KABUPATEN SIMALUNGUN"
            ],
            [
                "id" => 1210,
                "propinsi_id" => 12,
                "name" => "KABUPATEN DAIRI"
            ],
            [
                "id" => 1211,
                "propinsi_id" => 12,
                "name" => "KABUPATEN KARO"
            ],
            [
                "id" => 1212,
                "propinsi_id" => 12,
                "name" => "KABUPATEN DELI SERDANG"
            ],
            [
                "id" => 1213,
                "propinsi_id" => 12,
                "name" => "KABUPATEN LANGKAT"
            ],
            [
                "id" => 1214,
                "propinsi_id" => 12,
                "name" => "KABUPATEN NIAS SELATAN"
            ],
            [
                "id" => 1215,
                "propinsi_id" => 12,
                "name" => "KABUPATEN HUMBANG HASUNDUTAN"
            ],
            [
                "id" => 1216,
                "propinsi_id" => 12,
                "name" => "KABUPATEN PAKPAK BHARAT"
            ],
            [
                "id" => 1217,
                "propinsi_id" => 12,
                "name" => "KABUPATEN SAMOSIR"
            ],
            [
                "id" => 1218,
                "propinsi_id" => 12,
                "name" => "KABUPATEN SERDANG BEDAGAI"
            ],
            [
                "id" => 1219,
                "propinsi_id" => 12,
                "name" => "KABUPATEN BATU BARA"
            ],
            [
                "id" => 1220,
                "propinsi_id" => 12,
                "name" => "KABUPATEN PADANG LAWAS UTARA"
            ],
            [
                "id" => 1221,
                "propinsi_id" => 12,
                "name" => "KABUPATEN PADANG LAWAS"
            ],
            [
                "id" => 1222,
                "propinsi_id" => 12,
                "name" => "KABUPATEN LABUHAN BATU SELATAN"
            ],
            [
                "id" => 1223,
                "propinsi_id" => 12,
                "name" => "KABUPATEN LABUHAN BATU UTARA"
            ],
            [
                "id" => 1224,
                "propinsi_id" => 12,
                "name" => "KABUPATEN NIAS UTARA"
            ],
            [
                "id" => 1225,
                "propinsi_id" => 12,
                "name" => "KABUPATEN NIAS BARAT"
            ],
            [
                "id" => 1271,
                "propinsi_id" => 12,
                "name" => "KOTA SIBOLGA"
            ],
            [
                "id" => 1272,
                "propinsi_id" => 12,
                "name" => "KOTA TANJUNG BALAI"
            ],
            [
                "id" => 1273,
                "propinsi_id" => 12,
                "name" => "KOTA PEMATANG SIANTAR"
            ],
            [
                "id" => 1274,
                "propinsi_id" => 12,
                "name" => "KOTA TEBING TINGGI"
            ],
            [
                "id" => 1275,
                "propinsi_id" => 12,
                "name" => "KOTA MEDAN"
            ],
            [
                "id" => 1276,
                "propinsi_id" => 12,
                "name" => "KOTA BINJAI"
            ],
            [
                "id" => 1277,
                "propinsi_id" => 12,
                "name" => "KOTA PADANGSIDIMPUAN"
            ],
            [
                "id" => 1278,
                "propinsi_id" => 12,
                "name" => "KOTA GUNUNGSITOLI"
            ],
            [
                "id" => 1301,
                "propinsi_id" => 13,
                "name" => "KABUPATEN KEPULAUAN MENTAWAI"
            ],
            [
                "id" => 1302,
                "propinsi_id" => 13,
                "name" => "KABUPATEN PESISIR SELATAN"
            ],
            [
                "id" => 1303,
                "propinsi_id" => 13,
                "name" => "KABUPATEN SOLOK"
            ],
            [
                "id" => 1304,
                "propinsi_id" => 13,
                "name" => "KABUPATEN SIJUNJUNG"
            ],
            [
                "id" => 1305,
                "propinsi_id" => 13,
                "name" => "KABUPATEN TANAH DATAR"
            ],
            [
                "id" => 1306,
                "propinsi_id" => 13,
                "name" => "KABUPATEN PADANG PARIAMAN"
            ],
            [
                "id" => 1307,
                "propinsi_id" => 13,
                "name" => "KABUPATEN AGAM"
            ],
            [
                "id" => 1308,
                "propinsi_id" => 13,
                "name" => "KABUPATEN LIMA PULUH KOTA"
            ],
            [
                "id" => 1309,
                "propinsi_id" => 13,
                "name" => "KABUPATEN PASAMAN"
            ],
            [
                "id" => 1310,
                "propinsi_id" => 13,
                "name" => "KABUPATEN SOLOK SELATAN"
            ],
            [
                "id" => 1311,
                "propinsi_id" => 13,
                "name" => "KABUPATEN DHARMASRAYA"
            ],
            [
                "id" => 1312,
                "propinsi_id" => 13,
                "name" => "KABUPATEN PASAMAN BARAT"
            ],
            [
                "id" => 1371,
                "propinsi_id" => 13,
                "name" => "KOTA PADANG"
            ],
            [
                "id" => 1372,
                "propinsi_id" => 13,
                "name" => "KOTA SOLOK"
            ],
            [
                "id" => 1373,
                "propinsi_id" => 13,
                "name" => "KOTA SAWAH LUNTO"
            ],
            [
                "id" => 1374,
                "propinsi_id" => 13,
                "name" => "KOTA PADANG PANJANG"
            ],
            [
                "id" => 1375,
                "propinsi_id" => 13,
                "name" => "KOTA BUKITTINGGI"
            ],
            [
                "id" => 1376,
                "propinsi_id" => 13,
                "name" => "KOTA PAYAKUMBUH"
            ],
            [
                "id" => 1377,
                "propinsi_id" => 13,
                "name" => "KOTA PARIAMAN"
            ],
            [
                "id" => 1401,
                "propinsi_id" => 14,
                "name" => "KABUPATEN KUANTAN SINGINGI"
            ],
            [
                "id" => 1402,
                "propinsi_id" => 14,
                "name" => "KABUPATEN INDRAGIRI HULU"
            ],
            [
                "id" => 1403,
                "propinsi_id" => 14,
                "name" => "KABUPATEN INDRAGIRI HILIR"
            ],
            [
                "id" => 1404,
                "propinsi_id" => 14,
                "name" => "KABUPATEN PELALAWAN"
            ],
            [
                "id" => 1405,
                "propinsi_id" => 14,
                "name" => "KABUPATEN S I A K"
            ],
            [
                "id" => 1406,
                "propinsi_id" => 14,
                "name" => "KABUPATEN KAMPAR"
            ],
            [
                "id" => 1407,
                "propinsi_id" => 14,
                "name" => "KABUPATEN ROKAN HULU"
            ],
            [
                "id" => 1408,
                "propinsi_id" => 14,
                "name" => "KABUPATEN BENGKALIS"
            ],
            [
                "id" => 1409,
                "propinsi_id" => 14,
                "name" => "KABUPATEN ROKAN HILIR"
            ],
            [
                "id" => 1410,
                "propinsi_id" => 14,
                "name" => "KABUPATEN KEPULAUAN MERANTI"
            ],
            [
                "id" => 1471,
                "propinsi_id" => 14,
                "name" => "KOTA PEKANBARU"
            ],
            [
                "id" => 1473,
                "propinsi_id" => 14,
                "name" => "KOTA D U M A I"
            ],
            [
                "id" => 1501,
                "propinsi_id" => 15,
                "name" => "KABUPATEN KERINCI"
            ],
            [
                "id" => 1502,
                "propinsi_id" => 15,
                "name" => "KABUPATEN MERANGIN"
            ],
            [
                "id" => 1503,
                "propinsi_id" => 15,
                "name" => "KABUPATEN SAROLANGUN"
            ],
            [
                "id" => 1504,
                "propinsi_id" => 15,
                "name" => "KABUPATEN BATANG HARI"
            ],
            [
                "id" => 1505,
                "propinsi_id" => 15,
                "name" => "KABUPATEN MUARO JAMBI"
            ],
            [
                "id" => 1506,
                "propinsi_id" => 15,
                "name" => "KABUPATEN TANJUNG JABUNG TIMUR"
            ],
            [
                "id" => 1507,
                "propinsi_id" => 15,
                "name" => "KABUPATEN TANJUNG JABUNG BARAT"
            ],
            [
                "id" => 1508,
                "propinsi_id" => 15,
                "name" => "KABUPATEN TEBO"
            ],
            [
                "id" => 1509,
                "propinsi_id" => 15,
                "name" => "KABUPATEN BUNGO"
            ],
            [
                "id" => 1571,
                "propinsi_id" => 15,
                "name" => "KOTA JAMBI"
            ],
            [
                "id" => 1572,
                "propinsi_id" => 15,
                "name" => "KOTA SUNGAI PENUH"
            ],
            [
                "id" => 1601,
                "propinsi_id" => 16,
                "name" => "KABUPATEN OGAN KOMERING ULU"
            ],
            [
                "id" => 1602,
                "propinsi_id" => 16,
                "name" => "KABUPATEN OGAN KOMERING ILIR"
            ],
            [
                "id" => 1603,
                "propinsi_id" => 16,
                "name" => "KABUPATEN MUARA ENIM"
            ],
            [
                "id" => 1604,
                "propinsi_id" => 16,
                "name" => "KABUPATEN LAHAT"
            ],
            [
                "id" => 1605,
                "propinsi_id" => 16,
                "name" => "KABUPATEN MUSI RAWAS"
            ],
            [
                "id" => 1606,
                "propinsi_id" => 16,
                "name" => "KABUPATEN MUSI BANYUASIN"
            ],
            [
                "id" => 1607,
                "propinsi_id" => 16,
                "name" => "KABUPATEN BANYU ASIN"
            ],
            [
                "id" => 1608,
                "propinsi_id" => 16,
                "name" => "KABUPATEN OGAN KOMERING ULU SELATAN"
            ],
            [
                "id" => 1609,
                "propinsi_id" => 16,
                "name" => "KABUPATEN OGAN KOMERING ULU TIMUR"
            ],
            [
                "id" => 1610,
                "propinsi_id" => 16,
                "name" => "KABUPATEN OGAN ILIR"
            ],
            [
                "id" => 1611,
                "propinsi_id" => 16,
                "name" => "KABUPATEN EMPAT LAWANG"
            ],
            [
                "id" => 1612,
                "propinsi_id" => 16,
                "name" => "KABUPATEN PENUKAL ABAB LEMATANG ILIR"
            ],
            [
                "id" => 1613,
                "propinsi_id" => 16,
                "name" => "KABUPATEN MUSI RAWAS UTARA"
            ],
            [
                "id" => 1671,
                "propinsi_id" => 16,
                "name" => "KOTA PALEMBANG"
            ],
            [
                "id" => 1672,
                "propinsi_id" => 16,
                "name" => "KOTA PRABUMULIH"
            ],
            [
                "id" => 1673,
                "propinsi_id" => 16,
                "name" => "KOTA PAGAR ALAM"
            ],
            [
                "id" => 1674,
                "propinsi_id" => 16,
                "name" => "KOTA LUBUKLINGGAU"
            ],
            [
                "id" => 1701,
                "propinsi_id" => 17,
                "name" => "KABUPATEN BENGKULU SELATAN"
            ],
            [
                "id" => 1702,
                "propinsi_id" => 17,
                "name" => "KABUPATEN REJANG LEBONG"
            ],
            [
                "id" => 1703,
                "propinsi_id" => 17,
                "name" => "KABUPATEN BENGKULU UTARA"
            ],
            [
                "id" => 1704,
                "propinsi_id" => 17,
                "name" => "KABUPATEN KAUR"
            ],
            [
                "id" => 1705,
                "propinsi_id" => 17,
                "name" => "KABUPATEN SELUMA"
            ],
            [
                "id" => 1706,
                "propinsi_id" => 17,
                "name" => "KABUPATEN MUKOMUKO"
            ],
            [
                "id" => 1707,
                "propinsi_id" => 17,
                "name" => "KABUPATEN LEBONG"
            ],
            [
                "id" => 1708,
                "propinsi_id" => 17,
                "name" => "KABUPATEN KEPAHIANG"
            ],
            [
                "id" => 1709,
                "propinsi_id" => 17,
                "name" => "KABUPATEN BENGKULU TENGAH"
            ],
            [
                "id" => 1771,
                "propinsi_id" => 17,
                "name" => "KOTA BENGKULU"
            ],
            [
                "id" => 1801,
                "propinsi_id" => 18,
                "name" => "KABUPATEN LAMPUNG BARAT"
            ],
            [
                "id" => 1802,
                "propinsi_id" => 18,
                "name" => "KABUPATEN TANGGAMUS"
            ],
            [
                "id" => 1803,
                "propinsi_id" => 18,
                "name" => "KABUPATEN LAMPUNG SELATAN"
            ],
            [
                "id" => 1804,
                "propinsi_id" => 18,
                "name" => "KABUPATEN LAMPUNG TIMUR"
            ],
            [
                "id" => 1805,
                "propinsi_id" => 18,
                "name" => "KABUPATEN LAMPUNG TENGAH"
            ],
            [
                "id" => 1806,
                "propinsi_id" => 18,
                "name" => "KABUPATEN LAMPUNG UTARA"
            ],
            [
                "id" => 1807,
                "propinsi_id" => 18,
                "name" => "KABUPATEN WAY KANAN"
            ],
            [
                "id" => 1808,
                "propinsi_id" => 18,
                "name" => "KABUPATEN TULANGBAWANG"
            ],
            [
                "id" => 1809,
                "propinsi_id" => 18,
                "name" => "KABUPATEN PESAWARAN"
            ],
            [
                "id" => 1810,
                "propinsi_id" => 18,
                "name" => "KABUPATEN PRINGSEWU"
            ],
            [
                "id" => 1811,
                "propinsi_id" => 18,
                "name" => "KABUPATEN MESUJI"
            ],
            [
                "id" => 1812,
                "propinsi_id" => 18,
                "name" => "KABUPATEN TULANG BAWANG BARAT"
            ],
            [
                "id" => 1813,
                "propinsi_id" => 18,
                "name" => "KABUPATEN PESISIR BARAT"
            ],
            [
                "id" => 1871,
                "propinsi_id" => 18,
                "name" => "KOTA BANDAR LAMPUNG"
            ],
            [
                "id" => 1872,
                "propinsi_id" => 18,
                "name" => "KOTA METRO"
            ],
            [
                "id" => 1901,
                "propinsi_id" => 19,
                "name" => "KABUPATEN BANGKA"
            ],
            [
                "id" => 1902,
                "propinsi_id" => 19,
                "name" => "KABUPATEN BELITUNG"
            ],
            [
                "id" => 1903,
                "propinsi_id" => 19,
                "name" => "KABUPATEN BANGKA BARAT"
            ],
            [
                "id" => 1904,
                "propinsi_id" => 19,
                "name" => "KABUPATEN BANGKA TENGAH"
            ],
            [
                "id" => 1905,
                "propinsi_id" => 19,
                "name" => "KABUPATEN BANGKA SELATAN"
            ],
            [
                "id" => 1906,
                "propinsi_id" => 19,
                "name" => "KABUPATEN BELITUNG TIMUR"
            ],
            [
                "id" => 1971,
                "propinsi_id" => 19,
                "name" => "KOTA PANGKAL PINANG"
            ],
            [
                "id" => 2101,
                "propinsi_id" => 21,
                "name" => "KABUPATEN KARIMUN"
            ],
            [
                "id" => 2102,
                "propinsi_id" => 21,
                "name" => "KABUPATEN BINTAN"
            ],
            [
                "id" => 2103,
                "propinsi_id" => 21,
                "name" => "KABUPATEN NATUNA"
            ],
            [
                "id" => 2104,
                "propinsi_id" => 21,
                "name" => "KABUPATEN LINGGA"
            ],
            [
                "id" => 2105,
                "propinsi_id" => 21,
                "name" => "KABUPATEN KEPULAUAN ANAMBAS"
            ],
            [
                "id" => 2171,
                "propinsi_id" => 21,
                "name" => "KOTA B A T A M"
            ],
            [
                "id" => 2172,
                "propinsi_id" => 21,
                "name" => "KOTA TANJUNG PINANG"
            ],
            [
                "id" => 3101,
                "propinsi_id" => 31,
                "name" => "KABUPATEN KEPULAUAN SERIBU"
            ],
            [
                "id" => 3171,
                "propinsi_id" => 31,
                "name" => "KOTA JAKARTA SELATAN"
            ],
            [
                "id" => 3172,
                "propinsi_id" => 31,
                "name" => "KOTA JAKARTA TIMUR"
            ],
            [
                "id" => 3173,
                "propinsi_id" => 31,
                "name" => "KOTA JAKARTA PUSAT"
            ],
            [
                "id" => 3174,
                "propinsi_id" => 31,
                "name" => "KOTA JAKARTA BARAT"
            ],
            [
                "id" => 3175,
                "propinsi_id" => 31,
                "name" => "KOTA JAKARTA UTARA"
            ],
            [
                "id" => 3201,
                "propinsi_id" => 32,
                "name" => "KABUPATEN BOGOR"
            ],
            [
                "id" => 3202,
                "propinsi_id" => 32,
                "name" => "KABUPATEN SUKABUMI"
            ],
            [
                "id" => 3203,
                "propinsi_id" => 32,
                "name" => "KABUPATEN CIANJUR"
            ],
            [
                "id" => 3204,
                "propinsi_id" => 32,
                "name" => "KABUPATEN BANDUNG"
            ],
            [
                "id" => 3205,
                "propinsi_id" => 32,
                "name" => "KABUPATEN GARUT"
            ],
            [
                "id" => 3206,
                "propinsi_id" => 32,
                "name" => "KABUPATEN TASIKMALAYA"
            ],
            [
                "id" => 3207,
                "propinsi_id" => 32,
                "name" => "KABUPATEN CIAMIS"
            ],
            [
                "id" => 3208,
                "propinsi_id" => 32,
                "name" => "KABUPATEN KUNINGAN"
            ],
            [
                "id" => 3209,
                "propinsi_id" => 32,
                "name" => "KABUPATEN CIREBON"
            ],
            [
                "id" => 3210,
                "propinsi_id" => 32,
                "name" => "KABUPATEN MAJALENGKA"
            ],
            [
                "id" => 3211,
                "propinsi_id" => 32,
                "name" => "KABUPATEN SUMEDANG"
            ],
            [
                "id" => 3212,
                "propinsi_id" => 32,
                "name" => "KABUPATEN INDRAMAYU"
            ],
            [
                "id" => 3213,
                "propinsi_id" => 32,
                "name" => "KABUPATEN SUBANG"
            ],
            [
                "id" => 3214,
                "propinsi_id" => 32,
                "name" => "KABUPATEN PURWAKARTA"
            ],
            [
                "id" => 3215,
                "propinsi_id" => 32,
                "name" => "KABUPATEN KARAWANG"
            ],
            [
                "id" => 3216,
                "propinsi_id" => 32,
                "name" => "KABUPATEN BEKASI"
            ],
            [
                "id" => 3217,
                "propinsi_id" => 32,
                "name" => "KABUPATEN BANDUNG BARAT"
            ],
            [
                "id" => 3218,
                "propinsi_id" => 32,
                "name" => "KABUPATEN PANGANDARAN"
            ],
            [
                "id" => 3271,
                "propinsi_id" => 32,
                "name" => "KOTA BOGOR"
            ],
            [
                "id" => 3272,
                "propinsi_id" => 32,
                "name" => "KOTA SUKABUMI"
            ],
            [
                "id" => 3273,
                "propinsi_id" => 32,
                "name" => "KOTA BANDUNG"
            ],
            [
                "id" => 3274,
                "propinsi_id" => 32,
                "name" => "KOTA CIREBON"
            ],
            [
                "id" => 3275,
                "propinsi_id" => 32,
                "name" => "KOTA BEKASI"
            ],
            [
                "id" => 3276,
                "propinsi_id" => 32,
                "name" => "KOTA DEPOK"
            ],
            [
                "id" => 3277,
                "propinsi_id" => 32,
                "name" => "KOTA CIMAHI"
            ],
            [
                "id" => 3278,
                "propinsi_id" => 32,
                "name" => "KOTA TASIKMALAYA"
            ],
            [
                "id" => 3279,
                "propinsi_id" => 32,
                "name" => "KOTA BANJAR"
            ],
            [
                "id" => 3301,
                "propinsi_id" => 33,
                "name" => "KABUPATEN CILACAP"
            ],
            [
                "id" => 3302,
                "propinsi_id" => 33,
                "name" => "KABUPATEN BANYUMAS"
            ],
            [
                "id" => 3303,
                "propinsi_id" => 33,
                "name" => "KABUPATEN PURBALINGGA"
            ],
            [
                "id" => 3304,
                "propinsi_id" => 33,
                "name" => "KABUPATEN BANJARNEGARA"
            ],
            [
                "id" => 3305,
                "propinsi_id" => 33,
                "name" => "KABUPATEN KEBUMEN"
            ],
            [
                "id" => 3306,
                "propinsi_id" => 33,
                "name" => "KABUPATEN PURWOREJO"
            ],
            [
                "id" => 3307,
                "propinsi_id" => 33,
                "name" => "KABUPATEN WONOSOBO"
            ],
            [
                "id" => 3308,
                "propinsi_id" => 33,
                "name" => "KABUPATEN MAGELANG"
            ],
            [
                "id" => 3309,
                "propinsi_id" => 33,
                "name" => "KABUPATEN BOYOLALI"
            ],
            [
                "id" => 3310,
                "propinsi_id" => 33,
                "name" => "KABUPATEN KLATEN"
            ],
            [
                "id" => 3311,
                "propinsi_id" => 33,
                "name" => "KABUPATEN SUKOHARJO"
            ],
            [
                "id" => 3312,
                "propinsi_id" => 33,
                "name" => "KABUPATEN WONOGIRI"
            ],
            [
                "id" => 3313,
                "propinsi_id" => 33,
                "name" => "KABUPATEN KARANGANYAR"
            ],
            [
                "id" => 3314,
                "propinsi_id" => 33,
                "name" => "KABUPATEN SRAGEN"
            ],
            [
                "id" => 3315,
                "propinsi_id" => 33,
                "name" => "KABUPATEN GROBOGAN"
            ],
            [
                "id" => 3316,
                "propinsi_id" => 33,
                "name" => "KABUPATEN BLORA"
            ],
            [
                "id" => 3317,
                "propinsi_id" => 33,
                "name" => "KABUPATEN REMBANG"
            ],
            [
                "id" => 3318,
                "propinsi_id" => 33,
                "name" => "KABUPATEN PATI"
            ],
            [
                "id" => 3319,
                "propinsi_id" => 33,
                "name" => "KABUPATEN KUDUS"
            ],
            [
                "id" => 3320,
                "propinsi_id" => 33,
                "name" => "KABUPATEN JEPARA"
            ],
            [
                "id" => 3321,
                "propinsi_id" => 33,
                "name" => "KABUPATEN DEMAK"
            ],
            [
                "id" => 3322,
                "propinsi_id" => 33,
                "name" => "KABUPATEN SEMARANG"
            ],
            [
                "id" => 3323,
                "propinsi_id" => 33,
                "name" => "KABUPATEN TEMANGGUNG"
            ],
            [
                "id" => 3324,
                "propinsi_id" => 33,
                "name" => "KABUPATEN KENDAL"
            ],
            [
                "id" => 3325,
                "propinsi_id" => 33,
                "name" => "KABUPATEN BATANG"
            ],
            [
                "id" => 3326,
                "propinsi_id" => 33,
                "name" => "KABUPATEN PEKALONGAN"
            ],
            [
                "id" => 3327,
                "propinsi_id" => 33,
                "name" => "KABUPATEN PEMALANG"
            ],
            [
                "id" => 3328,
                "propinsi_id" => 33,
                "name" => "KABUPATEN TEGAL"
            ],
            [
                "id" => 3329,
                "propinsi_id" => 33,
                "name" => "KABUPATEN BREBES"
            ],
            [
                "id" => 3371,
                "propinsi_id" => 33,
                "name" => "KOTA MAGELANG"
            ],
            [
                "id" => 3372,
                "propinsi_id" => 33,
                "name" => "KOTA SURAKARTA"
            ],
            [
                "id" => 3373,
                "propinsi_id" => 33,
                "name" => "KOTA SALATIGA"
            ],
            [
                "id" => 3374,
                "propinsi_id" => 33,
                "name" => "KOTA SEMARANG"
            ],
            [
                "id" => 3375,
                "propinsi_id" => 33,
                "name" => "KOTA PEKALONGAN"
            ],
            [
                "id" => 3376,
                "propinsi_id" => 33,
                "name" => "KOTA TEGAL"
            ],
            [
                "id" => 3401,
                "propinsi_id" => 34,
                "name" => "KABUPATEN KULON PROGO"
            ],
            [
                "id" => 3402,
                "propinsi_id" => 34,
                "name" => "KABUPATEN BANTUL"
            ],
            [
                "id" => 3403,
                "propinsi_id" => 34,
                "name" => "KABUPATEN GUNUNG KIDUL"
            ],
            [
                "id" => 3404,
                "propinsi_id" => 34,
                "name" => "KABUPATEN SLEMAN"
            ],
            [
                "id" => 3471,
                "propinsi_id" => 34,
                "name" => "KOTA YOGYAKARTA"
            ],
            [
                "id" => 3501,
                "propinsi_id" => 35,
                "name" => "KABUPATEN PACITAN"
            ],
            [
                "id" => 3502,
                "propinsi_id" => 35,
                "name" => "KABUPATEN PONOROGO"
            ],
            [
                "id" => 3503,
                "propinsi_id" => 35,
                "name" => "KABUPATEN TRENGGALEK"
            ],
            [
                "id" => 3504,
                "propinsi_id" => 35,
                "name" => "KABUPATEN TULUNGAGUNG"
            ],
            [
                "id" => 3505,
                "propinsi_id" => 35,
                "name" => "KABUPATEN BLITAR"
            ],
            [
                "id" => 3506,
                "propinsi_id" => 35,
                "name" => "KABUPATEN KEDIRI"
            ],
            [
                "id" => 3507,
                "propinsi_id" => 35,
                "name" => "KABUPATEN MALANG"
            ],
            [
                "id" => 3508,
                "propinsi_id" => 35,
                "name" => "KABUPATEN LUMAJANG"
            ],
            [
                "id" => 3509,
                "propinsi_id" => 35,
                "name" => "KABUPATEN JEMBER"
            ],
            [
                "id" => 3510,
                "propinsi_id" => 35,
                "name" => "KABUPATEN BANYUWANGI"
            ],
            [
                "id" => 3511,
                "propinsi_id" => 35,
                "name" => "KABUPATEN BONDOWOSO"
            ],
            [
                "id" => 3512,
                "propinsi_id" => 35,
                "name" => "KABUPATEN SITUBONDO"
            ],
            [
                "id" => 3513,
                "propinsi_id" => 35,
                "name" => "KABUPATEN PROBOLINGGO"
            ],
            [
                "id" => 3514,
                "propinsi_id" => 35,
                "name" => "KABUPATEN PASURUAN"
            ],
            [
                "id" => 3515,
                "propinsi_id" => 35,
                "name" => "KABUPATEN SIDOARJO"
            ],
            [
                "id" => 3516,
                "propinsi_id" => 35,
                "name" => "KABUPATEN MOJOKERTO"
            ],
            [
                "id" => 3517,
                "propinsi_id" => 35,
                "name" => "KABUPATEN JOMBANG"
            ],
            [
                "id" => 3518,
                "propinsi_id" => 35,
                "name" => "KABUPATEN NGANJUK"
            ],
            [
                "id" => 3519,
                "propinsi_id" => 35,
                "name" => "KABUPATEN MADIUN"
            ],
            [
                "id" => 3520,
                "propinsi_id" => 35,
                "name" => "KABUPATEN MAGETAN"
            ],
            [
                "id" => 3521,
                "propinsi_id" => 35,
                "name" => "KABUPATEN NGAWI"
            ],
            [
                "id" => 3522,
                "propinsi_id" => 35,
                "name" => "KABUPATEN BOJONEGORO"
            ],
            [
                "id" => 3523,
                "propinsi_id" => 35,
                "name" => "KABUPATEN TUBAN"
            ],
            [
                "id" => 3524,
                "propinsi_id" => 35,
                "name" => "KABUPATEN LAMONGAN"
            ],
            [
                "id" => 3525,
                "propinsi_id" => 35,
                "name" => "KABUPATEN GRESIK"
            ],
            [
                "id" => 3526,
                "propinsi_id" => 35,
                "name" => "KABUPATEN BANGKALAN"
            ],
            [
                "id" => 3527,
                "propinsi_id" => 35,
                "name" => "KABUPATEN SAMPANG"
            ],
            [
                "id" => 3528,
                "propinsi_id" => 35,
                "name" => "KABUPATEN PAMEKASAN"
            ],
            [
                "id" => 3529,
                "propinsi_id" => 35,
                "name" => "KABUPATEN SUMENEP"
            ],
            [
                "id" => 3571,
                "propinsi_id" => 35,
                "name" => "KOTA KEDIRI"
            ],
            [
                "id" => 3572,
                "propinsi_id" => 35,
                "name" => "KOTA BLITAR"
            ],
            [
                "id" => 3573,
                "propinsi_id" => 35,
                "name" => "KOTA MALANG"
            ],
            [
                "id" => 3574,
                "propinsi_id" => 35,
                "name" => "KOTA PROBOLINGGO"
            ],
            [
                "id" => 3575,
                "propinsi_id" => 35,
                "name" => "KOTA PASURUAN"
            ],
            [
                "id" => 3576,
                "propinsi_id" => 35,
                "name" => "KOTA MOJOKERTO"
            ],
            [
                "id" => 3577,
                "propinsi_id" => 35,
                "name" => "KOTA MADIUN"
            ],
            [
                "id" => 3578,
                "propinsi_id" => 35,
                "name" => "KOTA SURABAYA"
            ],
            [
                "id" => 3579,
                "propinsi_id" => 35,
                "name" => "KOTA BATU"
            ],
            [
                "id" => 3601,
                "propinsi_id" => 36,
                "name" => "KABUPATEN PANDEGLANG"
            ],
            [
                "id" => 3602,
                "propinsi_id" => 36,
                "name" => "KABUPATEN LEBAK"
            ],
            [
                "id" => 3603,
                "propinsi_id" => 36,
                "name" => "KABUPATEN TANGERANG"
            ],
            [
                "id" => 3604,
                "propinsi_id" => 36,
                "name" => "KABUPATEN SERANG"
            ],
            [
                "id" => 3671,
                "propinsi_id" => 36,
                "name" => "KOTA TANGERANG"
            ],
            [
                "id" => 3672,
                "propinsi_id" => 36,
                "name" => "KOTA CILEGON"
            ],
            [
                "id" => 3673,
                "propinsi_id" => 36,
                "name" => "KOTA SERANG"
            ],
            [
                "id" => 3674,
                "propinsi_id" => 36,
                "name" => "KOTA TANGERANG SELATAN"
            ],
            [
                "id" => 5101,
                "propinsi_id" => 51,
                "name" => "KABUPATEN JEMBRANA"
            ],
            [
                "id" => 5102,
                "propinsi_id" => 51,
                "name" => "KABUPATEN TABANAN"
            ],
            [
                "id" => 5103,
                "propinsi_id" => 51,
                "name" => "KABUPATEN BADUNG"
            ],
            [
                "id" => 5104,
                "propinsi_id" => 51,
                "name" => "KABUPATEN GIANYAR"
            ],
            [
                "id" => 5105,
                "propinsi_id" => 51,
                "name" => "KABUPATEN KLUNGKUNG"
            ],
            [
                "id" => 5106,
                "propinsi_id" => 51,
                "name" => "KABUPATEN BANGLI"
            ],
            [
                "id" => 5107,
                "propinsi_id" => 51,
                "name" => "KABUPATEN KARANG ASEM"
            ],
            [
                "id" => 5108,
                "propinsi_id" => 51,
                "name" => "KABUPATEN BULELENG"
            ],
            [
                "id" => 5171,
                "propinsi_id" => 51,
                "name" => "KOTA DENPASAR"
            ],
            [
                "id" => 5201,
                "propinsi_id" => 52,
                "name" => "KABUPATEN LOMBOK BARAT"
            ],
            [
                "id" => 5202,
                "propinsi_id" => 52,
                "name" => "KABUPATEN LOMBOK TENGAH"
            ],
            [
                "id" => 5203,
                "propinsi_id" => 52,
                "name" => "KABUPATEN LOMBOK TIMUR"
            ],
            [
                "id" => 5204,
                "propinsi_id" => 52,
                "name" => "KABUPATEN SUMBAWA"
            ],
            [
                "id" => 5205,
                "propinsi_id" => 52,
                "name" => "KABUPATEN DOMPU"
            ],
            [
                "id" => 5206,
                "propinsi_id" => 52,
                "name" => "KABUPATEN BIMA"
            ],
            [
                "id" => 5207,
                "propinsi_id" => 52,
                "name" => "KABUPATEN SUMBAWA BARAT"
            ],
            [
                "id" => 5208,
                "propinsi_id" => 52,
                "name" => "KABUPATEN LOMBOK UTARA"
            ],
            [
                "id" => 5271,
                "propinsi_id" => 52,
                "name" => "KOTA MATARAM"
            ],
            [
                "id" => 5272,
                "propinsi_id" => 52,
                "name" => "KOTA BIMA"
            ],
            [
                "id" => 5301,
                "propinsi_id" => 53,
                "name" => "KABUPATEN SUMBA BARAT"
            ],
            [
                "id" => 5302,
                "propinsi_id" => 53,
                "name" => "KABUPATEN SUMBA TIMUR"
            ],
            [
                "id" => 5303,
                "propinsi_id" => 53,
                "name" => "KABUPATEN KUPANG"
            ],
            [
                "id" => 5304,
                "propinsi_id" => 53,
                "name" => "KABUPATEN TIMOR TENGAH SELATAN"
            ],
            [
                "id" => 5305,
                "propinsi_id" => 53,
                "name" => "KABUPATEN TIMOR TENGAH UTARA"
            ],
            [
                "id" => 5306,
                "propinsi_id" => 53,
                "name" => "KABUPATEN BELU"
            ],
            [
                "id" => 5307,
                "propinsi_id" => 53,
                "name" => "KABUPATEN ALOR"
            ],
            [
                "id" => 5308,
                "propinsi_id" => 53,
                "name" => "KABUPATEN LEMBATA"
            ],
            [
                "id" => 5309,
                "propinsi_id" => 53,
                "name" => "KABUPATEN FLORES TIMUR"
            ],
            [
                "id" => 5310,
                "propinsi_id" => 53,
                "name" => "KABUPATEN SIKKA"
            ],
            [
                "id" => 5311,
                "propinsi_id" => 53,
                "name" => "KABUPATEN ENDE"
            ],
            [
                "id" => 5312,
                "propinsi_id" => 53,
                "name" => "KABUPATEN NGADA"
            ],
            [
                "id" => 5313,
                "propinsi_id" => 53,
                "name" => "KABUPATEN MANGGARAI"
            ],
            [
                "id" => 5314,
                "propinsi_id" => 53,
                "name" => "KABUPATEN ROTE NDAO"
            ],
            [
                "id" => 5315,
                "propinsi_id" => 53,
                "name" => "KABUPATEN MANGGARAI BARAT"
            ],
            [
                "id" => 5316,
                "propinsi_id" => 53,
                "name" => "KABUPATEN SUMBA TENGAH"
            ],
            [
                "id" => 5317,
                "propinsi_id" => 53,
                "name" => "KABUPATEN SUMBA BARAT DAYA"
            ],
            [
                "id" => 5318,
                "propinsi_id" => 53,
                "name" => "KABUPATEN NAGEKEO"
            ],
            [
                "id" => 5319,
                "propinsi_id" => 53,
                "name" => "KABUPATEN MANGGARAI TIMUR"
            ],
            [
                "id" => 5320,
                "propinsi_id" => 53,
                "name" => "KABUPATEN SABU RAIJUA"
            ],
            [
                "id" => 5321,
                "propinsi_id" => 53,
                "name" => "KABUPATEN MALAKA"
            ],
            [
                "id" => 5371,
                "propinsi_id" => 53,
                "name" => "KOTA KUPANG"
            ],
            [
                "id" => 6101,
                "propinsi_id" => 61,
                "name" => "KABUPATEN SAMBAS"
            ],
            [
                "id" => 6102,
                "propinsi_id" => 61,
                "name" => "KABUPATEN BENGKAYANG"
            ],
            [
                "id" => 6103,
                "propinsi_id" => 61,
                "name" => "KABUPATEN LANDAK"
            ],
            [
                "id" => 6104,
                "propinsi_id" => 61,
                "name" => "KABUPATEN MEMPAWAH"
            ],
            [
                "id" => 6105,
                "propinsi_id" => 61,
                "name" => "KABUPATEN SANGGAU"
            ],
            [
                "id" => 6106,
                "propinsi_id" => 61,
                "name" => "KABUPATEN KETAPANG"
            ],
            [
                "id" => 6107,
                "propinsi_id" => 61,
                "name" => "KABUPATEN SINTANG"
            ],
            [
                "id" => 6108,
                "propinsi_id" => 61,
                "name" => "KABUPATEN KAPUAS HULU"
            ],
            [
                "id" => 6109,
                "propinsi_id" => 61,
                "name" => "KABUPATEN SEKADAU"
            ],
            [
                "id" => 6110,
                "propinsi_id" => 61,
                "name" => "KABUPATEN MELAWI"
            ],
            [
                "id" => 6111,
                "propinsi_id" => 61,
                "name" => "KABUPATEN KAYONG UTARA"
            ],
            [
                "id" => 6112,
                "propinsi_id" => 61,
                "name" => "KABUPATEN KUBU RAYA"
            ],
            [
                "id" => 6171,
                "propinsi_id" => 61,
                "name" => "KOTA PONTIANAK"
            ],
            [
                "id" => 6172,
                "propinsi_id" => 61,
                "name" => "KOTA SINGKAWANG"
            ],
            [
                "id" => 6201,
                "propinsi_id" => 62,
                "name" => "KABUPATEN KOTAWARINGIN BARAT"
            ],
            [
                "id" => 6202,
                "propinsi_id" => 62,
                "name" => "KABUPATEN KOTAWARINGIN TIMUR"
            ],
            [
                "id" => 6203,
                "propinsi_id" => 62,
                "name" => "KABUPATEN KAPUAS"
            ],
            [
                "id" => 6204,
                "propinsi_id" => 62,
                "name" => "KABUPATEN BARITO SELATAN"
            ],
            [
                "id" => 6205,
                "propinsi_id" => 62,
                "name" => "KABUPATEN BARITO UTARA"
            ],
            [
                "id" => 6206,
                "propinsi_id" => 62,
                "name" => "KABUPATEN SUKAMARA"
            ],
            [
                "id" => 6207,
                "propinsi_id" => 62,
                "name" => "KABUPATEN LAMANDAU"
            ],
            [
                "id" => 6208,
                "propinsi_id" => 62,
                "name" => "KABUPATEN SERUYAN"
            ],
            [
                "id" => 6209,
                "propinsi_id" => 62,
                "name" => "KABUPATEN KATINGAN"
            ],
            [
                "id" => 6210,
                "propinsi_id" => 62,
                "name" => "KABUPATEN PULANG PISAU"
            ],
            [
                "id" => 6211,
                "propinsi_id" => 62,
                "name" => "KABUPATEN GUNUNG MAS"
            ],
            [
                "id" => 6212,
                "propinsi_id" => 62,
                "name" => "KABUPATEN BARITO TIMUR"
            ],
            [
                "id" => 6213,
                "propinsi_id" => 62,
                "name" => "KABUPATEN MURUNG RAYA"
            ],
            [
                "id" => 6271,
                "propinsi_id" => 62,
                "name" => "KOTA PALANGKA RAYA"
            ],
            [
                "id" => 6301,
                "propinsi_id" => 63,
                "name" => "KABUPATEN TANAH LAUT"
            ],
            [
                "id" => 6302,
                "propinsi_id" => 63,
                "name" => "KABUPATEN KOTA BARU"
            ],
            [
                "id" => 6303,
                "propinsi_id" => 63,
                "name" => "KABUPATEN BANJAR"
            ],
            [
                "id" => 6304,
                "propinsi_id" => 63,
                "name" => "KABUPATEN BARITO KUALA"
            ],
            [
                "id" => 6305,
                "propinsi_id" => 63,
                "name" => "KABUPATEN TAPIN"
            ],
            [
                "id" => 6306,
                "propinsi_id" => 63,
                "name" => "KABUPATEN HULU SUNGAI SELATAN"
            ],
            [
                "id" => 6307,
                "propinsi_id" => 63,
                "name" => "KABUPATEN HULU SUNGAI TENGAH"
            ],
            [
                "id" => 6308,
                "propinsi_id" => 63,
                "name" => "KABUPATEN HULU SUNGAI UTARA"
            ],
            [
                "id" => 6309,
                "propinsi_id" => 63,
                "name" => "KABUPATEN TABALONG"
            ],
            [
                "id" => 6310,
                "propinsi_id" => 63,
                "name" => "KABUPATEN TANAH BUMBU"
            ],
            [
                "id" => 6311,
                "propinsi_id" => 63,
                "name" => "KABUPATEN BALANGAN"
            ],
            [
                "id" => 6371,
                "propinsi_id" => 63,
                "name" => "KOTA BANJARMASIN"
            ],
            [
                "id" => 6372,
                "propinsi_id" => 63,
                "name" => "KOTA BANJAR BARU"
            ],
            [
                "id" => 6401,
                "propinsi_id" => 64,
                "name" => "KABUPATEN PASER"
            ],
            [
                "id" => 6402,
                "propinsi_id" => 64,
                "name" => "KABUPATEN KUTAI BARAT"
            ],
            [
                "id" => 6403,
                "propinsi_id" => 64,
                "name" => "KABUPATEN KUTAI KARTANEGARA"
            ],
            [
                "id" => 6404,
                "propinsi_id" => 64,
                "name" => "KABUPATEN KUTAI TIMUR"
            ],
            [
                "id" => 6405,
                "propinsi_id" => 64,
                "name" => "KABUPATEN BERAU"
            ],
            [
                "id" => 6409,
                "propinsi_id" => 64,
                "name" => "KABUPATEN PENAJAM PASER UTARA"
            ],
            [
                "id" => 6411,
                "propinsi_id" => 64,
                "name" => "KABUPATEN MAHAKAM HULU"
            ],
            [
                "id" => 6471,
                "propinsi_id" => 64,
                "name" => "KOTA BALIKPAPAN"
            ],
            [
                "id" => 6472,
                "propinsi_id" => 64,
                "name" => "KOTA SAMARINDA"
            ],
            [
                "id" => 6474,
                "propinsi_id" => 64,
                "name" => "KOTA BONTANG"
            ],
            [
                "id" => 6501,
                "propinsi_id" => 65,
                "name" => "KABUPATEN MALINAU"
            ],
            [
                "id" => 6502,
                "propinsi_id" => 65,
                "name" => "KABUPATEN BULUNGAN"
            ],
            [
                "id" => 6503,
                "propinsi_id" => 65,
                "name" => "KABUPATEN TANA TIDUNG"
            ],
            [
                "id" => 6504,
                "propinsi_id" => 65,
                "name" => "KABUPATEN NUNUKAN"
            ],
            [
                "id" => 6571,
                "propinsi_id" => 65,
                "name" => "KOTA TARAKAN"
            ],
            [
                "id" => 7101,
                "propinsi_id" => 71,
                "name" => "KABUPATEN BOLAANG MONGONDOW"
            ],
            [
                "id" => 7102,
                "propinsi_id" => 71,
                "name" => "KABUPATEN MINAHASA"
            ],
            [
                "id" => 7103,
                "propinsi_id" => 71,
                "name" => "KABUPATEN KEPULAUAN SANGIHE"
            ],
            [
                "id" => 7104,
                "propinsi_id" => 71,
                "name" => "KABUPATEN KEPULAUAN TALAUD"
            ],
            [
                "id" => 7105,
                "propinsi_id" => 71,
                "name" => "KABUPATEN MINAHASA SELATAN"
            ],
            [
                "id" => 7106,
                "propinsi_id" => 71,
                "name" => "KABUPATEN MINAHASA UTARA"
            ],
            [
                "id" => 7107,
                "propinsi_id" => 71,
                "name" => "KABUPATEN BOLAANG MONGONDOW UTARA"
            ],
            [
                "id" => 7108,
                "propinsi_id" => 71,
                "name" => "KABUPATEN SIAU TAGULANDANG BIARO"
            ],
            [
                "id" => 7109,
                "propinsi_id" => 71,
                "name" => "KABUPATEN MINAHASA TENGGARA"
            ],
            [
                "id" => 7110,
                "propinsi_id" => 71,
                "name" => "KABUPATEN BOLAANG MONGONDOW SELATAN"
            ],
            [
                "id" => 7111,
                "propinsi_id" => 71,
                "name" => "KABUPATEN BOLAANG MONGONDOW TIMUR"
            ],
            [
                "id" => 7171,
                "propinsi_id" => 71,
                "name" => "KOTA MANADO"
            ],
            [
                "id" => 7172,
                "propinsi_id" => 71,
                "name" => "KOTA BITUNG"
            ],
            [
                "id" => 7173,
                "propinsi_id" => 71,
                "name" => "KOTA TOMOHON"
            ],
            [
                "id" => 7174,
                "propinsi_id" => 71,
                "name" => "KOTA KOTAMOBAGU"
            ],
            [
                "id" => 7201,
                "propinsi_id" => 72,
                "name" => "KABUPATEN BANGGAI KEPULAUAN"
            ],
            [
                "id" => 7202,
                "propinsi_id" => 72,
                "name" => "KABUPATEN BANGGAI"
            ],
            [
                "id" => 7203,
                "propinsi_id" => 72,
                "name" => "KABUPATEN MOROWALI"
            ],
            [
                "id" => 7204,
                "propinsi_id" => 72,
                "name" => "KABUPATEN POSO"
            ],
            [
                "id" => 7205,
                "propinsi_id" => 72,
                "name" => "KABUPATEN DONGGALA"
            ],
            [
                "id" => 7206,
                "propinsi_id" => 72,
                "name" => "KABUPATEN TOLI-TOLI"
            ],
            [
                "id" => 7207,
                "propinsi_id" => 72,
                "name" => "KABUPATEN BUOL"
            ],
            [
                "id" => 7208,
                "propinsi_id" => 72,
                "name" => "KABUPATEN PARIGI MOUTONG"
            ],
            [
                "id" => 7209,
                "propinsi_id" => 72,
                "name" => "KABUPATEN TOJO UNA-UNA"
            ],
            [
                "id" => 7210,
                "propinsi_id" => 72,
                "name" => "KABUPATEN SIGI"
            ],
            [
                "id" => 7211,
                "propinsi_id" => 72,
                "name" => "KABUPATEN BANGGAI LAUT"
            ],
            [
                "id" => 7212,
                "propinsi_id" => 72,
                "name" => "KABUPATEN MOROWALI UTARA"
            ],
            [
                "id" => 7271,
                "propinsi_id" => 72,
                "name" => "KOTA PALU"
            ],
            [
                "id" => 7301,
                "propinsi_id" => 73,
                "name" => "KABUPATEN KEPULAUAN SELAYAR"
            ],
            [
                "id" => 7302,
                "propinsi_id" => 73,
                "name" => "KABUPATEN BULUKUMBA"
            ],
            [
                "id" => 7303,
                "propinsi_id" => 73,
                "name" => "KABUPATEN BANTAENG"
            ],
            [
                "id" => 7304,
                "propinsi_id" => 73,
                "name" => "KABUPATEN JENEPONTO"
            ],
            [
                "id" => 7305,
                "propinsi_id" => 73,
                "name" => "KABUPATEN TAKALAR"
            ],
            [
                "id" => 7306,
                "propinsi_id" => 73,
                "name" => "KABUPATEN GOWA"
            ],
            [
                "id" => 7307,
                "propinsi_id" => 73,
                "name" => "KABUPATEN SINJAI"
            ],
            [
                "id" => 7308,
                "propinsi_id" => 73,
                "name" => "KABUPATEN MAROS"
            ],
            [
                "id" => 7309,
                "propinsi_id" => 73,
                "name" => "KABUPATEN PANGKAJENE DAN KEPULAUAN"
            ],
            [
                "id" => 7310,
                "propinsi_id" => 73,
                "name" => "KABUPATEN BARRU"
            ],
            [
                "id" => 7311,
                "propinsi_id" => 73,
                "name" => "KABUPATEN BONE"
            ],
            [
                "id" => 7312,
                "propinsi_id" => 73,
                "name" => "KABUPATEN SOPPENG"
            ],
            [
                "id" => 7313,
                "propinsi_id" => 73,
                "name" => "KABUPATEN WAJO"
            ],
            [
                "id" => 7314,
                "propinsi_id" => 73,
                "name" => "KABUPATEN SIDENRENG RAPPANG"
            ],
            [
                "id" => 7315,
                "propinsi_id" => 73,
                "name" => "KABUPATEN PINRANG"
            ],
            [
                "id" => 7316,
                "propinsi_id" => 73,
                "name" => "KABUPATEN ENREKANG"
            ],
            [
                "id" => 7317,
                "propinsi_id" => 73,
                "name" => "KABUPATEN LUWU"
            ],
            [
                "id" => 7318,
                "propinsi_id" => 73,
                "name" => "KABUPATEN TANA TORAJA"
            ],
            [
                "id" => 7322,
                "propinsi_id" => 73,
                "name" => "KABUPATEN LUWU UTARA"
            ],
            [
                "id" => 7325,
                "propinsi_id" => 73,
                "name" => "KABUPATEN LUWU TIMUR"
            ],
            [
                "id" => 7326,
                "propinsi_id" => 73,
                "name" => "KABUPATEN TORAJA UTARA"
            ],
            [
                "id" => 7371,
                "propinsi_id" => 73,
                "name" => "KOTA MAKASSAR"
            ],
            [
                "id" => 7372,
                "propinsi_id" => 73,
                "name" => "KOTA PAREPARE"
            ],
            [
                "id" => 7373,
                "propinsi_id" => 73,
                "name" => "KOTA PALOPO"
            ],
            [
                "id" => 7401,
                "propinsi_id" => 74,
                "name" => "KABUPATEN BUTON"
            ],
            [
                "id" => 7402,
                "propinsi_id" => 74,
                "name" => "KABUPATEN MUNA"
            ],
            [
                "id" => 7403,
                "propinsi_id" => 74,
                "name" => "KABUPATEN KONAWE"
            ],
            [
                "id" => 7404,
                "propinsi_id" => 74,
                "name" => "KABUPATEN KOLAKA"
            ],
            [
                "id" => 7405,
                "propinsi_id" => 74,
                "name" => "KABUPATEN KONAWE SELATAN"
            ],
            [
                "id" => 7406,
                "propinsi_id" => 74,
                "name" => "KABUPATEN BOMBANA"
            ],
            [
                "id" => 7407,
                "propinsi_id" => 74,
                "name" => "KABUPATEN WAKATOBI"
            ],
            [
                "id" => 7408,
                "propinsi_id" => 74,
                "name" => "KABUPATEN KOLAKA UTARA"
            ],
            [
                "id" => 7409,
                "propinsi_id" => 74,
                "name" => "KABUPATEN BUTON UTARA"
            ],
            [
                "id" => 7410,
                "propinsi_id" => 74,
                "name" => "KABUPATEN KONAWE UTARA"
            ],
            [
                "id" => 7411,
                "propinsi_id" => 74,
                "name" => "KABUPATEN KOLAKA TIMUR"
            ],
            [
                "id" => 7412,
                "propinsi_id" => 74,
                "name" => "KABUPATEN KONAWE KEPULAUAN"
            ],
            [
                "id" => 7413,
                "propinsi_id" => 74,
                "name" => "KABUPATEN MUNA BARAT"
            ],
            [
                "id" => 7414,
                "propinsi_id" => 74,
                "name" => "KABUPATEN BUTON TENGAH"
            ],
            [
                "id" => 7415,
                "propinsi_id" => 74,
                "name" => "KABUPATEN BUTON SELATAN"
            ],
            [
                "id" => 7471,
                "propinsi_id" => 74,
                "name" => "KOTA KENDARI"
            ],
            [
                "id" => 7472,
                "propinsi_id" => 74,
                "name" => "KOTA BAUBAU"
            ],
            [
                "id" => 7501,
                "propinsi_id" => 75,
                "name" => "KABUPATEN BOALEMO"
            ],
            [
                "id" => 7502,
                "propinsi_id" => 75,
                "name" => "KABUPATEN GORONTALO"
            ],
            [
                "id" => 7503,
                "propinsi_id" => 75,
                "name" => "KABUPATEN POHUWATO"
            ],
            [
                "id" => 7504,
                "propinsi_id" => 75,
                "name" => "KABUPATEN BONE BOLANGO"
            ],
            [
                "id" => 7505,
                "propinsi_id" => 75,
                "name" => "KABUPATEN GORONTALO UTARA"
            ],
            [
                "id" => 7571,
                "propinsi_id" => 75,
                "name" => "KOTA GORONTALO"
            ],
            [
                "id" => 7601,
                "propinsi_id" => 76,
                "name" => "KABUPATEN MAJENE"
            ],
            [
                "id" => 7602,
                "propinsi_id" => 76,
                "name" => "KABUPATEN POLEWALI MANDAR"
            ],
            [
                "id" => 7603,
                "propinsi_id" => 76,
                "name" => "KABUPATEN MAMASA"
            ],
            [
                "id" => 7604,
                "propinsi_id" => 76,
                "name" => "KABUPATEN MAMUJU"
            ],
            [
                "id" => 7605,
                "propinsi_id" => 76,
                "name" => "KABUPATEN MAMUJU UTARA"
            ],
            [
                "id" => 7606,
                "propinsi_id" => 76,
                "name" => "KABUPATEN MAMUJU TENGAH"
            ],
            [
                "id" => 8101,
                "propinsi_id" => 81,
                "name" => "KABUPATEN MALUKU TENGGARA BARAT"
            ],
            [
                "id" => 8102,
                "propinsi_id" => 81,
                "name" => "KABUPATEN MALUKU TENGGARA"
            ],
            [
                "id" => 8103,
                "propinsi_id" => 81,
                "name" => "KABUPATEN MALUKU TENGAH"
            ],
            [
                "id" => 8104,
                "propinsi_id" => 81,
                "name" => "KABUPATEN BURU"
            ],
            [
                "id" => 8105,
                "propinsi_id" => 81,
                "name" => "KABUPATEN KEPULAUAN ARU"
            ],
            [
                "id" => 8106,
                "propinsi_id" => 81,
                "name" => "KABUPATEN SERAM BAGIAN BARAT"
            ],
            [
                "id" => 8107,
                "propinsi_id" => 81,
                "name" => "KABUPATEN SERAM BAGIAN TIMUR"
            ],
            [
                "id" => 8108,
                "propinsi_id" => 81,
                "name" => "KABUPATEN MALUKU BARAT DAYA"
            ],
            [
                "id" => 8109,
                "propinsi_id" => 81,
                "name" => "KABUPATEN BURU SELATAN"
            ],
            [
                "id" => 8171,
                "propinsi_id" => 81,
                "name" => "KOTA AMBON"
            ],
            [
                "id" => 8172,
                "propinsi_id" => 81,
                "name" => "KOTA TUAL"
            ],
            [
                "id" => 8201,
                "propinsi_id" => 82,
                "name" => "KABUPATEN HALMAHERA BARAT"
            ],
            [
                "id" => 8202,
                "propinsi_id" => 82,
                "name" => "KABUPATEN HALMAHERA TENGAH"
            ],
            [
                "id" => 8203,
                "propinsi_id" => 82,
                "name" => "KABUPATEN KEPULAUAN SULA"
            ],
            [
                "id" => 8204,
                "propinsi_id" => 82,
                "name" => "KABUPATEN HALMAHERA SELATAN"
            ],
            [
                "id" => 8205,
                "propinsi_id" => 82,
                "name" => "KABUPATEN HALMAHERA UTARA"
            ],
            [
                "id" => 8206,
                "propinsi_id" => 82,
                "name" => "KABUPATEN HALMAHERA TIMUR"
            ],
            [
                "id" => 8207,
                "propinsi_id" => 82,
                "name" => "KABUPATEN PULAU MOROTAI"
            ],
            [
                "id" => 8208,
                "propinsi_id" => 82,
                "name" => "KABUPATEN PULAU TALIABU"
            ],
            [
                "id" => 8271,
                "propinsi_id" => 82,
                "name" => "KOTA TERNATE"
            ],
            [
                "id" => 8272,
                "propinsi_id" => 82,
                "name" => "KOTA TIDORE KEPULAUAN"
            ],
            [
                "id" => 9101,
                "propinsi_id" => 91,
                "name" => "KABUPATEN FAKFAK"
            ],
            [
                "id" => 9102,
                "propinsi_id" => 91,
                "name" => "KABUPATEN KAIMANA"
            ],
            [
                "id" => 9103,
                "propinsi_id" => 91,
                "name" => "KABUPATEN TELUK WONDAMA"
            ],
            [
                "id" => 9104,
                "propinsi_id" => 91,
                "name" => "KABUPATEN TELUK BINTUNI"
            ],
            [
                "id" => 9105,
                "propinsi_id" => 91,
                "name" => "KABUPATEN MANOKWARI"
            ],
            [
                "id" => 9106,
                "propinsi_id" => 91,
                "name" => "KABUPATEN SORONG SELATAN"
            ],
            [
                "id" => 9107,
                "propinsi_id" => 91,
                "name" => "KABUPATEN SORONG"
            ],
            [
                "id" => 9108,
                "propinsi_id" => 91,
                "name" => "KABUPATEN RAJA AMPAT"
            ],
            [
                "id" => 9109,
                "propinsi_id" => 91,
                "name" => "KABUPATEN TAMBRAUW"
            ],
            [
                "id" => 9110,
                "propinsi_id" => 91,
                "name" => "KABUPATEN MAYBRAT"
            ],
            [
                "id" => 9111,
                "propinsi_id" => 91,
                "name" => "KABUPATEN MANOKWARI SELATAN"
            ],
            [
                "id" => 9112,
                "propinsi_id" => 91,
                "name" => "KABUPATEN PEGUNUNGAN ARFAK"
            ],
            [
                "id" => 9171,
                "propinsi_id" => 91,
                "name" => "KOTA SORONG"
            ],
            [
                "id" => 9401,
                "propinsi_id" => 94,
                "name" => "KABUPATEN MERAUKE"
            ],
            [
                "id" => 9402,
                "propinsi_id" => 94,
                "name" => "KABUPATEN JAYAWIJAYA"
            ],
            [
                "id" => 9403,
                "propinsi_id" => 94,
                "name" => "KABUPATEN JAYAPURA"
            ],
            [
                "id" => 9404,
                "propinsi_id" => 94,
                "name" => "KABUPATEN NABIRE"
            ],
            [
                "id" => 9408,
                "propinsi_id" => 94,
                "name" => "KABUPATEN KEPULAUAN YAPEN"
            ],
            [
                "id" => 9409,
                "propinsi_id" => 94,
                "name" => "KABUPATEN BIAK NUMFOR"
            ],
            [
                "id" => 9410,
                "propinsi_id" => 94,
                "name" => "KABUPATEN PANIAI"
            ],
            [
                "id" => 9411,
                "propinsi_id" => 94,
                "name" => "KABUPATEN PUNCAK JAYA"
            ],
            [
                "id" => 9412,
                "propinsi_id" => 94,
                "name" => "KABUPATEN MIMIKA"
            ],
            [
                "id" => 9413,
                "propinsi_id" => 94,
                "name" => "KABUPATEN BOVEN DIGOEL"
            ],
            [
                "id" => 9414,
                "propinsi_id" => 94,
                "name" => "KABUPATEN MAPPI"
            ],
            [
                "id" => 9415,
                "propinsi_id" => 94,
                "name" => "KABUPATEN ASMAT"
            ],
            [
                "id" => 9416,
                "propinsi_id" => 94,
                "name" => "KABUPATEN YAHUKIMO"
            ],
            [
                "id" => 9417,
                "propinsi_id" => 94,
                "name" => "KABUPATEN PEGUNUNGAN BINTANG"
            ],
            [
                "id" => 9418,
                "propinsi_id" => 94,
                "name" => "KABUPATEN TOLIKARA"
            ],
            [
                "id" => 9419,
                "propinsi_id" => 94,
                "name" => "KABUPATEN SARMI"
            ],
            [
                "id" => 9420,
                "propinsi_id" => 94,
                "name" => "KABUPATEN KEEROM"
            ],
            [
                "id" => 9426,
                "propinsi_id" => 94,
                "name" => "KABUPATEN WAROPEN"
            ],
            [
                "id" => 9427,
                "propinsi_id" => 94,
                "name" => "KABUPATEN SUPIORI"
            ],
            [
                "id" => 9428,
                "propinsi_id" => 94,
                "name" => "KABUPATEN MAMBERAMO RAYA"
            ],
            [
                "id" => 9429,
                "propinsi_id" => 94,
                "name" => "KABUPATEN NDUGA"
            ],
            [
                "id" => 9430,
                "propinsi_id" => 94,
                "name" => "KABUPATEN LANNY JAYA"
            ],
            [
                "id" => 9431,
                "propinsi_id" => 94,
                "name" => "KABUPATEN MAMBERAMO TENGAH"
            ],
            [
                "id" => 9432,
                "propinsi_id" => 94,
                "name" => "KABUPATEN YALIMO"
            ],
            [
                "id" => 9433,
                "propinsi_id" => 94,
                "name" => "KABUPATEN PUNCAK"
            ],
            [
                "id" => 9434,
                "propinsi_id" => 94,
                "name" => "KABUPATEN DOGIYAI"
            ],
            [
                "id" => 9435,
                "propinsi_id" => 94,
                "name" => "KABUPATEN INTAN JAYA"
            ],
            [
                "id" => 9436,
                "propinsi_id" => 94,
                "name" => "KABUPATEN DEIYAI"
            ],
            [
                "id" => 9471,
                "propinsi_id" => 94,
                "name" => "KOTA JAYAPURA"
            ]
        ];

        Kabupaten::insert($kabupatens);
    }
}
