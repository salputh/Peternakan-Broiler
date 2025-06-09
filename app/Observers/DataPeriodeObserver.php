<?php

namespace App\Observers;

use App\Models\DataPeriode;
use App\Models\RingkasanPerformaHarian; // Model untuk tabel ringkasan
use App\Models\StandardPerformaHarian;  // Model untuk standar performa
use App\Models\Deplesi;                 // Model untuk deplesi
use App\Models\StokPakanKeluar;         // Model untuk pakan keluar
use App\Models\StokObatKeluar;          // Model untuk obat keluar (jika perlu diagregasi)
use App\Models\BodyWeight;              // Model untuk berat badan
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log; // Untuk logging

class DataPeriodeObserver
{
    public function created(DataPeriode $dataPeriode): void
    {
        $this->saveDailySummary($dataPeriode);
    }

    public function updated(DataPeriode $dataPeriode): void
    {
        $this->saveDailySummary($dataPeriode);
    }

    public function deleted(DataPeriode $dataPeriode): void
    {
        RingkasanPerformaHarian::where('data_periode_id', $dataPeriode->id)->delete();
        Log::info('Ringkasan harian dihapus untuk DataPeriode ID: ' . $dataPeriode->id);
    }

    protected function saveDailySummary(DataPeriode $dataPeriode): void
    {
        Log::info('Memulai saveDailySummary untuk DataPeriode ID: ' . $dataPeriode->id);

        if (!$dataPeriode->periodes || !$dataPeriode->periodes->kandang) {
            Log::warning('DataPeriode ID ' . $dataPeriode->id . ' tidak memiliki relasi periode atau kandang.');
            return;
        }

        $tanggal_catat = $dataPeriode->tanggal;
        $usia = $dataPeriode->usia;
        $periode_id = $dataPeriode->periode_id;
        $kandang_id = $dataPeriode->periodes->kandang_id;

        $standardHariIni = StandardPerformaHarian::where('age', $usia)->first();
        if (!$standardHariIni) {
            Log::warning('Data standar performa tidak ditemukan untuk usia: ' . $usia . '. Menggunakan nilai default 0.');
            $standardHariIni = new StandardPerformaHarian();
        }

        $previousSummary = null;
        if ($usia > 1) {
            $previousSummary = RingkasanPerformaHarian::where('periode_id', $periode_id)
                ->where('kandang_id', $kandang_id)
                ->whereDate('tanggal_catat', $tanggal_catat->copy()->subDay())
                ->first();
        }

        // Population calculations
        $deplesiRecord = Deplesi::where('data_periode_id', $dataPeriode->id)->first();
        $jumlah_mati_harian = $deplesiRecord ? $deplesiRecord->jumlah_mati : 0;
        $jumlah_afkir_harian = $deplesiRecord ? $deplesiRecord->jumlah_afkir : 0;
        $jumlah_deplesi_harian = $jumlah_mati_harian + $jumlah_afkir_harian;

        $jumlah_ayam_awal = $previousSummary ? $previousSummary->jumlah_ayam_akhir : $dataPeriode->periodes->jumlah_ayam;
        $cum_deplesi_harian = $dataPeriode->periodes->deplesis()->sum('jumlah_mati') + $dataPeriode->periodes->deplesis()->sum('jumlah_afkir');

        // Feed calculations
        $pakanKeluarRecord = StokPakanKeluar::where('data_periode_id', $dataPeriode->id)->first();

        $act_zak_harian = $pakanKeluarRecord ? $pakanKeluarRecord->jumlah_keluar : 0;
        $act_jenis_pakan_harian = $pakanKeluarRecord ? $pakanKeluarRecord->jenis_pakan : null;
        $act_cum_zak_harian = 0;
        if ($previousSummary) {
            $act_cum_zak_harian = $previousSummary->act_cum_zak_harian + $act_zak_harian;
        } else {
            $act_cum_zak_harian = $act_zak_harian;
        }

        $std_gr_per_ekor_harian = $standardHariIni->pakan_std_gr_ek ?? 0;
        $std_pakan_zak_harian = ($jumlah_ayam_awal * $std_gr_per_ekor_harian) / 50000;
        // Calculate cumulative standard feed per day
        $std_cum_zak_harian = 0;
        if ($previousSummary) {
            $std_cum_zak_harian = $previousSummary->std_cum_zak_harian + $std_pakan_zak_harian;
        } else {
            $std_cum_zak_harian = $std_pakan_zak_harian;
        }
        $act_gr_per_ekor_harian = ($jumlah_ayam_awal > 0) ? ($act_zak_harian * 1000) / $jumlah_ayam_awal : 0;
        $act_cum_gr_per_ekor_harian = $previousSummary ? $previousSummary->act_cum_gr_per_ekor_harian + $act_gr_per_ekor_harian : $act_gr_per_ekor_harian;
        $std_cum_gr_per_ekor_harian = $previousSummary ? $previousSummary->std_cum_gr_per_ekor_harian + $std_gr_per_ekor_harian : $std_gr_per_ekor_harian;

        // Body weight calculations
        $bodyWeightRecord = BodyWeight::where('data_periode_id', $dataPeriode->id)->first();
        $act_bw_abw_harian = $bodyWeightRecord ? $bodyWeightRecord->body_weight_hasil : 0;
        $std_bw_abw_harian = $standardHariIni->bw_std_abw ?? 0;

        $act_bw_dg_gr_harian = 0;
        if ($usia == 1 && $act_bw_abw_harian > 0) {
            $act_bw_dg_gr_harian = $act_bw_abw_harian - 40;
        } elseif ($act_bw_abw_harian > 0 && $previousSummary && $previousSummary->act_bw_abw_harian > 0) {
            $act_bw_dg_gr_harian = $act_bw_abw_harian - $previousSummary->act_bw_abw_harian;
        }
        $std_bw_dg_gr_harian = $standardHariIni->bw_std_dg ?? 0;

        // Environment data
        $act_lingkungan_suhu_min_harian = $dataPeriode->suhu_min;
        $act_lingkungan_suhu_max_harian = $dataPeriode->suhu_max;
        $act_lingkungan_persen_hum_harian = $dataPeriode->kelembapan;

        $std_lingkungan_suhu_min_harian = $standardHariIni->std_suhu_min ?? null;
        $std_lingkungan_suhu_max_harian = $standardHariIni->std_suhu_max ?? null;
        $std_lingkungan_persen_hum_harian = $standardHariIni->std_kelembapan ?? null;

        // Performance calculations
        $act_fcr_harian = 0;
        if ($act_cum_gr_per_ekor_harian > 0 && $act_bw_abw_harian > 0) {
            $act_fcr_harian = $act_cum_gr_per_ekor_harian / $act_bw_abw_harian;
        }
        $std_fcr_harian = $standardHariIni->fcr_std ?? 0;

        $act_index_performance_harian = 0;
        $persen_hidup = ($dataPeriode->periodes->jumlah_ayam > 0) ?
            (($dataPeriode->periodes->jumlah_ayam - $cum_deplesi_harian) / $dataPeriode->periodes->jumlah_ayam) * 100 : 0;

        if ($act_bw_abw_harian > 0 && $act_fcr_harian > 0 && $usia > 0 && $persen_hidup > 0) {
            $act_index_performance_harian = (($act_bw_abw_harian / 1000) * $persen_hidup) / ($act_fcr_harian * $usia) * 100;
        }
        $std_index_performance_harian = $standardHariIni->ip_std ?? 0;

        // Calculate deltas
        $delta_bw_abw_harian = $act_bw_abw_harian - $std_bw_abw_harian;
        $delta_bw_dg_gr_harian = $act_bw_dg_gr_harian - $std_bw_dg_gr_harian;
        $delta_fcr_harian = $act_fcr_harian - $std_fcr_harian;
        $delta_index_performance_harian = $act_index_performance_harian - $std_index_performance_harian;

        $jumlah_ayam_akhir = $jumlah_ayam_awal - $jumlah_deplesi_harian;
        if ($jumlah_ayam_akhir < 0) $jumlah_ayam_akhir = 0;

        RingkasanPerformaHarian::updateOrCreate(
            ['data_periode_id' => $dataPeriode->id],
            [
                'kandang_id' => $kandang_id,
                'periode_id' => $periode_id,
                'tanggal_catat' => $tanggal_catat,
                'usia_ke' => $usia,

                'jumlah_ayam_awal' => $jumlah_ayam_awal,
                'jumlah_mati_harian' => $jumlah_mati_harian,
                'jumlah_afkir_harian' => $jumlah_afkir_harian,
                'jumlah_deplesi_harian' => $jumlah_deplesi_harian,
                'cum_deplesi_harian' => $cum_deplesi_harian,

                'std_pakan_zak_harian' => $std_pakan_zak_harian,
                'std_cum_zak_harian' => $std_cum_zak_harian,
                'std_gr_per_ekor_harian' => $std_gr_per_ekor_harian,
                'std_cum_gr_per_ekor_harian' => $std_cum_gr_per_ekor_harian,
                'act_zak_harian' => $act_zak_harian,
                'act_jenis_pakan_harian' => $act_jenis_pakan_harian,
                'act_cum_zak_harian' => $act_cum_zak_harian,
                'act_gr_per_ekor_harian' => $act_gr_per_ekor_harian,
                'act_cum_gr_per_ekor_harian' => $act_cum_gr_per_ekor_harian,
                'delta_zak_harian' => $act_zak_harian - $std_pakan_zak_harian,
                'delta_cum_zak_harian' => $act_cum_zak_harian - $std_cum_zak_harian,
                'delta_gr_per_ekor_harian' => $act_gr_per_ekor_harian - $std_gr_per_ekor_harian,
                'delta_cum_gr_per_ekor_harian' => $act_cum_gr_per_ekor_harian - $std_cum_gr_per_ekor_harian,

                'std_bw_abw_harian' => $std_bw_abw_harian,
                'std_bw_dg_gr_harian' => $std_bw_dg_gr_harian,
                'act_bw_abw_harian' => $act_bw_abw_harian,
                'act_bw_dg_gr_harian' => $act_bw_dg_gr_harian,
                'delta_bw_abw_harian' => $delta_bw_abw_harian,
                'delta_bw_dg_gr_harian' => $delta_bw_dg_gr_harian,

                'std_lingkungan_suhu_min_harian' => $std_lingkungan_suhu_min_harian,
                'std_lingkungan_suhu_max_harian' => $std_lingkungan_suhu_max_harian,
                'std_lingkungan_persen_hum_harian' => $std_lingkungan_persen_hum_harian,
                'act_lingkungan_suhu_min_harian' => $act_lingkungan_suhu_min_harian,
                'act_lingkungan_suhu_max_harian' => $act_lingkungan_suhu_max_harian,
                'act_lingkungan_persen_hum_harian' => $act_lingkungan_persen_hum_harian,
                'delta_lingkungan_suhu_min_harian' => $act_lingkungan_suhu_min_harian - $std_lingkungan_suhu_min_harian,
                'delta_lingkungan_suhu_max_harian' => $act_lingkungan_suhu_max_harian - $std_lingkungan_suhu_max_harian,
                'delta_lingkungan_persen_hum_harian' => $act_lingkungan_persen_hum_harian - $std_lingkungan_persen_hum_harian,

                'std_fcr_harian' => $std_fcr_harian,
                'act_fcr_harian' => $act_fcr_harian,
                'delta_fcr_harian' => $delta_fcr_harian,
                'std_index_performance_harian' => $std_index_performance_harian,
                'act_index_performance_harian' => $act_index_performance_harian,
                'delta_index_performance_harian' => $delta_index_performance_harian,

                // 'jumlah_ayam_dipanen' => $jumlah_ayam_dipanen,
                'jumlah_ayam_akhir' => $jumlah_ayam_akhir,
            ]

        );

        Log::info('Ringkasan harian berhasil disimpan/diupdate untuk DataPeriode ID: ' . $dataPeriode->id);
    }
}
