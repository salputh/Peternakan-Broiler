<?php

namespace App\Http\Controllers;

use App\Models\Kandangs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Peternakan;
use App\Models\Periode;

class KandangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Peternakan $peternakan)
    {
        $kandangs = $peternakan->kandangs;
        $kandangs = $peternakan->kandangs->map(function ($kandang) {
            // Get active period status for each kandang
            $aktivePeriode = $kandang->periodes()
                ->where('aktif', true)
                ->first();

            $kandang->periode_aktif = $aktivePeriode ? true : false;
            $kandang->nama_periode_aktif = $aktivePeriode ? $aktivePeriode->nama_periode : null;

            return $kandang;
        });
        return view('developer.kandang.index', compact('peternakan', 'kandangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Peternakan $peternakan)
    {
        return view('developer.kandang.create', compact('peternakan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Peternakan $peternakan)
    {
        $validated = Validator::make($request->all(), [
            'nama_kandang' => 'required|string|max:255',
            'kapasitas' => 'required|integer',
            'alamat' => 'required|string|max:255',
        ])->validate();

        $validated['peternakan_id'] = $peternakan->id;

        Kandangs::create($validated);

        return redirect()->route('developer.kandang.index', compact('peternakan'))
            ->with('success', 'Kandang created successfully.');
    }

    /**
     * Menampilkan detail 
     */

    public function show(Peternakan $peternakan, Kandangs $kandang, Periode $periode)
    {
        $daftarPeriode = $kandang->periodes()->get();
        $periodeAktif = $daftarPeriode->firstWhere('aktif', true);
        $totalPeriode = $daftarPeriode->count();

        if ($periodeAktif) {
            $stokPakan = $periodeAktif
                ->stokPakans()
                ->with('pakanJenis')
                ->get();

            $stokObat = $periodeAktif
                ->stokObats()
                ->with('obatJenis')
                ->get();
        } else {
            // inisialisasi koleksi kosong agar view-nya aman
            $stokPakan = collect();
            $stokObat = collect();
        }

        // 2) (Opsional) Kalau butuh summary lain, ambil juga di sini...
        // 3) Kirim ke view

        return view('developer.kandang.show', compact('peternakan', 'kandang', 'periode', 'stokPakan', 'stokObat', 'totalPeriode', 'periodeAktif'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peternakan $peternakan, Kandangs $kandang)
    {
        return view('developer.kandang.edit', compact('peternakan', 'kandang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peternakan $peternakan, Kandangs $kandang)
    {
        $validated = Validator::make($request->all(), [
            'nama_kandang' => 'required|string|max:255',
            'kapasitas' => 'required|integer',
            'alamat' => 'required|string|max:255',
        ])->validate();

        $validated['peternakan_id'] = $peternakan->id;

        $kandang->update($validated);

        return redirect()->route('developer.kandang.index', compact('peternakan'))
            ->with('success', 'Kandang updated successfully');
    }

    public function destroy(Peternakan $peternakan, Kandangs $kandang)
    {
        $kandang->delete();

        return redirect()->route('developer.kandang.index', compact('peternakan', 'kandang'))
            ->with('success', 'Kandang deleted successfully');
    }
}
