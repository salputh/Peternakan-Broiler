<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peternakan;

class DeveloperController extends Controller
{
     public function index()
     {
          // Ambil semua peternakan dengan owner dan jumlah kandang (relasi eager loading + withCount)
          $peternakans = Peternakan::with('owner')
               ->withCount('kandangs')
               ->orderBy('id', 'asc')
               ->get();

          return view('developer.dashboard.index', compact('peternakans'));
     }
}
