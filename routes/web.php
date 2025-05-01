<?php

use App\Exports\checklistExport;
use App\Http\Controllers\CetakKartuController;
use App\Http\Controllers\ExportKaryawans;
use App\Http\Controllers\McuCetak;
use App\Imports\checklistImport;
use App\Livewire\Cetak\Id as CetakId;
use App\Livewire\CetakKartuID;
use App\Livewire\Departments\Departments;
use App\Livewire\Histori\Id as HistoriId;
use App\Livewire\Histori\Kimper as HistoriKimper;
use App\Livewire\Histori\Mcu\Mcu as McuMcu;
use App\Livewire\Home\Home;
use App\Livewire\Jabatan\Jabatan;
use App\Livewire\Karyawan\Karyawan;
use App\Livewire\Mcu\Mcu;
use App\Livewire\Page\Notfound;
use App\Livewire\Pengajuan\Id;
use App\Livewire\Pengajuan\Kimper;
use App\Livewire\Perusahaan\Perusahaan;
use App\Livewire\Users\Users;
use App\Livewire\Versatility\Versatility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', Home::class)->name('home');
    Route::get('karyawan', Karyawan::class)->name('karyawan');
    Route::get('mcu', Mcu::class)->name('mcu');
    Route::get('histori-mcu', McuMcu::class)->name('histori-mcu');
    Route::group(['middleware' => ['role:superadmin']], function () {
        //Route::get('export-karyawans', [Karyawan::class, 'export'])->name('export-karyawans');
        Route::get('users', Users::class)->name('users');
        Route::get('departments', Departments::class)->name('departments');
        Route::get('perusahaan', Perusahaan::class)->name('perusahaan');
        Route::get('jabatan', Jabatan::class)->name('jabatan');
        Route::get('versatility', Versatility::class)->name('versatility');
    });
    Route::group(['middleware' => ['role:superadmin,admin,she']], function () {
        Route::get('pengajuan-id', Id::class)->name('pengajuan-id');
        Route::get('pengajuan-kimper', Kimper::class)->name('pengajuan-kimper');
        Route::get('histori-id', HistoriId::class)->name('histori-id');
        Route::get('histori-kimper', HistoriKimper::class)->name('histori-kimper');
    });
    Route::group(['middleware' => ['role:superadmin,she']], function () {
        Route::get('cetak-id', CetakId::class)->name('id-karyawan');
        Route::get('cetak-kartu/{id}', [CetakKartuController::class, 'cetak']);
    });
    Route::group(['middleware' => ['role:superadmin,dokter']], function () {
        Route::get('cetak-mcu/{id}', [McuCetak::class, 'cetak']);
        Route::get('cetak-mcu-sub/{id}', [McuCetak::class, 'cetakSub']);
        Route::get('cetak-skd/{id}', [McuCetak::class, 'skd']);
    });
});

Route::get('/try-export', function () {
    return Excel::download(new checklistExport, 'data-karyawan' . date('Y-m-d') . time() . '.xlsx');
})->name('try-export');

Route::get('/try-import', function () {
    $filePath = storage_path('app/public/try-import2.xlsx');
    try {
        Excel::import(new checklistImport, $filePath);
        return '✅ Import berhasil dari storage!';
    } catch (\Exception $e) {
        return '❌ Gagal import: ' . $e->getMessage();
    }
});

Route::get('/notfound/{page}', Notfound::class);
Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', function () {
        return view('auth.login');
    });
});
Auth::routes([
    'register' => false, // Register Routes...
    'reset' => false, // Reset Password Routes...
    'verify' => false, // Email Verification Routes...
]);
