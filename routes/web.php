<?php

use App\Exports\checklistExport;
use App\Http\Controllers\CetakKartuController;
use App\Http\Controllers\ExportKaryawans;
use App\Http\Controllers\McuCetak;
use App\Http\Controllers\QueueController;
use App\Imports\checklistImport;
use App\Livewire\Cetak\Id as CetakId;
use App\Livewire\Cetak\Kimper as CetakKimper;
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
use App\Livewire\Profil\Password;
use App\Livewire\Report;
use App\Livewire\Users\Users;
use App\Livewire\Versatility\Versatility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', Home::class)->name('home');
    Route::get('profil', Password::class)->name('profil');
    Route::group(['middleware' => ['role:superadmin,admin,she']], function () {
        Route::get('karyawan', Karyawan::class)->name('karyawan');
    });
    Route::get('profil', Password::class)->name('profil');
    Route::get('mcu', Mcu::class)->name('mcu');
    Route::get('histori-mcu', McuMcu::class)->name('histori-mcu');
    Route::get('report', Report::class)->name('report');

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
        Route::get('cetak-kimper', CetakKimper::class)->name('kimper-karyawan');
        Route::get('cetak-kartu/{id}', [CetakKartuController::class, 'cetak']);
        Route::get('cetak-kartu-kimper/{id}', [CetakKartuController::class, 'cetakKimper']);
    });
    Route::group(['middleware' => ['role:superadmin,dokter']], function () {
        Route::get('report-mcu/{date_mcu1}/{date_mcu2}', [Report::class, 'mcuReport'])->name('report-mcu');
        Route::get('export-hasil-mcu', [Mcu::class, 'exportExcel'])->name('export-hasil-mcu');
        Route::get('cetak-mcu/{id}', [McuCetak::class, 'cetak']);
        Route::get('cetak-mcu-sub/{id}', [McuCetak::class, 'cetakSub']);
        Route::get('cetak-skd/{id}', [McuCetak::class, 'skd']);
        Route::get('cetak-laik/{id}', [McuCetak::class, 'cetakLaik']);
        Route::get('cetak-reportId/{date_id1}/{date_id2}', [McuCetak::class, 'reportId'])->name('cetak-reportId');
        Route::get('cetak-reportKimper/{date1}/{date2}', [McuCetak::class, 'reportKimper'])->name('cetak-reportKimper');
    });
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('report-mcu/{date_mcu1}/{date_mcu2}', [Report::class, 'mcuReport'])->name('report-mcu');
        Route::get('cetak-mcu/{id}', [McuCetak::class, 'cetak']);
        Route::get('cetak-mcu-sub/{id}', [McuCetak::class, 'cetakSub']);
        Route::get('cetak-skd/{id}', [McuCetak::class, 'skd']);
        Route::get('cetak-laik/{id}', [McuCetak::class, 'cetakLaik']);
        Route::get('cetak-reportId/{date_id1}/{date_id2}', [McuCetak::class, 'reportId'])->name('cetak-reportId');
        Route::get('cetak-reportKimper/{date1}/{date2}', [McuCetak::class, 'reportKimper'])->name('cetak-reportKimper');
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
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
});
Auth::routes([
    'register' => false, // Register Routes...
    'reset' => false, // Reset Password Routes...
    'verify' => false, // Email Verification Routes...
]);
Route::get('/run-queue-once/{token}', [QueueController::class, 'runQueueOnce']);
