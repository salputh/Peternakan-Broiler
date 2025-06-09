<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokPakan;
use App\Models\StokPakanMasuk;
use App\Models\StokPakanKeluar;
use App\Models\Periode;
use App\Models\Kandangs;
use App\Models\PakanJenis;
use App\Models\Peternakan;
use Illuminate\Http\RedirectResponse;

class StokPakanController extends Controller
{
    public function index(Peternakan $peternakan, Kandangs $kandang)
    {
        // 1) ambil semua periode (jika butuh dropdown nanti)
        $daftarPeriode = $kandang
            ->periodes()
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // 2) pastikan ada periode aktif
        $periodeAktif = $daftarPeriode
            ->firstWhere('aktif', true);

        if (! $periodeAktif) {
            return back()->with('error', 'Belum ada periode aktif di kandang ini.');
        }

        // 3) ambil semua jenis pakan (kode SB10, SB11, dsb)
        $pakanJenis = PakanJenis::all();

        // 4) bangun ringkasan: total masuk – total keluar per jenis
        $ringkasan = $pakanJenis->map(function ($pj) use ($periodeAktif, $kandang) {
            $stokPakan = StokPakan::firstOrCreate([
                'periode_id'     => $periodeAktif->id,
                'kandang_id'     => $kandang->id,
                'pakan_jenis_id' => $pj->id,
            ], [
                'jumlah_stok'    => 0
            ]);

            $totalMasuk = $stokPakan->stokPakanMasuk()->sum('jumlah_masuk');
            $totalKeluar = $stokPakan->stokPakanKeluar()->sum('jumlah_keluar');

            return (object) [
                'id'       => $pj->id,
                'kode'     => $pj->kode,
                'satuan'   => 'ZAK',
                'keterangan' => $pj->keterangan,
                'masuk'    => $totalMasuk,
                'keluar'   => $totalKeluar,
                'tersedia' => $totalMasuk - $totalKeluar,
            ];
        });

        // 5) ambil semua transaksi masuk untuk periode aktif
        try {
            $queryMasuk = StokPakanMasuk::with('stokPakans.pakanJenis')
                ->whereHas('stokPakans', function ($query) use ($periodeAktif, $kandang) {
                    $query->where('periode_id', $periodeAktif->id)
                        ->where('kandang_id', $kandang->id);
                });

            // DEBUGGING: Periksa query SQL dan binding
            $masukList = $queryMasuk->orderBy('tanggal', 'desc')->get();
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengambil data stok pakan masuk: ' . $e->getMessage());
        }

        // 6) ambil semua transaksi keluar untuk periode aktif
        $keluarList = StokPakanKeluar::with('stokPakans.pakanJenis')
            ->whereHas('stokPakans', function ($query) use ($periodeAktif, $kandang) {
                $query->where('periode_id', $periodeAktif->id)
                    ->where('kandang_id', $kandang->id);
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalPeriode = $daftarPeriode->count();

        $masuk = new StokPakanMasuk();

        return view('developer.stok_pakan.index', compact(
            'peternakan',
            'kandang',
            'daftarPeriode',
            'periodeAktif',
            'ringkasan',
            'totalPeriode',
            'masukList',
            'keluarList',
            'pakanJenis',
            'masuk',
        ));
    }
}
