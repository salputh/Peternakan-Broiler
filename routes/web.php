<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeternakanController;
use App\Http\Controllers\KandangController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\StokPakanController;
use App\Http\Controllers\StokPakanMasukController;
use App\Http\Controllers\StokPakanKeluarController;
use App\Http\Controllers\StokObatMasukController;
use App\Http\Controllers\StokObatKeluarController;
use App\Http\Controllers\KandangDashboardController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\RecordHarianController;
use App\Http\Controllers\StokObatController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===================== GUEST ROUTES =====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'loginForm'])->name('login.form');
    Route::post('/login', [LoginController::class, 'processLogin'])->name('login');

    Route::get('/dev-login', [LoginController::class, 'devLoginForm'])->name('dev.login.form');
    Route::post('/dev-login', [LoginController::class, 'processDevLogin'])->name('dev.login');
});

// ===================== LOGOUT ROUTES =====================

// ===================== REDIRECT ROUTES BELUM LOGIN =====================
Route::get('/', function () {
    // Kalau belum login
    if (Auth::guest()) {
        return redirect()->route('login.form');
    }

    // Kalau udah login
    $user = Auth::user();

    return match ($user->role) {
        'developer' => redirect()->route('developer.dashboard'),
        'owner'     => redirect()->route('owner.dashboard'),
        'manager'   => redirect()->route('manager.dashboard'),
        'operator'  => redirect()->route('operator.dashboard'),
        default     => redirect()->route('login.form'),
    };
})->name('home');


// ===================== ROUTES DEVELOPER =====================
Route::prefix('developer')->name('developer.')->middleware(['auth', 'role:developer'])->group(function () {
    Route::get('peternakan/{peternakan:slug}/kandang/{kandang:slug}/periode/create', [PeriodeController::class, 'create'])->name('periode.create');

    Route::post('/logout', [LoginController::class, 'logoutDev'])->name('logout');

    // DASHBOARD DEVELOPER
    Route::get('/dashboard', [DeveloperController::class, 'index'])->name('dashboard');

    // DASHBOARD KANDANG
    Route::get('/dashboard/peternakan/{peternakan:slug}/kandang/{kandang:slug}/', [KandangDashboardController::class, 'index'])->name('dashboard.kandang');
    Route::put('/dashboard/peternakan/{peternakan:slug}/kandang/{kandang:slug}/end', [KandangDashboardController::class, 'end'])->name('kandang.periode.end');

    // KANDANG CRUD
    Route::resource('peternakan.kandang', KandangController::class)
        ->scoped(['peternakan' => 'slug', 'kandang' => 'slug'])
        ->names([
            'index'  => 'kandang.index',
            'create' => 'kandang.create',
            'store'  => 'kandang.store',
            'edit'   => 'kandang.edit',
            'update' => 'kandang.update',
        ]);


    Route::delete('peternakan/{peternakan:slug}/{kandang:slug}', [KandangController::class, 'destroy'])->name('kandang.destroy');
    Route::get('peternakan/{peternakan:slug}/kandang/{kandang:slug}/periode/{periode:slug}', [KandangController::class, 'show'])->name('kandang.show');

    Route::post('/record-harian', [RecordHarianController::class, 'store'])->name('daily.record.store');


    Route::resource('peternakan', PeternakanController::class)
        ->scoped(['peternakan' => 'slug'])
        ->names([
            'index'   => 'peternakan.index',
            'create'  => 'peternakan.create',
            'store'   => 'peternakan.store',
            'edit'    => 'peternakan.edit',
            'update'  => 'peternakan.update',
            'show'    => 'peternakan.show',
        ]);


    // OWNER CRUD
    Route::get('/owner/create', [OwnerController::class, 'create'])->name('owner.create');
    Route::post('/owner/store', [OwnerController::class, 'store'])->name('owner.store');
    Route::get('/owner/{owner:slug}/edit', [OwnerController::class, 'edit'])->name('owner.edit');
    Route::match(['put', 'patch'], '/{owner:slug}', [OwnerController::class, 'update'])->name('owner.update');
    Route::delete('/{owner:slug}', [OwnerController::class, 'destroy'])->name('owner.destroy');

    // PERIODE CRUD
    Route::get('peternakan/{peternakan:slug}/kandang/{kandang:slug}/periode/', [PeriodeController::class, 'index'])->name('periode.index');
    Route::post('peternakan/{peternakan:slug}/kandang/{kandang:slug}/periode/', [PeriodeController::class, 'store'])->name('periode.store');
    Route::get('peternakan/{peternakan:slug}/kandang/{kandang:slug}/periode/{periode:slug}/edit', [PeriodeController::class, 'edit'])->name('periode.edit');
    Route::get('peternakan/{peternakan:slug}/kandang/{kandang:slug}/periode/{periode:slug}/detail', [PeriodeController::class, 'show'])->name('periode.show');
    Route::put('peternakan/{peternakan:slug}/kandang/{kandang:slug}/periode/{periode:slug}/end', [PeriodeController::class, 'end'])->name('periode.end');
    Route::match(['put', 'patch'], 'peternakan/{peternakan:slug}/kandang/{kandang:slug}/periode/{periode:slug}', [PeriodeController::class, 'update'])->name('periode.update');
    Route::delete('peternakan/{peternakan:slug}/kandang/{kandang:slug}/periode/{periode:slug}', [PeriodeController::class, 'destroy'])->name('periode.destroy');



    // STOK PAKAN
    Route::get(
        'peternakan/{peternakan:slug}/kandang/{kandang:slug}/stok-pakan/',
        [StokPakanController::class, 'index']
    )->name('stok_pakan.index');


    Route::post(
        'peternakan/{peternakan:slug}/kandang/{kandang:slug}/stok-pakan/masuk',
        [StokPakanMasukController::class, 'store']
    )->name('stok_pakan.masuk.store');

    Route::delete(
        'peternakan/{peternakan:slug}/kandang/{kandang:slug}/stok-pakan/{masuk}',
        [StokPakanMasukController::class, 'destroy']
    )->name('stok_pakan.masuk.destroy');

    Route::match(
        ['put', 'patch'],
        'peternakan/{peternakan:slug}/kandang/{kandang:slug}/stok-pakan/{masuk}',
        [StokPakanMasukController::class, 'update']
    )->name('stok_pakan.masuk.update');

    // STOK OBAT
    Route::get(
        'peternakan/{peternakan:slug}/kandang/{kandang:slug}/stok-obat/',
        [StokObatController::class, 'index']
    )->name('stok_obat.index');

    Route::post(
        'peternakan/{peternakan:slug}/kandang/{kandang:slug}/stok-obat/masuk',
        [StokObatMasukController::class, 'store']
    )->name('stok_obat.masuk.store');

    Route::match(
        ['put', 'patch'],
        'peternakan/{peternakan:slug}/kandang/{kandang:slug}/stok-obat/{masuk}',
        [StokObatMasukController::class, 'update']
    )->name('stok_obat.masuk.update');

    Route::delete(
        'peternakan/{peternakan:slug}/kandang/{kandang:slug}/stok-obat/{masuk}',
        [StokObatMasukController::class, 'destroy']
    )->name('stok_obat.masuk.destroy');

    // Data Harian
});
