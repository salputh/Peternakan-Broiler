<?php

namespace App\Http\Controllers;

use App\Models\Peternakan;
use Illuminate\Http\Request;

class PeternakanController extends Controller
{
    public function index()
    {
        $peternakans = Peternakan::all();
        return view('peternakan.peternakan-show', compact('peternakans'));
    }

    public function create()
    {
        return view('peternakan.peternakan-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'peternakan_nama' => 'required|string|max:255'
        ]);

        Peternakan::create([
            'peternakan_nama' => $request->peternakan_nama,
        ]);

        return redirect()->route('peternakan.index')->with('success', 'Peternakan berhasil ditambahkan.');
    }

    public function show(Peternakan $peternakan)
    {
        return view('peternakan.show', compact('peternakan'));
    }

    public function edit(Peternakan $peternakan)
    {
        return view('peternakan.edit', compact('peternakan'));
    }

    public function update(Request $request, Peternakan $peternakan)
    {
        $request->validate([
            'peternakan_nama' => 'required|string|max:255'
        ]);

        $peternakan->update([
            'peternakan_nama' => $request->peternakan_nama,
        ]);

        return redirect()->route('peternakan.index')->with('success', 'Data peternakan berhasil diperbarui.');
    }

    public function destroy(Peternakan $peternakan)
    {
        $peternakan->delete();
        return redirect()->route('peternakan.index')->with('success', 'Peternakan berhasil dihapus.');
    }
}
