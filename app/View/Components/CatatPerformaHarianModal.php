<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Periode; // Import model Periode
use Illuminate\Database\Eloquent\Collection; // Import Collection
use App\Models\StandardPerformaHarian;

class CatatPerformaHarianModal extends Component
{
     public Periode $periodeAktif;
     public Collection $pakanJenisOptions; // Tipe hint untuk Collection
     public Collection $stokObatOptions; // Tipe hint untuk Collection
     public ?StandardPerformaHarian $standardHariIni;

     /**
      * Create a new component instance.
      */
     public function __construct(
          Periode $periodeAktif,
          Collection $pakanJenisOptions,
          Collection $stokObatOptions,
          ?StandardPerformaHarian $standardHariIni = null
     ) {
          $this->periodeAktif = $periodeAktif;
          $this->pakanJenisOptions = $pakanJenisOptions;
          $this->stokObatOptions = $stokObatOptions;
          $this->standardHariIni = $standardHariIni;
     }

     /**
      * Get the view / contents that represent the component.
      */
     public function render(): \Illuminate\Contracts\View\View|\Closure|string
     {
          return view('components.catat-performa-harian-modal');
     }
}
