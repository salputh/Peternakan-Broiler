<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Peternakan;
use App\Models\Kandangs;
use App\Models\StandardPerformaHarian;
use App\Models\StokObat;
use App\Models\PakanJenis;

class KandangDashboardController extends Controller
{
     public function index(Peternakan $peternakan, Kandangs $kandang)
     {
          $peternakans = Peternakan::all();

          // 1) Ambil semua periode kandang yang aktif
          $daftarPeriode = $kandang
               ->periodes()
               ->orderBy('tanggal_mulai', 'desc')
               ->get();

          // 2) Cari periode yang aktif
          $periodeAktif = $daftarPeriode->firstWhere('aktif', true);
          $totalPeriode = $daftarPeriode->count();

          // Initialize default values
          $standardPerforma = collect();
          $stokPakan = collect();
          $stokObat = collect();
          $usiaAktif = 0;
          $standardHariIni = null;
          $pakanJenisOptions = PakanJenis::all();
          $stokObatOptions = collect();


          // 3) Jika ada periode aktif, ambil stoknya, kalau enggak, stok kosong
          if ($periodeAktif) {
               $stokPakan = $periodeAktif
                    ->stokPakans() // Asumsi relasi ini masih ada di Periode.php
                    ->with('pakanJenis') // Asumsi relasi ini masih ada di StokPakan.php
                    ->get();
               // --- Fetch Semua Data Standard Performa ---
               $standardPerforma = StandardPerformaHarian::all();

               // --- Hitung Usia Aktif ---
               $usiaAktif = (int)(\Carbon\Carbon::parse($periodeAktif->tanggal_mulai)->diffInDays(now()) + 1);

               // Ini akan menampilkan objek standardHariIni (seharusnya tidak null jika data ada)
               $standardHariIni = $standardPerforma->where('age', $usiaAktif)->first();

               // --- Fetch Stok Obat (BARU: Menggunakan agregasi dari tabel transaksi) ---
               $stokObat = StokObat::select(
                    'stok_obat.id',
                    'stok_obat.nama_obat',
                    'stok_obat.kategori',
                    'stok_obat.satuan',
                    DB::raw('COALESCE(SUM(stok_obat_masuk.jumlah_masuk), 0) as jumlah_masuk'),
                    DB::raw('COALESCE(SUM(stok_obat_keluar.jumlah_keluar), 0) as jumlah_terpakai'),
                    DB::raw('(COALESCE(SUM(stok_obat_masuk.jumlah_masuk), 0) - COALESCE(SUM(stok_obat_keluar.jumlah_keluar), 0)) as jumlah_tersedia')
               )
                    ->leftJoin('stok_obat_masuk', function ($join) use ($periodeAktif, $kandang) {
                         $join->on('stok_obat.id', '=', 'stok_obat_masuk.stok_obat_id')
                              ->where('stok_obat_masuk.periode_id', $periodeAktif->id)
                              ->where('stok_obat_masuk.kandang_id', $kandang->id);
                    })
                    ->leftJoin('stok_obat_keluar', function ($join) use ($periodeAktif) {
                         $join->on('stok_obat.id', '=', 'stok_obat_keluar.stok_obat_id')
                              ->whereIn('stok_obat_keluar.data_periode_id', function ($query) use ($periodeAktif) {
                                   $query->select('data_periode_id')
                                        ->from('data_periodes')
                                        ->where('periode_id', $periodeAktif->id);
                              });
                    })
                    ->groupBy('stok_obat.id', 'stok_obat.nama_obat', 'stok_obat.kategori', 'stok_obat.satuan')
                    ->having('jumlah_tersedia', '>', 0)
                    ->orderBy('stok_obat.nama_obat')
                    ->get();


               // Filterisasi stok obat yang hanya memiliki stok masuk pada periode aktif
               $stokObatOptions = StokObat::select(
                    'stok_obat.id',
                    'stok_obat.nama_obat',
                    'stok_obat.kategori',
                    'stok_obat.satuan',
                    DB::raw('COALESCE(SUM(stok_obat_masuk.jumlah_masuk), 0) as jumlah_masuk'),
                    DB::raw('COALESCE(SUM(stok_obat_keluar.jumlah_keluar), 0) as jumlah_terpakai'),
                    DB::raw('(COALESCE(SUM(stok_obat_masuk.jumlah_masuk), 0) - COALESCE(SUM(stok_obat_keluar.jumlah_keluar), 0)) as jumlah_tersedia')
               )
                    ->leftJoin('stok_obat_masuk', function ($join) use ($periodeAktif, $kandang) {
                         $join->on('stok_obat.id', '=', 'stok_obat_masuk.stok_obat_id')
                              ->where('stok_obat_masuk.periode_id', $periodeAktif->id)
                              ->where('stok_obat_masuk.kandang_id', $kandang->id);
                    })
                    ->leftJoin('stok_obat_keluar', function ($join) use ($periodeAktif) {
                         $join->on('stok_obat.id', '=', 'stok_obat_keluar.stok_obat_id')
                              ->whereIn('stok_obat_keluar.data_periode_id', function ($query) use ($periodeAktif) {
                                   $query->select('data_periode_id')
                                        ->from('data_periodes')
                                        ->where('periode_id', $periodeAktif->id);
                              });
                    })
                    ->groupBy('stok_obat.id', 'stok_obat.nama_obat', 'stok_obat.kategori', 'stok_obat.satuan')
                    ->orderBy('stok_obat.nama_obat')
                    ->get();
          }


          return view('developer.dashboard.kandang', compact(
               'peternakan',
               'periodeAktif',
               'peternakans',
               'kandang',
               'stokPakan',
               'stokObat',
               'totalPeriode',
               'standardHariIni',
               'standardPerforma',
               'pakanJenisOptions',
               'stokObatOptions',
          ));
     }

     public function end(Peternakan $peternakan, Kandangs $kandang) // Ganti Kandangs menjadi Kandang jika itu nama modelnya
     {
          try {
               $periodeAktif = $kandang
                    ->periodes() // Asumsi relasi 'periodes'
                    ->where('aktif', true)
                    ->orderBy('tanggal_mulai', 'desc') // Order by sebenarnya tidak terlalu penting jika hanya mencari satu
                    ->first();

               if (!$periodeAktif) {
                    return back()->with('error', 'Tidak ditemukan periode aktif untuk kandang ini.');
               }

               $periodeAktif->update(['aktif' => false]);

               return redirect()
                    ->route('developer.periode.index', [
                         'peternakan' => $peternakan,
                         'kandang' => $kandang,
                    ])
                    ->with('success', "Periode untuk kandang {$kandang->nama_kandang} berhasil diakhiri!"); // Contoh pesan lebih spesifik
          } catch (\Exception $e) {
               Log::error('Error ending period for kandang ID: ' . $kandang->id, [ // Tambahkan konteks ke log
                    'peternakan_id' => $peternakan->id,
                    'kandang_id' => $kandang->id,
                    'message' => $e->getMessage(),
                    // 'trace' => $e->getTraceAsString() // Trace bisa sangat panjang, pertimbangkan untuk production
               ]);

               return back()->with('error', 'Terjadi kesalahan saat mengakhiri periode. Silakan coba lagi.');
          }
     }
}
