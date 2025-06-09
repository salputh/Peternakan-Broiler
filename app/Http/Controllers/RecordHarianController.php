<?php

namespace App\Http\Controllers;

use App\Models\DataPeriode;
use App\Models\Deplesi;
use App\Models\StokPakanKeluar;
use App\Models\StokObatKeluar; // Pastikan ini di-import
use App\Models\BodyWeight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Untuk transaksi database
use Illuminate\Support\Facades\Log; // Untuk logging error yang lebih baik

class RecordHarianController extends Controller
{
     public function store(Request $request)
     {
          // 1. Validasi semua data dari form
          $validatedData = $request->validate([
               'periode_id' => 'required|exists:periodes,id',
               'tanggal' => 'required|date',
               'usia' => 'required|integer',
               // Suhu dan kelembaban sekarang wajib diisi
               'suhu_min' => 'required|numeric',
               'suhu_max' => 'required|numeric',
               'kelembapan' => 'required|numeric',
               'catatan' => 'nullable|string',

               // Data Deplesi - jumlah mati/afkir sekarang wajib diisi
               'jumlah_mati' => 'required|integer',
               'jumlah_afkir' => 'required|integer',
               'foto_deplesi' => 'nullable|string', // Foto bisa null
               'keterangan' => 'nullable|string', // Keterangan deplesi bisa null

               // Data Pakan Keluar - jumlah dan jenis pakan wajib diisi
               'pakan_keluar_jumlah' => 'required|numeric',
               'pakan_keluar_jenis_id' => 'required|exists:pakan_jenis,id',

               // Data Obat Keluar - jumlah dan jenis obat wajib diisi
               // Mengoreksi nama validasi agar konsisten dengan penggunaan di bawah
               'obat_keluar_jumlah' => 'required|numeric', // Diubah dari nullable ke required
               'stok_obat_id' => 'required|exists:stok_obat,id', // Mengoreksi nama dan memastikan required

               // Data Body Weight - hasil wajib diisi
               'body_weight_hasil' => 'required|numeric',
          ]);

          // Gunakan transaksi database untuk memastikan semua data tersimpan atau tidak sama sekali
          DB::beginTransaction();
          try {
               $dataPeriode = DataPeriode::updateOrCreate(
                    [
                         'periode_id' => $validatedData['periode_id'],
                         'tanggal' => $validatedData['tanggal'],
                    ],
                    [
                         'usia' => $validatedData['usia'],
                         'suhu_min' => $validatedData['suhu_min'],
                         'suhu_max' => $validatedData['suhu_max'],
                         'kelembapan' => $validatedData['kelembapan'],
                         'catatan' => $validatedData['catatan'], // Catatan masih bisa null
                    ]
               );


               // 3. Simpan data transaksi menggunakan data_periode_id yang baru dibuat/diperbarui
               // Karena jumlah_mati dan jumlah_afkir required, blok ini akan selalu dijalankan
               Deplesi::create([
                    'data_periode_id' => $dataPeriode->id,
                    'jumlah_mati' => $validatedData['jumlah_mati'],
                    'jumlah_afkir' => $validatedData['jumlah_afkir'],
                    'foto' => $validatedData['foto_deplesi'], // Foto bisa null
                    'keterangan' => $validatedData['keterangan'], // Mengoreksi nama variabel
                    'tanggal' => $dataPeriode->tanggal, // Ambil dari data_periode yang baru dibuat
               ]);

               // Karena pakan_keluar_jumlah dan pakan_keluar_jenis_id required, blok ini akan selalu dijalankan
               StokPakanKeluar::create([
                    'data_periode_id' => $dataPeriode->id,
                    'jumlah_keluar' => $validatedData['pakan_keluar_jumlah'],
                    'stok_pakan_id' => $validatedData['pakan_keluar_jenis_id'],
                    'tanggal' => $dataPeriode->tanggal, // Ambil dari data_periode yang baru dibuat
               ]);

               // Karena obat_keluar_jumlah dan stok_obat_id required, blok ini akan selalu dijalankan
               StokObatKeluar::create([
                    'data_periode_id' => $dataPeriode->id,
                    'jumlah_keluar' => $validatedData['obat_keluar_jumlah'],
                    'stok_obat_id' => $validatedData['stok_obat_id'], // Mengoreksi nama variabel
                    'tanggal' => $dataPeriode->tanggal, // Ambil dari data_periode yang baru dibuat
               ]);

               // Karena body_weight_hasil required, blok ini akan selalu dijalankan
               BodyWeight::create([
                    'data_periode_id' => $dataPeriode->id,
                    'body_weight_hasil' => $validatedData['body_weight_hasil'],
                    'tanggal' => $dataPeriode->tanggal, // Ambil dari data_periode yang baru dibuat
               ]);

               DB::commit();

               // Jika sampai sini, berarti tidak ada error dalam transaksi
               // Ini akan menampilkan semua query jika transaksi berhasil


               return back()->with('success', 'Data harian berhasil disimpan!');
          } catch (\Exception $e) {
               DB::rollBack();
               // FIX: Pindahkan pengambilan query log dan dd($e) ke sini
               $queryLog = DB::getQueryLog(); // Ambil query log sebelum error
               Log::error('Error saving daily record: ' . $e->getMessage(), [
                    'request_data' => $request->all(),
                    'exception_trace' => $e->getTraceAsString(),
                    'query_log_at_error' => $queryLog // Tambahkan query log ke log error
               ]);

               // Ini akan menampilkan error yang sebenarnya terjadi, lengkap dengan query
               dd([
                    'ERROR_OCCURRED' => true,
                    'Exception_Message' => $e->getMessage(),
                    'Exception_File' => $e->getFile(),
                    'Exception_Line' => $e->getLine(),
                    'SQL_Queries_Before_Error' => $queryLog,
                    'Full_Exception' => $e // Untuk melihat semua detail exception
               ]);

               return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
          }
     }
}
