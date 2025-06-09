<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokPakanKeluar;
use App\Models\StokPakan;
use App\Models\DataPeriode;

class StokPakanKeluarController extends Controller
{
    public function index($peternakan, $kandang, $periode, $stok_pakan)
    {
        $stokPakan = StokPakan::with('pakanJenis')->findOrFail($stok_pakan);
        $stokKeluar = $stokPakan->stokPakanKeluar()->with('dataPeriode')->latest()->get();

        return view('stok_pakan_keluar.index', compact('stokKeluar', 'stokPakan'));
    }

    public function create($peternakan, $kandang, $periode, $stok_pakan)
    {
        $stokPakan = StokPakan::with('pakanJenis')->findOrFail($stok_pakan);
        $dataPeriode = DataPeriode::where('periode_id', $periode)->get();

        return view('stok_pakan_keluar.create', compact('stokPakan', 'dataPeriode'));
    }

    public function store(Request $request, $peternakan, $kandang, $periode, $stok_pakan)
    {
        $request->validate([
            'data_periode_id' => 'required|exists:data_periode,data_periode_id',
            'jumlah_keluar' => 'required|integer|min:1'
        ]);

        StokPakanKeluar::create([
            'data_periode_id' => $request->data_periode_id,
            'stok_pakan_id' => $stok_pakan,
            'jumlah_keluar' => $request->jumlah_keluar,
        ]);

        $stok = StokPakan::findOrFail($stok_pakan);
        $stok->decrement('jumlah_stok', $request->jumlah_keluar);

        return redirect()->route('dev.stok_pakan.keluar.index', compact('peternakan', 'kandang', 'periode', 'stok_pakan'))
            ->with('success', 'Stok pakan keluar berhasil disimpan.');
    }

    public function show($peternakan, $kandang, $periode, $stok_pakan, $id)
    {
        $stokKeluar = StokPakanKeluar::with(['stokPakan', 'dataPeriode'])->findOrFail($id);
        return view('stok_pakan_keluar.show', compact('stokKeluar'));
    }

    public function edit($peternakan, $kandang, $periode, $stok_pakan, $id)
    {
        $stokKeluar = StokPakanKeluar::findOrFail($id);
        $dataPeriode = DataPeriode::where('periode_id', $periode)->get();

        return view('stok_pakan_keluar.edit', compact('stokKeluar', 'dataPeriode'));
    }

    public function update(Request $request, $peternakan, $kandang, $periode, $stok_pakan, $id)
    {
        $request->validate([
            'jumlah_keluar' => 'required|integer|min:1'
        ]);

        $stokKeluar = StokPakanKeluar::findOrFail($id);
        $stok = StokPakan::findOrFail($stok_pakan);

        $stok->increment('jumlah_stok', $stokKeluar->jumlah_keluar); // rollback
        $stok->decrement('jumlah_stok', $request->jumlah_keluar);    // update baru

        $stokKeluar->update([
            'jumlah_keluar' => $request->jumlah_keluar,
        ]);

        return redirect()->route('dev.stok_pakan.keluar.index', compact('peternakan', 'kandang', 'periode', 'stok_pakan'))
            ->with('success', 'Stok pakan keluar berhasil diperbarui.');
    }

    public function destroy($peternakan, $kandang, $periode, $stok_pakan, $id)
    {
        $stokKeluar = StokPakanKeluar::findOrFail($id);
        $stok = StokPakan::findOrFail($stok_pakan);
        $stok->increment('jumlah_stok', $stokKeluar->jumlah_keluar);

        $stokKeluar->delete();

        return redirect()->route('dev.stok_pakan.keluar.index', compact('peternakan', 'kandang', 'periode', 'stok_pakan'))
            ->with('success', 'Data stok pakan keluar berhasil dihapus.');
    }
}
