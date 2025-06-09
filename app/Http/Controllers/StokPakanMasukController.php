<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokPakanMasuk;
use App\Models\StokPakan;
use App\Models\Peternakan;
use App\Models\Kandangs;
use App\Models\Periode;
use App\Models\PakanJenis;
use Illuminate\Http\RedirectResponse;

class StokPakanMasukController extends Controller
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

        $pakanJenis = PakanJenis::all();

        return view('developer.stok_pakan.masuk.create', compact(
            'peternakan',
            'kandang',
            'periodeAktif',
            'pakanJenis'
        ));
    }

    public function store(Request $request, Peternakan $peternakan, Kandangs $kandang)
    {
        $request->validate([
            'pakan_jenis_id' => 'required|exists:pakan_jenis,id',
            'tanggal'        => 'required|date',
            'jumlah_masuk'   => 'required|integer|min:1',
        ]);

        $periodeAktif = $kandang->periodes()->where('aktif', true)->first();

        if (!$periodeAktif) {
            return back()->with('error', 'Belum ada periode aktif di kandang ini.');
        }

        // Cari stok_pakan ID yang sesuai
        $stokPakan = StokPakan::firstOrCreate([
            'periode_id'     => $periodeAktif->id,
            'kandang_id'     => $kandang->id,
            'pakan_jenis_id' => $request->pakan_jenis_id,
        ], [
            'jumlah_stok' => 0,
        ]);

        // Simpan ke tabel stok_pakan_masuk
        StokPakanMasuk::create([
            'stok_pakan_id' => $stokPakan->id,
            'tanggal'       => $request->tanggal,
            'jumlah_masuk'  => $request->jumlah_masuk,
        ]);

        // Tambahkan stok pada tabel stok_pakan
        $stokPakan->increment('jumlah_stok', $request->jumlah_masuk);

        return redirect()
            ->route('developer.stok_pakan.index', [
                'peternakan' => $peternakan->slug,
                'kandang' => $kandang->slug,
                'tab' => 'masuk'
            ])
            ->with('success', 'Stok pakan masuk berhasil dicatat.');
    }

    public function edit(Peternakan $peternakan, Kandangs $kandang, StokPakanMasuk $masuk)
    {
        $periodeAktif = $kandang->periodes()->where('aktif', true)->first();

        if (!$periodeAktif) {
            return back()->with('error', 'Belum ada periode aktif di kandang ini.');
        }

        // Load the related stok pakan data
        $masuk->load('stokPakan.pakanJenis');
        $pakanJenis = PakanJenis::all();

        return view('developer.stok_pakan.masuk.edit', compact(
            'peternakan',
            'kandang',
            'periodeAktif',
            'pakanJenis',
            'masuk',
        ));
    }

    public function update(Request $request, Peternakan $peternakan, Kandangs $kandang, StokPakanMasuk $masuk)
    {
        $request->validate([
            'pakan_jenis_id' => 'required|exists:pakan_jenis,id',
            'tanggal' => 'required|date',
            'jumlah_masuk' => 'required|integer|min:1'
        ]);

        $periodeAktif = $kandang->periodes()->where('aktif', true)->first();

        if (!$periodeAktif) {
            return back()->with('error', 'Belum ada periode aktif di kandang ini.');
        }

        // Get current stok_pakan record with eager loading
        $masuk->load('stokPakans');
        $currentStokPakan = $masuk->stokPakans;

        if (!$currentStokPakan) {
            return back()->with('error', 'Data stok pakan tidak ditemukan.');
        }

        // If pakan type changed, create or get new stok_pakan record
        if ($currentStokPakan->pakan_jenis_id != $request->pakan_jenis_id) {
            // Decrease stock from old stok_pakan record
            $currentStokPakan->decrement('jumlah_stok', $masuk->jumlah_masuk);

            // Get or create new stok_pakan record
            $newStokPakan = StokPakan::firstOrCreate([
                'periode_id' => $periodeAktif->id,
                'kandang_id' => $kandang->id,
                'pakan_jenis_id' => $request->pakan_jenis_id,
            ], [
                'jumlah_stok' => 0,
            ]);

            // Update stok_pakan_id in masuk record
            $masuk->stok_pakan_id = $newStokPakan->id;

            // Increment new stok_pakan
            $newStokPakan->increment('jumlah_stok', $request->jumlah_masuk);
        } else {
            // Just update the stock amount if pakan type hasn't changed
            $currentStokPakan->decrement('jumlah_stok', $masuk->jumlah_masuk);
            $currentStokPakan->increment('jumlah_stok', $request->jumlah_masuk);
        }

        // Update the masuk record
        $masuk->tanggal = $request->tanggal;
        $masuk->jumlah_masuk = $request->jumlah_masuk;
        $masuk->save();

        return redirect()
            ->route('developer.stok_pakan.index', [
                'peternakan' => $peternakan->slug,
                'kandang' => $kandang->slug,
                'tab' => 'masuk'
            ])
            ->with('success', 'Data stok pakan masuk berhasil diperbarui.');
    }


    public function destroy(
        Peternakan $peternakan,
        Kandangs $kandang,
        StokPakanMasuk $masuk,
    ): RedirectResponse {
        $masuk->stokPakans->decrement('jumlah_stok', $masuk->jumlah_masuk);

        // 2) Hapus record transaksi masuk
        $masuk->delete();

        return redirect()->route('developer.stok_pakan.index', [
            'peternakan' => $peternakan,
            'kandang' => $kandang,
            'tab' => 'masuk',
        ])->with('success', 'Transaksi Pakan Masuk Berhasil Dihapus');
    }
}
