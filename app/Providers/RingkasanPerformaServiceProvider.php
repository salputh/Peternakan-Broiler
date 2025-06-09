<?php

namespace App\Providers;

use App\Models\DataPeriode;
use App\Models\BodyWeight;
use App\Models\Deplesi;
use App\Models\StokPakanKeluar;
use App\Models\StokObatKeluar;
use App\Observers\DataPeriodeObserver;
use Illuminate\Support\ServiceProvider;

class RingkasanPerformaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Hapus registrasi ganda untuk DataPeriode
        DataPeriode::observe(DataPeriodeObserver::class);

        // Perbaiki event listeners untuk memastikan dataPeriode ada
        BodyWeight::created(function ($bodyWeight) {
            if ($bodyWeight->dataPeriode) {
                $observer = app(DataPeriodeObserver::class);
                $observer->updated($bodyWeight->dataPeriode);
            }
        });

        Deplesi::created(function ($deplesi) {
            if ($deplesi->dataPeriode) {
                $observer = app(DataPeriodeObserver::class);
                $observer->updated($deplesi->dataPeriode);
            }
        });

        StokPakanKeluar::created(function ($pakanKeluar) {
            if ($pakanKeluar->dataPeriode) {
                $observer = app(DataPeriodeObserver::class);
                $observer->updated($pakanKeluar->dataPeriode);
            }
        });

        StokObatKeluar::created(function ($obatKeluar) {
            if ($obatKeluar->dataPeriode) {
                $observer = app(DataPeriodeObserver::class);
                $observer->updated($obatKeluar->dataPeriode);
            }
        });
    }
}
