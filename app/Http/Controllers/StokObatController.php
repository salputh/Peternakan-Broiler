<?php

namespace App\Http\Controllers;

use App\Models\Peternakan;
use App\Models\StokObat;
use App\Models\Kandangs;
use App\Models\ObatJenis;
use App\Models\StokObatMasuk;
use App\Models\StokObatKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class StokObatController extends Controller
{
     public function index(Peternakan $peternakan, Kandangs $kandang)
     {
          // 1) Ambil semua periode terkait kandang, diurutkan dari terbaru
          $daftarPeriode = $kandang
               ->periodes()
               ->orderBy('tanggal_mulai', 'desc')
               ->get();

          // 2) Cari periode yang aktif
          $periodeAktif = $daftarPeriode
               ->firstWhere('aktif', true);

          // Jika tidak ada periode aktif, berikan pesan error
          if (!$periodeAktif) {
               return back()->with('error', 'Belum ada periode aktif di kandang ini. Silakan mulai periode baru.');
          }


          // --- 2) Dapatkan Daftar Obat ---
          // Mengambil semua jenis obat yang relevan dengan periode dan kandang aktif

          // --- 3) Dapatkan Ringkasan Stok Obat (Agregasi) ---
          // Ini adalah query kompleks untuk mendapatkan total masuk, keluar, dan tersedia
          // untuk setiap jenis obat yang relevan dengan periode dan kandang aktif.
          try {
               $ringkasan = StokObat::select(
                    'stok_obat.id',
                    'stok_obat.nama_obat',
                    'stok_obat.kategori',
                    'stok_obat.satuan',
                    DB::raw('COALESCE(SUM(DISTINCT stok_obat_masuk.jumlah_masuk), 0) as jumlah_masuk'),
                    DB::raw('COALESCE(SUM(DISTINCT stok_obat_keluar.jumlah_keluar), 0) as jumlah_terpakai'),
                    DB::raw('(COALESCE(SUM(DISTINCT stok_obat_masuk.jumlah_masuk), 0) - COALESCE(SUM(DISTINCT stok_obat_keluar.jumlah_keluar), 0)) as jumlah_tersedia')
               )
                    ->leftJoin('stok_obat_masuk', function ($join) use ($periodeAktif, $kandang) {
                         $join->on('stok_obat.id', '=', 'stok_obat_masuk.stok_obat_id')
                              ->where('stok_obat_masuk.periode_id', $periodeAktif->id)
                              ->where('stok_obat_masuk.kandang_id', $kandang->id)
                              ->where('stok_obat_masuk.tanggal', '<=', now());
                    })
                    ->leftJoin('stok_obat_keluar', function ($join) use ($periodeAktif) {
                         $join->on('stok_obat.id', '=', 'stok_obat_keluar.stok_obat_id')
                              ->whereIn('stok_obat_keluar.data_periode_id', function ($query) use ($periodeAktif) {
                                   $query->select('id')
                                        ->from('data_periodes')
                                        ->where('periode_id', $periodeAktif->id);
                              })
                              ->where('stok_obat_keluar.tanggal', '<=', now());
                    })
                    ->groupBy('stok_obat.id', 'stok_obat.nama_obat', 'stok_obat.kategori', 'stok_obat.satuan')
                    ->orderBy('stok_obat.nama_obat')
                    ->get();
          } catch (\Exception $e) {
               Log::error('Error fetching aggregated medicine stock data:', [
                    'periode_id' => $periodeAktif->periode_id,
                    'kandang_id' => $kandang->kandang_id,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString() // Sertakan trace untuk debugging
               ]);
               return back()->with('error', 'Terjadi kesalahan saat mengambil ringkasan stok obat: ' . $e->getMessage());
          }


          // --- 4) Dapatkan Daftar Detail Stok Obat Masuk ---
          // Mengambil semua transaksi masuk untuk periode dan kandang aktif, dengan eager loading relasi
          try {
               $masukList = StokObatMasuk::with(['stokObats', 'periodes', 'kandangs']) // Eager load relasi obat, periode, kandang
                    ->where('periode_id', $periodeAktif->id) // Filter berdasarkan periode aktif
                    ->where('kandang_id', $kandang->id) // Filter berdasarkan kandang saat ini
                    ->orderBy('tanggal', 'desc') // Urutkan berdasarkan tanggal masuk
                    ->get();
          } catch (\Exception $e) {
               Log::error('Error fetching incoming medicine stock list:', [
                    'periode_id' => $periodeAktif->id,
                    'kandang_id' => $kandang->id,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString() // Sertakan trace untuk debugging
               ]);
               return back()->with('error', 'Terjadi kesalahan saat mengambil daftar stok obat masuk: ' . $e->getMessage());
          }


          // --- 5) Dapatkan Daftar Detail Stok Obat Keluar ---
          // Mengambil semua transaksi keluar yang terkait dengan periode aktif, dengan eager loading relasi
          try {
               $keluarList = StokObatKeluar::with(['stokObats', 'dataPeriodes']) // Ubah dari 'stok_obat' ke 'stokObats'
                    ->whereHas('dataPeriodes', function ($query) use ($periodeAktif) {
                         $query->where('data_periode_id', $periodeAktif->id); // Menggunakan periode_id yang benar
                    })
                    ->where('tanggal', '<=', now()) // Menambahkan filter tanggal
                    ->orderBy('tanggal', 'desc') // Mengurutkan berdasarkan tanggal
                    ->get();
          } catch (\Exception $e) {
               Log::error('Error fetching outgoing medicine stock list:', [
                    'data_periode_id' => $periodeAktif->id,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
               ]);
               return back()->with('error', 'Terjadi kesalahan saat mengambil daftar stok obat keluar: ' . $e->getMessage());
          }

          // Hitung total periode (sudah ada)
          $totalPeriode = $daftarPeriode->count();

          // Variabel $masuk (singular) kemungkinan untuk form input di view.
          // Jika tidak ada form di halaman index ini, baris ini bisa dihapus.
          $masuk = new StokObatMasuk();
          $daftarObat = StokObat::all();

          // Mengembalikan view dengan semua data yang diperlukan
          return view('developer.stok_obat.index', compact(
               'peternakan',
               'kandang',
               'daftarPeriode',
               'periodeAktif',
               'ringkasan',
               'totalPeriode',
               'masukList',
               'keluarList',
               'daftarObat',
               'masuk' // Pass variabel ini jika diperlukan untuk form di view
          ));
     }

     public function create()
     {
          return view('stok-obat.create');
     }

     // public function store(Request $request)
     // {
     //      $validated = $request->validate([
     //           'nama_obat' => 'required|string|max:255',
     //           'jumlah' => 'required|numeric|min:0',
     //           'satuan' => 'required|string|max:50',
     //           'tanggal_masuk' => 'required|date',
     //           'tanggal_kadaluarsa' => 'required|date|after:tanggal_masuk',
     //           'harga' => 'required|numeric|min:0'
     //      ]);

     //      StokObat::create($validated);

     //      return redirect()->route('developer.stok-obat.index')
     //           ->with('success', 'Medicine stock added successfully');
     // }

     // public function show(StokObat $stokObat)
     // {
     //      return view('stok-obat.show', compact('stokObat'));
     // }

     // public function edit(StokObat $stokObat)
     // {
     //      return view('stok-obat.edit', compact('stokObat'));
     // }

     // public function update(Request $request, StokObat $stokObat)
     // {
     //      $validated = $request->validate([
     //           'nama_obat' => 'required|string|max:255',
     //           'jumlah' => 'required|numeric|min:0',
     //           'satuan' => 'required|string|max:50',
     //           'tanggal_masuk' => 'required|date',
     //           'tanggal_kadaluarsa' => 'required|date|after:tanggal_masuk',
     //           'harga' => 'required|numeric|min:0'
     //      ]);

     //      $stokObat->update($validated);

     //      return redirect()->route('stok-obat.index')
     //           ->with('success', 'Medicine stock updated successfully');
     // }

     // public function destroy(StokObat $stokObat)
     // {
     //      $stokObat->delete();

     //      return redirect()->route('stok-obat.index')
     //           ->with('success', 'Medicine stock deleted successfully');
     // }
}
