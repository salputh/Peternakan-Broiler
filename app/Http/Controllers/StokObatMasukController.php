<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peternakan;
use App\Models\Kandangs;
use App\Models\StokObat;
use App\Models\StokObatMasuk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StokObatMasukController extends Controller
{
     public function create(Peternakan $peternakan, Kandangs $kandang)
     {
          $daftarPeriode = $kandang
               ->periodes()
               ->orderBy('tanggal_mulai', 'desc')
               ->get();

          $periodeAktif = $daftarPeriode
               ->firstWhere('aktif', true);

          if (!$periodeAktif) {
               return back()->with('error', 'Belum ada periode aktif di kandang ini.');
          }

          // Ambil semua daftar obat yang sudah ada dari tabel 'obats'
          // Ini bisa digunakan di view untuk dropdown atau autocomplete
          $daftarObat = StokObat::all();

          return view('developer.stok_obat.masuk.create', compact(
               'peternakan',
               'kandang',
               'periodeAktif',
               'daftarObat' // Mengganti obatJenis menjadi daftarObat
          ));
     }

     public function store(Request $request, Peternakan $peternakan, Kandangs $kandang)
     {
          // 1. Validasi input dari form
          $request->validate([
               'nama_obat_input' => 'required|string|max:255', // Sesuai nama kolom di migrasi dan form
               'kategori_input'  => 'nullable|string|max:100',  // Kategori opsional, sesuai migrasi
               'tanggal'         => 'required|date',
               'jumlah_masuk'    => 'required|integer|min:1',
          ]);

          // 2. Cari periode aktif untuk kandang ini
          $periodeAktif = $kandang->periodes()->where('aktif', true)->first();

          if (!$periodeAktif) {
               return back()->with('error', 'Belum ada periode aktif di kandang ini. Silakan mulai periode baru.');
          }

          // 3. Logika untuk menemukan atau membuat entri di tabel 'stok_obat' (master list obat)
          // Menggunakan model 'Obat' yang merepresentasikan tabel 'stok_obat'
          $obat = StokObat::firstOrNew(['nama_obat' => $request->nama_obat_input]);

          // Jika obat belum ada (baru), atau jika kategori di tabel 'stok_obat' masih kosong (NULL)
          // dan kategori_input disediakan, maka isi/update kategori di tabel 'stok_obat'.
          // Jika nama obat sudah ada tetapi kategorinya kosong, dan ada kategori_input, maka update.
          // Atau jika kategori_input berbeda dari yang sudah ada, update.
          if (!$obat->exists || ($request->filled('kategori_input') && $obat->kategori !== $request->kategori_input)) {
               $obat->kategori = $request->kategori_input; // Ini akan menjadi NULL jika kategori_input kosong
               // Jika Anda juga ingin mengupdate satuan dari inputan, tambahkan di sini:
               // $obat->satuan = $request->satuan_input; // Asumsi ada satuan_input di form
               $obat->save(); // Simpan obat baru atau update kategori obat yang sudah ada di tabel 'stok_obat'
          }
          // Jika obat sudah ada dan kategori_input tidak diisi, biarkan kategori yang ada di tabel 'stok_obat'
          // Jika obat sudah ada dan kategori_input diisi sama dengan yang ada, tidak perlu update

          // 4. Simpan data ke tabel stok_obat_masuk
          try {
               StokObatMasuk::create([
                    'nama_obat_input' => $request->nama_obat_input,   // Simpan input mentah nama obat
                    'kategori_input'  => $request->kategori_input,    // Simpan input mentah kategori (bisa NULL)
                    'stok_obat_id'    => $obat->id,                    // Menggunakan ID dari tabel 'stok_obat'
                    'periode_id'      => $periodeAktif->id,   // Menggunakan periode_id dari periode aktif
                    'kandang_id'      => $kandang->id,       // Menggunakan kandang_id dari kandang saat ini
                    'tanggal'         => $request->tanggal,
                    'jumlah_masuk'    => $request->jumlah_masuk,
               ]);
          } catch (\Exception $e) {
               // Tangani kesalahan saat menyimpan data ke database
               Log::error('Error saving incoming medicine stock:', [
                    'request_data' => $request->all(),
                    'stok_obat_id' => $obat->id,
                    'periode_id'   => $periodeAktif->id,
                    'kandang_id'   => $kandang->id,
                    'message'      => $e->getMessage(),
                    'trace'        => $e->getTraceAsString()
               ]);
               return back()->with('error', 'Terjadi kesalahan saat mencatat stok obat masuk. Silakan coba lagi.');
          }

          // 5. Redirect kembali ke halaman index stok obat dengan pesan sukses
          return redirect()
               ->route('developer.stok_obat.index', [
                    'peternakan' => $peternakan->slug,
                    'kandang'    => $kandang->slug,
                    'tab'        => 'masuk' // Opsional: untuk mengarahkan ke tab 'masuk' jika ada di view
               ])
               ->with('success', 'Stok obat masuk berhasil dicatat.');
     }

     public function update(Request $request, Peternakan $peternakan, Kandangs $kandang, StokObatMasuk $masuk)
     {
          $request->validate([
               'stok_obat_id'    => 'required|exists:stok_obat,id',
               'nama_obat_input' => 'required|string|max:255',
               'kategori_input'  => 'required|string|max:255',
               'tanggal'         => 'required|date',
               'jumlah_masuk'    => 'required|integer|min:1'
          ]);

          DB::beginTransaction();
          try {
               $masterStokObat = StokObat::findOrFail($request->stok_obat_id);

               $masterStokObat->nama_obat = $request->nama_obat_input;
               $masterStokObat->kategori  = $request->kategori_input;

               $masterStokObat->save();

               // Update field pada record transaksi StokObatMasuk
               $masuk->stok_obat_id    = $request->stok_obat_id;
               $masuk->nama_obat_input = $request->nama_obat_input;
               $masuk->kategori_input  = $request->kategori_input;
               $masuk->tanggal         = $request->tanggal;
               $masuk->jumlah_masuk    = $request->jumlah_masuk;
               $masuk->save();

               DB::commit();

               return redirect()
                    ->route('developer.stok_obat.index', [
                         'peternakan' => $peternakan->slug,
                         'kandang'    => $kandang->slug,
                         'tab'        => 'masuk'
                    ])
                    ->with('success', 'Data stok obat masuk dan daftar stok obat berhasil diperbarui.');
          } catch (\Exception $e) {
               DB::rollBack();
               return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
          }
     }

     public function destroy(
          Peternakan $peternakan,
          Kandangs $kandang,
          StokObatMasuk $masuk
     ): RedirectResponse {
          // Get active period for the selected kandang
          $periodeAktif = $kandang->periodes()->where('aktif', true)->first();

          if (!$periodeAktif) {
               return back()->with('error', 'Tidak ada periode aktif di kandang ini.');
          }

          // Verify that the medicine record belongs to the active period and kandang
          if ($masuk->periode_id !== $periodeAktif->id || $masuk->kandang_id !== $kandang->id) {
               return back()->with('error', 'Data obat masuk tidak ditemukan untuk periode dan kandang yang aktif.');
          }

          DB::transaction(function () use ($masuk) {
               // Only delete the incoming transaction record
               // We don't delete the master StokObat record anymore since it might be used by other transactions
               $masuk->delete();
          });

          return redirect()->route('developer.stok_obat.index', [
               'peternakan' => $peternakan->slug,
               'kandang'    => $kandang->slug,
               'tab'        => 'masuk',
          ])->with('success', 'Transaksi Obat Masuk Berhasil Dihapus');
     }
}
