<?php

namespace App\Http\Controllers;

use App\Models\BodyWeight;
use App\Models\DataPeriode;
use Illuminate\Http\Request;

class BodyWeightController extends Controller
{
    public function index()
    {
        $bodyWeights = BodyWeight::with('dataPeriode')->get();
        return view('bodyweight.index', compact('bodyWeights'));
    }

    public function create()
    {
        $dataPeriodes = DataPeriode::all();
        return view('bodyweight.create', compact('dataPeriodes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'data_periode_id' => 'required|exists:data_periode,data_periode_id',
            'body_weight_hasil' => 'required|numeric|min:0',
        ]);

        BodyWeight::create($validated);

        return redirect()->route('bodyweight.index')
            ->with('success', 'Berat ayam berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $bodyWeight = BodyWeight::with('dataPeriode')->findOrFail($id);
        return view('bodyweight.show', compact('bodyWeight'));
    }

    public function edit(string $id)
    {
        $bodyWeight = BodyWeight::findOrFail($id);
        $dataPeriodes = DataPeriode::all();
        return view('bodyweight.edit', compact('bodyWeight', 'dataPeriodes'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'data_periode_id' => 'required|exists:data_periode,data_periode_id',
            'body_weight_hasil' => 'required|numeric|min:0',
        ]);

        $bodyWeight = BodyWeight::findOrFail($id);
        $bodyWeight->update($validated);

        return redirect()->route('bodyweight.show', $bodyWeight->body_weight_id)
            ->with('success', 'Berat ayam berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $bodyWeight = BodyWeight::findOrFail($id);
        $bodyWeight->delete();

        return redirect()->route('bodyweight.index')
            ->with('success', 'Berat ayam berhasil dihapus.');
    }
}
