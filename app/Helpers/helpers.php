<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Storage;

function cekFile($path)
{
    if ($path && Storage::disk('public')->exists($path)) {
        return $path . "ada";
    } else {
        return $path . "tidak ada";
    }
}
if (!function_exists('getInfoKaryawanByNrpDept')) {
    function getInfoKaryawanByNrpDept($nrp): ?string
    {
        $karyawan = Karyawan::where('nrp', $nrp)->first();

        if (!$karyawan) {
            return null; // atau bisa return 'NRP tidak ditemukan' jika ingin hard fail
        }

        return "{$karyawan->dept}";
    }
}
if (!function_exists('getInfoKaryawanByNrp')) {
    function getInfoKaryawanByNrp($nrp): ?string
    {
        $karyawan = Karyawan::where('nrp', $nrp)->first();

        if (!$karyawan) {
            return null; // atau bisa return 'NRP tidak ditemukan' jika ingin hard fail
        }

        return "{$karyawan->nrp} - {$karyawan->nama} - {$karyawan->jabatan}/{$karyawan->dept}";
    }
}
if (!function_exists('getUserInfo')) {
    function getUserInfo(): array
    {
        $user = Auth::user();
        $nama = $user->name ?? $user->nama ?? 'User';

        $labelZona = match (config('app.timezone')) {
            'Asia/Jakarta' => 'WIB',
            'Asia/Makassar' => 'WITA',
            'Asia/Jayapura' => 'WIT',
            default => '',
        };

        $waktu = now()->format('d-m-Y H:i:s') . ' ' . $labelZona;

        return [
            'nama' => $nama,
            'waktu' => $waktu,
            'adminENG' => [
                '082276297441'
            ],
            'adminPRO' => [
                '081117049395'
            ],
            'adminPLT' => [
                '081117049394'
            ],
            'adminHC' => [
                '08116890789'
            ],
            'adminCOE' => [
                '08116830966'
            ],
            'nomorAdmins' => [
                '088212543694', //saya
                //'085954590940', // yazid
                //'08991649871', // mas candra
                //'082266012957' // mas anton
            ],
            'nomorParamedik' => [
                '088212543694', //saya
                //'085954590940', // yazid
                //'085879793321', //masbondan
                //'08991649871' // mas candra
                //'088212543694',
                //'085954590940',
            ],
            'nomorDokter' => [
                '088212543694', //saya
                //'085879793321', //masbondan
                //'08991649871', // mas candra
                //'088212543694',
                //'085954590940',
            ],
            'token' => env('PESAN_TOKEN', 'abc25qc'),
        ];
    }
}

if (!function_exists('pesan')) {
    function pesan($nohp, $pesan, $token)
    {
        $tokenValid = env('PESAN_TOKEN', 'abc25qc');

        if (empty($nohp)) {
            $nohp = '088212543694';
        }

        if ($token !== $tokenValid) {
            return ['success' => false, 'message' => 'Token salah'];
        }

        $info = getUserInfo(); // Ambil data user & waktu dari helper

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://pesan.buanatechno.id/send-message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'message' => $pesan,
                'number' => $nohp,
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return ['success' => false, 'message' => $err];
        }

        return ['success' => true, 'response' => $response];
    }
}
class ExcelHelper
{
    /**
     * Ambil value dari kolom excel
     * - Jika kosong, return null
     * - Jika ada, bisa langsung strtoupper atau manipulasi lain
     */
    public static function getValue($row, $index, $uppercase = false)
    {
        if (!isset($row[$index]) || trim($row[$index]) === '') {
            return null;
        }

        $value = trim($row[$index]);

        return $uppercase ? strtoupper($value) : $value;
    }
}
