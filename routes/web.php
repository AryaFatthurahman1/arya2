<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\SatuanKerjaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;

// Public Routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.welcome');
    }
    return view('auth.login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Dashboard Welcome (landing page after login)
    Route::get('/dashboard', [DashboardController::class, 'welcome'])->name('dashboard.welcome');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    // 2FA
    Route::get('/2fa', [AuthController::class, 'showTwoFactorForm'])->name('2fa.show');
    Route::post('/2fa', [AuthController::class, 'verifyTwoFactor'])->name('2fa.verify');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // === Employee Management ===
    Route::prefix('dashboard/employees')->name('karyawan.')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('index');
        Route::get('/create', [KaryawanController::class, 'create'])->name('create');
        Route::post('/', [KaryawanController::class, 'store'])->name('store');
        Route::get('/{karyawan}', [KaryawanController::class, 'show'])->name('show');
        Route::get('/{karyawan}/edit', [KaryawanController::class, 'edit'])->name('edit');
        Route::patch('/{karyawan}', [KaryawanController::class, 'update'])->name('update');
        Route::delete('/{karyawan}', [KaryawanController::class, 'destroy'])->name('destroy');
        Route::get('/export/data', [KaryawanController::class, 'export'])->name('export');
        Route::get('/download-template', [KaryawanController::class, 'downloadTemplate'])->name('download-template');
        Route::post('/import/data', [KaryawanController::class, 'import'])->name('import');
    });

    // === Attendance Management ===
    Route::prefix('dashboard/attendance')->name('absensi.')->group(function () {
        Route::get('/', [AbsensiController::class, 'index'])->name('index');
        Route::get('/create', [AbsensiController::class, 'create'])->name('create');
        Route::post('/', [AbsensiController::class, 'store'])->name('store');
        Route::get('/{absensi}', [AbsensiController::class, 'show'])->name('show');
        Route::get('/{absensi}/edit', [AbsensiController::class, 'edit'])->name('edit');
        Route::patch('/{absensi}', [AbsensiController::class, 'update'])->name('update');
        Route::delete('/{absensi}', [AbsensiController::class, 'destroy'])->name('destroy');
        Route::get('/online/clock', [AbsensiController::class, 'absenOnline'])->name('online');
        Route::post('/clock-in', [AbsensiController::class, 'clockIn'])->name('clock-in');
        Route::get('/export/data', [AbsensiController::class, 'export'])->name('export');
        Route::post('/import/data', [AbsensiController::class, 'import'])->name('import');
    });

    // === Leave Management ===
    Route::prefix('dashboard/leaves')->name('leaves.')->group(function () {
        Route::get('/', [IzinController::class, 'index'])->name('index');
        Route::get('/create', [IzinController::class, 'create'])->name('create');
        Route::post('/', [IzinController::class, 'store'])->name('store');
        Route::get('/{izin}', [IzinController::class, 'show'])->name('show');
        Route::get('/{izin}/edit', [IzinController::class, 'edit'])->name('edit');
        Route::patch('/{izin}', [IzinController::class, 'update'])->name('update');
        Route::delete('/{izin}', [IzinController::class, 'destroy'])->name('destroy');
        Route::patch('/{izin}/approve', [IzinController::class, 'approve'])->name('approve');
        Route::patch('/{izin}/reject', [IzinController::class, 'reject'])->name('reject');
    });

    // === Task Management ===
    Route::prefix('dashboard/tasks')->name('tasks.')->group(function () {
        Route::get('/', [TugasController::class, 'index'])->name('index');
        Route::get('/create', [TugasController::class, 'create'])->name('create');
        Route::post('/', [TugasController::class, 'store'])->name('store');
        Route::get('/{tugas}', [TugasController::class, 'show'])->name('show');
        Route::get('/{tugas}/edit', [TugasController::class, 'edit'])->name('edit');
        Route::patch('/{tugas}', [TugasController::class, 'update'])->name('update');
        Route::delete('/{tugas}', [TugasController::class, 'destroy'])->name('destroy');
        Route::post('/{tugas}/status', [TugasController::class, 'updateStatus'])->name('updateStatus');
    });

    // === Performance Management ===
    Route::prefix('dashboard/performance')->name('penilaian.')->group(function () {
        Route::get('/', [PenilaianController::class, 'index'])->name('index');
        Route::get('/create', [PenilaianController::class, 'create'])->name('create');
        Route::post('/', [PenilaianController::class, 'store'])->name('store');
        Route::get('/{penilaian}', [PenilaianController::class, 'show'])->name('show');
        Route::get('/{penilaian}/edit', [PenilaianController::class, 'edit'])->name('edit');
        Route::patch('/{penilaian}', [PenilaianController::class, 'update'])->name('update');
        Route::delete('/{penilaian}', [PenilaianController::class, 'destroy'])->name('destroy');
    });

    // === Payroll Management ===
    Route::prefix('dashboard/payroll')->name('penggajian.')->group(function () {
        Route::get('/', [PenggajianController::class, 'index'])->name('index');
        Route::get('/create', [PenggajianController::class, 'create'])->name('create');
        Route::post('/', [PenggajianController::class, 'store'])->name('store');
        Route::get('/{penggajian}', [PenggajianController::class, 'show'])->name('show');
        Route::get('/{penggajian}/edit', [PenggajianController::class, 'edit'])->name('edit');
        Route::patch('/{penggajian}', [PenggajianController::class, 'update'])->name('update');
        Route::delete('/{penggajian}', [PenggajianController::class, 'destroy'])->name('destroy');
        Route::get('/{penggajian}/paid', [PenggajianController::class, 'markPaid'])->name('paid');
        Route::get('/{penggajian}/slip', [PenggajianController::class, 'printSlip'])->name('slip');
        Route::get('/export/data', [PenggajianController::class, 'export'])->name('export');
    });

    // === Documents ===
    Route::prefix('dashboard/documents')->name('dokumen.')->group(function () {
        Route::get('/', [DokumenController::class, 'index'])->name('index');
        Route::get('/create', [DokumenController::class, 'create'])->name('create');
        Route::post('/', [DokumenController::class, 'store'])->name('store');
        Route::get('/{dokumen}', [DokumenController::class, 'show'])->name('show');
        Route::get('/{dokumen}/edit', [DokumenController::class, 'edit'])->name('edit');
        Route::patch('/{dokumen}', [DokumenController::class, 'update'])->name('update');
        Route::delete('/{dokumen}', [DokumenController::class, 'destroy'])->name('destroy');
        Route::get('/{dokumen}/download', [DokumenController::class, 'download'])->name('download');
    });

    // === Bulk Import ===
    Route::prefix('dashboard/import')->name('import.')->group(function () {
        Route::get('/', [DashboardController::class, 'import'])->name('index');
    });

    // === Analytics & Reports ===
    Route::prefix('dashboard/analytics')->name('analytics.')->group(function () {
        Route::get('/', [DashboardController::class, 'analytics'])->name('index');
    });

    // === Master Data (Jabatan & Satuan Kerja) ===
    Route::prefix('dashboard/master')->group(function () {
        Route::resource('jabatan', JabatanController::class)->names('jabatan');
        Route::resource('satuan-kerja', SatuanKerjaController::class)->names('satuan-kerja');
    });

    // === Notifications ===
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});
