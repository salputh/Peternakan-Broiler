<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periode;
use App\Models\Kandangs;
use App\Models\Peternakan;
use App\Models\StokPakan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PeriodeController extends Controller
{
    public function index(Peternakan $peternakan, Kandangs $kandang)
    {
        // 1) Daftar semua periode untuk kandang ini
        $periodes = $kandang
            ->periodes()
            ->orderBy('tanggal_mulai')
            ->get();

        // 2) Hitung total periode (untuk badge di nav)
        $totalPeriode = $periodes->count();

        // 3) Ambil periode aktif (jika ada)
        $periodeAktif = $periodes->firstWhere('aktif', true);

        // 4) Siapkan stok pakan untuk periode aktif (opsional, bisa di‐load nanti oleh stok_pakan.index)
        $stokPakan = $periodeAktif
            ? $periodeAktif->stokPakans()->with('pakanJenis')->get()
            : collect();

        return view('developer.periode.index', compact(
            'peternakan',
            'kandang',
            'periodes',
            'totalPeriode',
            'periodeAktif',
            'stokPakan'
        ));
    }


    public function create(Peternakan $peternakan, Kandangs $kandang)
    {
        // 1) Ambil periode aktif (jika ada)
        $periodeAktif = $kandang
            ->periodes()
            ->where('aktif', true)
            ->first();

        $kandangs = Kandangs::all();

        // 2) Kirim ke view
        return view('developer.periode.create', [
            'kandangs',
            'peternakan' => $peternakan,
            'kandang' => $kandang,
            'periodeAktif',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request, Peternakan $peternakan, Kandangs $kandang)
    {

        Log::info('Periode store request data:', $request->all());
        // 1. Validasi input
        $data = $request->validate([
            'nama_periode'  => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'jumlah_ayam'   => 'required|integer|min:1',
        ]);

        Log::info('Periode validated data:', $data);

        try {
            // 2. Cek apakah sudah ada periode aktif
            if ($kandang->periodes()->where('aktif', true)->exists()) {
                return back()
                    ->with('error', 'Masih ada periode aktif di kandang ini. Harap akhiri periode tersebut terlebih dahulu.');
            }

            // 3. Buat periode baru lewat relasi (otomatis set kandang_id & slug)
            $periode = $kandang->periodes()->create([
                'nama_periode'  => $data['nama_periode'],
                'tanggal_mulai' => $data['tanggal_mulai'],
                'jumlah_ayam'   => $data['jumlah_ayam'],
                'aktif'         => true,
            ]);

            // 4. Redirect ke dashboard
            return redirect()->route('developer.dashboard.kandang', [
                'peternakan' => $peternakan,
                'kandang'    => $kandang,
            ])->with('success', 'Periode baru berhasil dibuat!');
        } catch (\Exception $e) {
            Log::error('Gagal membuat periode', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return back()
                ->with('error', 'Terjadi kesalahan saat membuat periode baru. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        $periode = Periode::findOrFail($id);
        $kandangs = Kandangs::all();
        return view('periode.edit', compact('periode', 'kandangs'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $request->validate([
            'kandang_id' => 'required|exists:kandangs,kandang_id',
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'jumlah_ayam' => 'required|integer|min:1'
        ]);

        $periode = Periode::findOrFail($id);
        $periode->update([
            'kandang_id' => $request->kandang_id,
            'nama_periode' => $request->periode_nama,
            'tanggal_mulai' => $request->periode_tanggal,
            'jumlah_ayam' => $request->periode_jumlah_ayam
        ]);

        return redirect()->route('dev.kandang.show', [
            'peternakan' => $request->peternakan_id ?? null,
            'kandang' => $request->kandang_id,
            'periode' => $periode->periode_id
        ])->with('success', 'Periode berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Peternakan $peternakan, Kandangs $kandang, Periode $periode)
    {
        $periode->delete();

        return redirect()->route('developer.periode.index', compact('peternakan', 'kandang'))
            ->with('success', 'Periode berhasil dihapus!');
    }

    /**
     * Akhiri periode (set aktif = false)
     */

    public function end(Peternakan $peternakan, Kandangs $kandang, Periode $periode)
    {
        $periode->update(['aktif' => false]);
        return redirect()->route('developer.periode.index', [
            'peternakan' => $peternakan,
            'kandang' => $kandang
        ])
            ->with('success', 'Periode berhasil diakhiri!');
    }
}
