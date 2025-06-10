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
        $dataPeriode->load('periodes.kandang');

        if (!$dataPeriode->periodes || !$dataPeriode->periodes->kandang) {
            Log::warning('DataPeriode ID ' . $dataPeriode->id . ' tidak memiliki relasi periode atau kandang setelah eager loading.');
            return;
        }

        // Basic data
        $tanggal_catat = $dataPeriode->tanggal;
        $usia = $dataPeriode->usia;
        $periode_id = $dataPeriode->periode_id;
        $kandang_id = $dataPeriode->periodes->kandang_id;

        // Get standard performance data
        $standardHariIni = StandardPerformaHarian::where('age', $usia)->first() ?? new StandardPerformaHarian();

        // Get previous day data - PERBAIKAN
        $previousSummary = null;
        if ($usia > 1) {
            $previousSummary = RingkasanPerformaHarian::where('periode_id', $periode_id)
                ->where('kandang_id', $kandang_id)
                ->where('usia_ke', $usia - 1) // Gunakan usia sebelumnya sebagai alternatif
                ->orWhere(function ($query) use ($periode_id, $kandang_id, $tanggal_catat) {
                    $query->where('periode_id', $periode_id)
                        ->where('kandang_id', $kandang_id)
                        ->whereDate('tanggal_catat', $tanggal_catat->copy()->subDay());
                })
                ->orderBy('usia_ke', 'desc')
                ->first();
        }

        // EKSTRAK VARIABEL PREVIOUS SUMMARY
        $prev_jumlah_ayam_akhir = $previousSummary ? $previousSummary->jumlah_ayam_akhir : $dataPeriode->periodes->jumlah_ayam;
        $prev_act_cum_zak_harian = $previousSummary ? $previousSummary->act_cum_zak_harian : 0;
        $prev_act_cum_gr_per_ekor_harian = $previousSummary ? $previousSummary->act_cum_gr_per_ekor_harian : 0;
        $prev_std_cum_zak_harian = $previousSummary ? $previousSummary->std_cum_zak_harian : 0;
        $prev_std_cum_gr_per_ekor_harian = $previousSummary ? $previousSummary->std_cum_gr_per_ekor_harian : 0;
        $prev_act_bw_abw_harian = $previousSummary ? $previousSummary->act_bw_abw_harian : 0;
        $prev_act_fcr_harian = $previousSummary ? $previousSummary->act_fcr_harian : 0;

        // Population calculations
        $deplesiRecord = Deplesi::where('data_periode_id', $dataPeriode->id)->first();
        $jumlah_mati_harian = $deplesiRecord ? $deplesiRecord->jumlah_mati : 0;
        $jumlah_afkir_harian = $deplesiRecord ? $deplesiRecord->jumlah_afkir : 0;
        $jumlah_deplesi_harian = $jumlah_mati_harian + $jumlah_afkir_harian;

        $jumlah_ayam_awal = $prev_jumlah_ayam_akhir;
        $jumlah_ayam_akhir = max(0, $jumlah_ayam_awal - $jumlah_deplesi_harian);

        // PERBAIKAN DEPLESI: Hitung deplesi kumulatif yang benar
        $std_cum_deplesi_harian = $standardHariIni->deplesi_std_cum;
        $total_ayam_deplesi = $dataPeriode->periodes->jumlah_ayam - $jumlah_ayam_akhir;
        $cum_deplesi_harian = ($dataPeriode->periodes->jumlah_ayam > 0) ?
            ($total_ayam_deplesi / $dataPeriode->periodes->jumlah_ayam) * 100 : 0;

        // Persentase kelangsungan hidup = 100% - Deplesi kumulatif
        $persen_hidup_kumulatif = 100 - $cum_deplesi_harian;

        // Feed calculations
        $pakanKeluarRecord = StokPakanKeluar::with(['stokPakans.pakanJenis' => function ($query) {
            $query->select('id', 'kode');
        }])->where('data_periode_id', $dataPeriode->id)->first();

        $pakan_zak_harian = $pakanKeluarRecord ? $pakanKeluarRecord->jumlah_keluar : 0;

        $act_pakan_zak_harian = $pakan_zak_harian;
        $act_gr_per_ekor_harian = $jumlah_ayam_awal > 0 ? ($pakan_zak_harian * 50 * 1000) / $jumlah_ayam_awal : 0;

        // Calculate cumulative feed - MENGGUNAKAN VARIABEL YANG SUDAH DIEKSTRAK
        $act_cum_zak_harian = $prev_act_cum_zak_harian + $act_pakan_zak_harian;
        $act_cum_gr_per_ekor_harian = $prev_act_cum_gr_per_ekor_harian + $act_gr_per_ekor_harian;

        // Standard feed calculations
        $std_gr_per_ekor_harian = $standardHariIni->pakan_std_gr_ek ?? 0;
        $std_pakan_zak_harian = round(($jumlah_ayam_awal * $std_gr_per_ekor_harian) / 50000);

        $std_cum_zak_harian = $prev_std_cum_zak_harian + $std_pakan_zak_harian;
        $std_cum_gr_per_ekor_harian = $prev_std_cum_gr_per_ekor_harian + $std_gr_per_ekor_harian;

        // Delta feed calculations
        $delta_zak_harian = $act_pakan_zak_harian - $std_pakan_zak_harian;
        $delta_cum_zak_harian = $act_cum_zak_harian - $std_cum_zak_harian;
        $delta_gr_per_ekor_harian = $act_gr_per_ekor_harian - $std_gr_per_ekor_harian;
        $delta_cum_gr_per_ekor_harian = $act_cum_gr_per_ekor_harian - $std_cum_gr_per_ekor_harian;

        // Body weight calculations
        $bodyWeightRecord = BodyWeight::where('data_periode_id', $dataPeriode->id)->first();
        $act_bw_abw_harian = $bodyWeightRecord ? $bodyWeightRecord->body_weight_hasil : 0;

        // Calculate daily weight gain - MENGGUNAKAN VARIABEL YANG SUDAH DIEKSTRAK
        $act_bw_dg_gr_harian = 0;
        if ($usia == 1) {
            $berat_doc_standar = 56; // gram (standar berat DOC)
            $act_bw_dg_gr_harian = $act_bw_abw_harian - $berat_doc_standar;
        } elseif ($prev_act_bw_abw_harian > 0 && $act_bw_abw_harian > 0) {
            $act_bw_dg_gr_harian = $act_bw_abw_harian - $prev_act_bw_abw_harian;
        }

        // Delta body weight calculations
        $delta_bw_abw_harian = $act_bw_abw_harian - ($standardHariIni->bw_std_abw ?? 0);
        $delta_bw_dg_gr_harian = $act_bw_dg_gr_harian - ($standardHariIni->bw_std_dg ?? 0);

        // Environment data
        $act_lingkungan_suhu_min_harian = $dataPeriode->suhu_min;
        $act_lingkungan_suhu_max_harian = $dataPeriode->suhu_max;
        $act_lingkungan_persen_hum_harian = $dataPeriode->kelembapan;

        // Delta environment calculations
        $delta_lingkungan_suhu_min_harian = $act_lingkungan_suhu_min_harian - ($standardHariIni->std_suhu_min ?? 0);
        $delta_lingkungan_suhu_max_harian = $act_lingkungan_suhu_max_harian - ($standardHariIni->std_suhu_max ?? 0);
        $delta_lingkungan_persen_hum_harian = $act_lingkungan_persen_hum_harian - ($standardHariIni->std_kelembapan ?? 0);

        // PERBAIKAN FCR: Menggunakan rumus yang benar
        $total_feed_consumed_kg = $act_cum_zak_harian * 50; // Convert sak to kg (1 sak = 50 kg)

        $berat_doc_kg = 0.045; // 45 gram = 0.045 kg
        $bobot_total_awal_kg = $dataPeriode->periodes->jumlah_ayam * $berat_doc_kg;
        $bobot_total_sekarang_kg = ($act_bw_abw_harian / 1000) * $jumlah_ayam_akhir;
        $total_bobot_dihasilkan_kg = $bobot_total_sekarang_kg - $bobot_total_awal_kg;

        $total_bobot_dihasilkan_kg = max($total_bobot_dihasilkan_kg, 0.001);

        // Hitung FCR - MENGGUNAKAN VARIABEL YANG SUDAH DIEKSTRAK
        $act_fcr_harian = ($total_bobot_dihasilkan_kg > 0) ?
            $total_feed_consumed_kg / $total_bobot_dihasilkan_kg : $prev_act_fcr_harian;

        $delta_fcr_harian = $act_fcr_harian - ($standardHariIni->fcr_std ?? 0);

        // PERBAIKAN IP: Menggunakan rumus yang benar
        $berat_rata_rata_kg = $act_bw_abw_harian / 1000;

        $ip_numerator = $berat_rata_rata_kg * $persen_hidup_kumulatif;
        $ip_denominator = $act_fcr_harian * $usia;

        if ($ip_denominator > 0 && $ip_numerator > 0 && $act_fcr_harian > 0) {
            $act_index_performance_harian = ($ip_numerator / $ip_denominator) * 10000;
            $act_index_performance_harian = round($act_index_performance_harian);
        } else {
            $act_index_performance_harian = 0;
        }

        $std_index_performance = $standardHariIni->ip_std ?? 0;
        $delta_index_performance_harian = $act_index_performance_harian - $std_index_performance;

        // Create or update summary
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
                'cum_deplesi_harian' => round($cum_deplesi_harian, 2),
                'std_cum_deplesi_harian' => $std_cum_deplesi_harian,

                'std_pakan_zak_harian' => $std_pakan_zak_harian,
                'std_cum_zak_harian' => $std_cum_zak_harian,
                'std_gr_per_ekor_harian' => $std_gr_per_ekor_harian,
                'std_cum_gr_per_ekor_harian' => $std_cum_gr_per_ekor_harian,
                'act_zak_harian' => $act_pakan_zak_harian,
                'act_jenis_pakan_harian' => $pakanKeluarRecord && $pakanKeluarRecord->stokPakans && $pakanKeluarRecord->stokPakans->first() && $pakanKeluarRecord->stokPakans->first()->pakanJenis ? $pakanKeluarRecord->stokPakans->first()->pakanJenis->kode : '',
                'act_cum_zak_harian' => $act_cum_zak_harian,
                'act_gr_per_ekor_harian' => round($act_gr_per_ekor_harian, 2),
                'act_cum_gr_per_ekor_harian' => round($act_cum_gr_per_ekor_harian, 2),
                'delta_zak_harian' => $delta_zak_harian,
                'delta_cum_zak_harian' => $delta_cum_zak_harian,
                'delta_gr_per_ekor_harian' => round($delta_gr_per_ekor_harian, 2),
                'delta_cum_gr_per_ekor_harian' => round($delta_cum_gr_per_ekor_harian, 2),

                'std_bw_abw_harian' => $standardHariIni->bw_std_abw ?? 0,
                'std_bw_dg_gr_harian' => $standardHariIni->bw_std_dg ?? 0,
                'act_bw_abw_harian' => $act_bw_abw_harian,
                'act_bw_dg_gr_harian' => round($act_bw_dg_gr_harian, 2),
                'delta_bw_abw_harian' => round($delta_bw_abw_harian, 2),
                'delta_bw_dg_gr_harian' => round($delta_bw_dg_gr_harian, 2),
                'obat_jenis_harian' => '',
                'obat_jumlah_harian' => 0,
                'std_lingkungan_suhu_min_harian' => $standardHariIni->std_suhu_min ?? 0,
                'std_lingkungan_suhu_max_harian' => $standardHariIni->std_suhu_max ?? 0,
                'std_lingkungan_persen_hum_harian' => $standardHariIni->std_kelembapan ?? 0,
                'act_lingkungan_suhu_min_harian' => $act_lingkungan_suhu_min_harian,
                'act_lingkungan_suhu_max_harian' => $act_lingkungan_suhu_max_harian,
                'act_lingkungan_persen_hum_harian' => $act_lingkungan_persen_hum_harian,
                'delta_lingkungan_suhu_min_harian' => round($delta_lingkungan_suhu_min_harian, 2),
                'delta_lingkungan_suhu_max_harian' => round($delta_lingkungan_suhu_max_harian, 2),
                'delta_lingkungan_persen_hum_harian' => round($delta_lingkungan_persen_hum_harian, 2),
                'std_fcr_harian' => $standardHariIni->fcr_std ?? 0,
                'act_fcr_harian' => round($act_fcr_harian, 3),
                'delta_fcr_harian' => round($delta_fcr_harian, 3),
                'std_index_performance_harian' => $standardHariIni->ip_std ?? 0,
                'act_index_performance_harian' => $act_index_performance_harian,
                'delta_index_performance_harian' => $delta_index_performance_harian,
                'jumlah_ayam_dipanen' => 0,
                'jumlah_ayam_akhir' => $jumlah_ayam_akhir
            ]
        );

        Log::info('Ringkasan harian berhasil disimpan/diupdate untuk DataPeriode ID: ' . $dataPeriode->id .
            ' - FCR: ' . round($act_fcr_harian, 3) .
            ', IP: ' . $act_index_performance_harian .
            ', Deplesi: ' . round($cum_deplesi_harian, 2) . '%');
    }
}
