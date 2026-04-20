<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Super Admin & Admin Routes
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::resource('/admin/users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');
    });

    // Dosen Routes
    Route::middleware('role:superadmin,admin,dosen')->group(function () {
        Route::resource('/dosen/kelas', \App\Http\Controllers\Dosen\KelasController::class)->names('dosen.kelas');
        Route::post('/dosen/kelas/{kela}/mahasiswa', [\App\Http\Controllers\Dosen\KelasController::class, 'addMahasiswa'])->name('dosen.kelas.addMahasiswa');
        Route::post('/dosen/kelas/{kela}/import', [\App\Http\Controllers\Dosen\KelasController::class, 'importMahasiswa'])->name('dosen.kelas.import');
        Route::delete('/dosen/kelas/{kela}/mahasiswa/{mahasiswa}', [\App\Http\Controllers\Dosen\KelasController::class, 'removeMahasiswa'])->name('dosen.kelas.removeMahasiswa');
        Route::get('/dosen/search-mahasiswa', [\App\Http\Controllers\Dosen\KelasController::class, 'searchMahasiswa'])->name('dosen.mahasiswa.search');
        Route::get('/dosen/import-template', [\App\Http\Controllers\Dosen\KelasController::class, 'downloadTemplate'])->name('dosen.kelas.import-template');
        Route::post('/dosen/kelas/{kela}/link-perusahaan/{perusahaan}', [\App\Http\Controllers\Dosen\KelasController::class, 'linkPerusahaan'])->name('dosen.kelas.linkPerusahaan');
    });

    // Mahasiswa & Dosen Accounting Routes
    Route::middleware('role:superadmin,admin,mahasiswa,dosen')->group(function () {
        // Setup initial
        Route::get('/mahasiswa/studi-kasus', [\App\Http\Controllers\Mahasiswa\PerusahaanController::class, 'index'])->name('mahasiswa.perusahaan.index');
        Route::get('/mahasiswa/studi-kasus/{perusahaan}', [\App\Http\Controllers\Mahasiswa\PerusahaanController::class, 'show'])->name('mahasiswa.perusahaan.show');
        Route::get('/mahasiswa/setup', [\App\Http\Controllers\Mahasiswa\PerusahaanController::class, 'create'])->name('mahasiswa.perusahaan.create');
        Route::post('/mahasiswa/setup', [\App\Http\Controllers\Mahasiswa\PerusahaanController::class, 'store'])->name('mahasiswa.perusahaan.store');

        // Accounting Operations
        Route::get('/mahasiswa/coa', [\App\Http\Controllers\Mahasiswa\CoaController::class, 'index'])->name('mahasiswa.coa.index');
        Route::get('/mahasiswa/coa/create', [\App\Http\Controllers\Mahasiswa\CoaController::class, 'create'])->name('mahasiswa.coa.create');
        Route::post('/mahasiswa/coa', [\App\Http\Controllers\Mahasiswa\CoaController::class, 'store'])->name('mahasiswa.coa.store');
        Route::post('/mahasiswa/coa/import', [\App\Http\Controllers\Mahasiswa\CoaController::class, 'import'])->name('mahasiswa.coa.import');
        Route::post('/mahasiswa/coa/import-default', [\App\Http\Controllers\Mahasiswa\CoaController::class, 'importDefault'])->name('mahasiswa.coa.import-default');

        Route::get('/mahasiswa/saldo-awal', [\App\Http\Controllers\Mahasiswa\SaldoAwalController::class, 'index'])->name('mahasiswa.saldo-awal.index');
        Route::post('/mahasiswa/saldo-awal', [\App\Http\Controllers\Mahasiswa\SaldoAwalController::class, 'store'])->name('mahasiswa.saldo-awal.store');
        
        // Jurnal Umum
        Route::get('/mahasiswa/jurnal', [\App\Http\Controllers\Mahasiswa\JurnalController::class, 'index'])->name('mahasiswa.jurnal.index');
        Route::get('/mahasiswa/jurnal/create', [\App\Http\Controllers\Mahasiswa\JurnalController::class, 'create'])->name('mahasiswa.jurnal.create');
        Route::post('/mahasiswa/jurnal', [\App\Http\Controllers\Mahasiswa\JurnalController::class, 'store'])->name('mahasiswa.jurnal.store');
        Route::get('/mahasiswa/jurnal/{jurnal}/edit', [\App\Http\Controllers\Mahasiswa\JurnalController::class, 'edit'])->name('mahasiswa.jurnal.edit');
        Route::put('/mahasiswa/jurnal/{jurnal}', [\App\Http\Controllers\Mahasiswa\JurnalController::class, 'update'])->name('mahasiswa.jurnal.update');
        Route::delete('/mahasiswa/jurnal/{jurnal}', [\App\Http\Controllers\Mahasiswa\JurnalController::class, 'destroy'])->name('mahasiswa.jurnal.destroy');

        // Jurnal Penyesuaian
        Route::get('/mahasiswa/jurnal-penyesuaian', [\App\Http\Controllers\Mahasiswa\JurnalController::class, 'adjIndex'])->name('mahasiswa.jurnal-penyesuaian.index');
        Route::get('/mahasiswa/jurnal-penyesuaian/create', [\App\Http\Controllers\Mahasiswa\JurnalController::class, 'adjCreate'])->name('mahasiswa.jurnal-penyesuaian.create');
        Route::post('/mahasiswa/jurnal-penyesuaian', [\App\Http\Controllers\Mahasiswa\JurnalController::class, 'adjStore'])->name('mahasiswa.jurnal-penyesuaian.store');
        Route::get('/mahasiswa/jurnal-penyesuaian/{jurnal}/edit', [\App\Http\Controllers\Mahasiswa\JurnalController::class, 'adjEdit'])->name('mahasiswa.jurnal-penyesuaian.edit');
        Route::put('/mahasiswa/jurnal-penyesuaian/{jurnal}', [\App\Http\Controllers\Mahasiswa\JurnalController::class, 'adjUpdate'])->name('mahasiswa.jurnal-penyesuaian.update');
        Route::delete('/mahasiswa/jurnal-penyesuaian/{jurnal}', [\App\Http\Controllers\Mahasiswa\JurnalController::class, 'adjDestroy'])->name('mahasiswa.jurnal-penyesuaian.destroy');
        
        Route::post('/mahasiswa/tutup-buku', [\App\Http\Controllers\Mahasiswa\ClosingController::class, 'store'])->name('mahasiswa.perusahaan.closing');
        
        // Laporan Keuangan
        Route::prefix('laporan')->name('mahasiswa.laporan.')->group(function () {
            Route::get('/buku-besar', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'bukuBesar'])->name('buku-besar');
            Route::get('/neraca-saldo', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'trialBalance'])->name('neraca-saldo');
            Route::get('/laba-rugi', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'labaRugi'])->name('laba-rugi');
            Route::get('/laba-rugi/print', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'labaRugiPrint'])->name('laba-rugi.print');
            Route::get('/laba-rugi/excel', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'labaRugiExcel'])->name('laba-rugi.excel');
            Route::get('/laba-rugi/pdf', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'labaRugiPdf'])->name('laba-rugi.pdf');
            
            Route::get('/perubahan-ekuitas', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'perubahanEkuitas'])->name('perubahan-ekuitas');
            Route::get('/perubahan-ekuitas/print', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'perubahanEkuitasPrint'])->name('perubahan-ekuitas.print');
            Route::get('/perubahan-ekuitas/excel', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'perubahanEkuitasExcel'])->name('perubahan-ekuitas.excel');
            Route::get('/perubahan-ekuitas/pdf', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'perubahanEkuitasPdf'])->name('perubahan-ekuitas.pdf');

            Route::get('/neraca', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'neraca'])->name('neraca');
            Route::get('/neraca/print', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'neracaPrint'])->name('neraca.print');
            Route::get('/neraca/excel', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'neracaExcel'])->name('neraca.excel');
            Route::get('/neraca/pdf', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'neracaPdf'])->name('neraca.pdf');

            Route::get('/worksheet', [\App\Http\Controllers\Mahasiswa\LaporanController::class, 'worksheet'])->name('worksheet');
        });

        // Pengaturan
        Route::get('/mahasiswa/pengaturan', [\App\Http\Controllers\Mahasiswa\PerusahaanController::class, 'edit'])->name('mahasiswa.perusahaan.edit');
        Route::patch('/mahasiswa/pengaturan', [\App\Http\Controllers\Mahasiswa\PerusahaanController::class, 'update'])->name('mahasiswa.perusahaan.update');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
