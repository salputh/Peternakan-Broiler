@extends('developer.layouts.app')

@section('content')
<div class="p-2 w-full max-w-fit mx-auto bg-blue-50">
     {{-- === 1. Periode aktif atau terpilih (aktif) === --}}
     @if($periode = $periodeAktif)
     <div class="mb-6">
          <div class="flex items-center space-x-6">
               <h1 class="text-4xl font-bold text-gray-800">Dashboard Periode Aktif</h1>
               <button
                    @click="
                         showUpdateModal = true;
                         updateUrl = '{{ route('developer.kandang.periode.end', ['peternakan' => $peternakan->slug, 'kandang' => $kandang->slug]) }}';
                         modalTitle = 'Konfirmasi Akhiri Periode';
                         modalMessage = 'Apakah Anda yakin ingin mengakhiri periode ini?';"
                    type="button"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 hover:cursor-pointer transition-colors">
                    Akhiri Periode
               </button>
          </div>
          <nav class="mt-2" aria-label="Breadcrumb">
               <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li>
                         <a href="{{ route('developer.dashboard.kandang', ['peternakan' => $peternakan, 'kandang' => $kandang]) }}" class="hover:text-blue-600">Dashboard</a>
                    </li>
                    <li>
                         <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                         </svg>
                    </li>
                    <li class="text-blue-600">Periode Aktif</li>
               </ol>
          </nav>
     </div>

     <!-- Banner Periode Aktif -->
     <div class="bg-white rounded-xl shadow-md p-6 border border-green-200 mb-8">
          <div class="flex justify-between items-center">
               <div>
                    <div class="flex items-center justify-between">
                         <div class="flex items-center">
                              <h2 class="text-2xl font-bold text-green-800">{{ $periodeAktif->nama_periode }}</h2>
                              <span class="ml-3 px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full">
                                   {{ $periodeAktif->aktif ? 'Aktif':'Selesai'}}
                              </span>
                         </div>
                         <div class="flex gap-5 ml-5">

                         </div>
                    </div>
                    <div class="mt-4 grid grid-cols-10 gap-4">
                         <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                              <div class="flex items-center">
                                   <div class="p-2 rounded-full bg-blue-100">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                   </div>
                                   <div class="ml-3">
                                        <p class="text-sm text-gray-500">Tanggal Mulai</p>
                                        <p class="font-semibold">{{ \Carbon\Carbon::parse($periodeAktif->tanggal_mulai)->locale('id')->isoFormat('D MMMM Y') }}</p>
                                   </div>
                              </div>
                         </div>

                         <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                              <div class="flex items-center">
                                   <div class="p-2 rounded-full bg-green-100">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                   </div>
                                   <div class="ml-3">
                                        <p class="text-sm text-gray-500">Usia</p>
                                        <p class="font-semibold">Hari ke-{{ (int)(\Carbon\Carbon::parse($periodeAktif->tanggal_mulai)->diffInDays(now()) + 1) }}</p>
                                   </div>
                              </div>
                         </div>

                         <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                              <div class="flex items-center">
                                   <div class="p-2 rounded-full bg-purple-100">
                                        <x-emoji-chicken class="w-5 h-5" />
                                   </div>
                                   <div class="ml-3">
                                        <p class="text-sm text-gray-500">Total Ayam</p>
                                        <p class="font-semibold">{{ number_format($periodeAktif->jumlah_ayam) }} ekor</p>
                                   </div>
                              </div>
                         </div>

                         <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                              <div class="flex items-center">

                                   <div class="p-2 rounded-full bg-red-100">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                        </svg>
                                   </div>

                                   <div class="ml-3">

                                        <p class="text-sm text-gray-500">Mortalitas</p>
                                        <p class="font-semibold">{{ $standardHariIni->deplesi_std_cum ?? 0}}%</p>

                                   </div>

                              </div>
                         </div>

                         @foreach ($stokPakan as $stok)
                         @if($stok->jumlah_stok > 0)
                         <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 ">
                              <div class="flex items-center">
                                   <div class="p-2 rounded-full bg-blue-100">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                   </div>
                                   <div class="ml-3">
                                        @php
                                        $badgeColors = [
                                        'SB10' => 'purple',
                                        'SB11' => 'red',
                                        'SB12' => 'green'
                                        ];
                                        $color = $badgeColors[$stok->pakanJenis->kode] ?? 'gray';

                                        // Calculate available stock
                                        $totalMasuk = $stok->stokPakanMasuk->sum('jumlah_masuk');
                                        $totalKeluar = $stok->stokPakanKeluar->sum('jumlah_keluar');
                                        $stokTersedia = $totalMasuk - $totalKeluar;
                                        @endphp
                                        <div class="flex items-center space-x-2">
                                             <p class="text-sm">Stok</p>
                                             <span class="inline-flex items-center rounded-md bg-{{ $color }}-100 px-2 py-1 text-xs font-medium text-{{ $color }}-700 ring-1 ring-{{ $color }}-700/10 ring-inset">{{ $stok->pakanJenis->kode }}</span>
                                        </div>
                                        <p class="font-semibold">{{ $stokTersedia }} {{ $stok->pakanJenis->satuan ?? 'zak' }}</p>
                                   </div>
                              </div>
                         </div>
                         @endif
                         @endforeach

                         @foreach ($stokObat as $stok)
                         @if($stok->jumlah_tersedia > 0)
                         <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 ">
                              <div class=" w-full max-w-2 flex items-center">
                                   <div class="p-2 rounded-full bg-blue-100">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                   </div>
                                   <div class="ml-3">
                                        <p class="text-sm text-gray-500 font-semibold">{{ $stok->nama_obat ?? '-' }}</p>
                                        <p class="font-semibold">{{ $stok->jumlah_tersedia }} {{ $stok->satuan ?? 'pcs' }}</p>
                                   </div>
                              </div>
                         </div>
                         @endif
                         @endforeach

                         <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 ">
                              <div class="flex items-center">
                                   <div class="p-2 rounded-full bg-yellow-100">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                   </div>
                                   <div class="ml-3">
                                        <p class="text-sm text-gray-500">FCR</p>
                                        <p class="font-semibold">{{ $standardHariIni->fcr_std ?? 0}}</p>
                                   </div>
                              </div>
                         </div>

                         <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 ">
                              <div class="flex items-center">

                                   <div class="p-2 rounded-full bg-yellow-100">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                   </div>

                                   <div class="ml-3">
                                        <p class="text-sm text-gray-500">PREDIKSI FCR</p>
                                        <p class="font-semibold">{{ $standardHariIni->fcr_std ?? 0}}</p>
                                   </div>

                              </div>
                         </div>

                         <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 ">
                              <div class="flex items-center">

                                   <div class="p-2 rounded-full bg-yellow-100">
                                        <x-css-performance class="w-5 h-5" />
                                   </div>

                                   <div class="ml-3">
                                        <p class="text-sm text-gray-500">IP</p>
                                        <p class="font-semibold">{{ $standardHariIni->ip_std ?? 0 }}</p>
                                   </div>

                              </div>
                         </div>
                    </div>
               </div>

          </div>
     </div>

     <!-- Konten Dashboard untuk Periode Aktif -->
     <div class="space-y-8">
          <!-- Grafik dan Aktivitas -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
               <!-- Grafik Pertumbuhan -->
               <div class="bg-white rounded-xl shadow-md p-6 border border-blue-100">
                    <h2 class="text-xl font-semibold text-blue-900 mb-4">Grafik Pertumbuhan</h2>
                    <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                         <p class="text-gray-500">Grafik akan ditampilkan di sini</p>
                    </div>
               </div>

               <!-- Aktivitas Terbaru -->
               <div class="bg-white rounded-xl shadow-md p-6 border border-blue-100">
                    <h2 class="text-xl font-semibold text-blue-900 mb-4">Aktivitas Terbaru</h2>
                    <div class="space-y-4">
                         <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                              <div class="flex-shrink-0">
                                   <div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                   </div>
                              </div>
                              <div class="ml-4">
                                   <p class="text-sm font-medium text-blue-900">Pemberian Pakan</p>
                                   <p class="text-sm text-blue-600">50 kg pakan diberikan</p>
                                   <p class="text-xs text-blue-400">2 jam yang lalu</p>
                              </div>
                         </div>

                         <div class="flex items-center p-4 bg-red-50 rounded-lg">
                              <div class="flex-shrink-0">
                                   <div class="w-10 h-10 rounded-full bg-red-200 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                   </div>
                              </div>
                              <div class="ml-4">
                                   <p class="text-sm font-medium text-red-900">Mortalitas</p>
                                   <p class="text-sm text-red-600">2 ekor mortalitas tercatat</p>
                                   <p class="text-xs text-red-400">5 jam yang lalu</p>
                              </div>
                         </div>
                    </div>
               </div>
          </div>

          <!-- Tabel Standar Performa -->
          <div class="bg-white rounded-xl shadow-md p-6 border border-blue-100">
               <div class="flex justify-between">
                    <h2 class="text-2xl text-center font-semibold text-blue-900 mb-5">Data Record Performa Ayam Broiler</h2>
                    <div class="flex justify-end mb-4 space-x-4">
                         <button
                              @click="
                                        showDailyRecordModal = true;"
                              type="button"
                              class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 hover:cursor-pointer">
                              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                              </svg>
                              Catat Performa Harian
                         </button>
                    </div>
               </div>
               <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-700">
                         <thead>
                              <tr>
                                   <!--TANGGAL -->
                                   <th rowspan="2" colspan="1" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">TGL</th>
                                   <!-- UMUR -->
                                   <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">AGE</th>
                                   <!-- DEPLESI -->
                                   <th colspan="4" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">DEPLESI ACT</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-amber-100"> DEPLESI <br> STD</th>
                                   <!-- PAKAN -->
                                   <th colspan="4" class="border border-gray-300 px-2 py-1 text-center text-xs bg-sky-100">PAKAN <br>STANDARD</th>
                                   <th colspan="5" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">PAKAN <br>ACTUAL</th>
                                   <th colspan="4" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">DELTA <br>PAKAN</th>

                                   <!-- BODY WEIGHT -->
                                   <th colspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-green-100">BODY WEIGHT <br>STANDARD</th>
                                   <th colspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">BODY WEIGHT <br>ACTUAL</th>
                                   <th colspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">DELTA <br>BODY WEIGHT</th>

                                   <!-- OBAT & VITAMIN -->
                                   <th colspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">OBAT <br>& VIT</th>

                                   <!-- LINGKUNGAN -->
                                   <th colspan="3" class="border border-gray-300 px-2 py-1 text-center text-xs bg-zinc-100">LINGKUNGAN <br> STD</th>

                                   <!-- LINGKUNGAN -->
                                   <th colspan="3" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">LINGKUNGAN <br> ACT</th>


                                   <!-- FCR -->
                                   <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-yellow-100">FCR<br>STD</th>
                                   <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">FCR<br>ACT</th>
                                   <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">
                                        <span class="flex flex-col items-center justify-center">
                                             <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                  <path d="M12 4L20 18H4L12 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                             </svg>
                                             FCR
                                        </span>
                                   </th>
                                   <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-red-100">PRED<br>FCR</th>

                                   <!-- IP -->
                                   <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-pink-200">IP <br> STD</th>
                                   <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">IP <br> ACT</th>
                                   <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">
                                        <span class="flex flex-col items-center justify-center">
                                             <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                  <path d="M12 4L20 18H4L12 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                             </svg>
                                             IP
                                        </span>
                                   </th>
                                   <!-- SALDO AYAM -->
                                   <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">SALDO <br> AYAM</th>
                                   <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">JLH <br> PANEN</th>
                                   <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">SISA <br>AYAM</th>
                              </tr>
                              <tr>
                                   <!-- DEPLESI -->
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">MATI</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">AFKIR</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">JML</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">CUM%</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-amber-100">CUM%</th>
                                   <!-- STANDARD PAKAN -->
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-sky-100">ZAK</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-sky-100">CUM <br>ZAK</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-sky-100">GR/<br>EK</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-sky-100">CUM <br>GR/EK</th>
                                   <!-- ACTUAL PAKAN -->
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">ZAK</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">JENIS</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">CUM <br>ZAK</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">GR/<br>EK</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">CUM <br>GR/EK</th>

                                   <!-- SELISIH PAKAN -->
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">ZAK</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">CUM <br>ZAK</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">GR/<br>EK</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">CUM <br>GR/EK</th>

                                   <!-- STANDARD BODY WEIGHT -->
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-green-100">ABW<br>(gr)</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-green-100">DG<br>(gr)</th>
                                   <!-- ACTUAL BODY WEIGHT -->
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">ABW<br>(gr)</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">DG<br>(gr)</th>
                                   <!-- DELTA BODY WEIGHT -->
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">ABW<br>(gr)</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">DG<br>(gr)</th>

                                   <!-- OBAT & VITAMIN -->
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">JENIS</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">JLH</th>

                                   <!-- STANDARD LINGKUNGAN -->
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-zinc-100">SUHU<br>MIN</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-zinc-100">SUHU<br>MAX</th>
                                   <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-zinc-100">%<br>HUM</th>

                                   <!-- ACTUAL LINGKUNGAN -->
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">SUHU<br>MIN</th>
                                   <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">SUHU<br>MAX</th>
                                   <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">%<br>HUM</th>
                              </tr>

                         </thead>
                         <thead>

                         </thead>
                         <tbody>
                              @php
                              // Menggunakan tanggal_mulai dari $periodeAktif.
                              // Jika $periodeAktif null, ini akan default ke now() (walaupun loop tidak akan jalan karena if di bawah)
                              $startDate = $periodeAktif ? \Carbon\Carbon::parse($periodeAktif->tanggal_mulai) : now(); //
                              $dates = [];
                              for($i = 0; $i < 35; $i++) {
                                   $dates[]=$startDate->copy()->addDays($i);
                                   }
                                   $currentWeek = 0;
                                   $dayCount = 0;
                                   @endphp

                                   @if($standardPerforma && $standardPerforma->count() > 0)
                                   @foreach($dates as $index => $date)
                                   @php
                                   $dayCount++;
                                   if($dayCount % 7 == 1) {
                                   $currentWeek++;
                                   @endphp
                                   <tr>
                                        <td colspan="44" class="border border-gray-300 px-2 py-3 text-center text-md bg-blue-50 font-semibold">
                                             Minggu ke-{{ $currentWeek }}
                                        </td>
                                   </tr>
                                   @php
                                   }
                                   // PERBAIKAN DI SINI: Menggunakan $standardPerforma (camelCase)
                                   $standard = $standardPerforma->where('age', $dayCount)->first(); //
                                   // PERBAIKAN DI SINI: Menggunakan $actualPerforma (camelCase)
                                   @endphp
                                   <tr class="bg-white hover:bg-orange-100! transition duration-300 ">
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs">{{ $date->format('d M') }}</td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs">{{ $standard['age'] }}</td>
                                        <!-- DEPLESI ACTUAL -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <!-- DEPLESI STD -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs bg-amber-100">{{ number_format($standard['deplesi_std_cum'] * 1 , 2) }}%</td>

                                        <!-- PAKAN STD -->
                                        <td class="bg-sky-100 border border-gray-300 px-2 py-2.5 text-center text-xs ">{{ $standard['pakan_std_zak'] }}</td>
                                        <td class="bg-sky-100 border border-gray-300 px-2 py-2.5 text-center text-xs ">{{ $standard['pakan_std_cum_zak'] }}</td>
                                        <td class="bg-sky-100 border border-gray-300 px-2 py-2.5 text-center text-xs ">{{ $standard['pakan_std_gr_ek'] }}</td>
                                        <td class="bg-sky-100 border border-gray-300 px-2 py-2.5 text-center text-xs ">{{ $standard['pakan_std_cum_gr_ek'] }}</td>
                                        <!-- PAKAN ACTUAL -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center font-semibold text-xs"></td>
                                        <!-- DELTA PAKAN -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <!-- BODY WEIGHT STD -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs bg-green-100">{{ $standard['bw_std_abw'] }}</td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs bg-green-100">{{ $standard['bw_std_dg'] }}</td>
                                        <!-- BODY WEIGHT ACTUAL -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>

                                        <!-- SELISIH BODY WEIGHT -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <!-- OBAT & VIT -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>

                                        <!-- MIN TEMP -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs bg-zinc-100">{{$standard['std_suhu_min']}}</td>
                                        <!-- MAX TEMP -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs bg-zinc-100">{{$standard['std_suhu_max']}}</td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs bg-zinc-100">{{$standard['std_kelembapan']}}</td>
                                        <!-- MIN TEMP -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <!-- MAX TEMP -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>

                                        <!-- FCR -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs bg-yellow-100">{{ number_format($standard['fcr_std'], 3) }}</td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs bg-red-100"></td>

                                        <!-- IP -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs bg-pink-200">{{ $standard['ip_std'] ?? '0' }}</td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>

                                        <!-- SALDO -->
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs">{{ number_format($periodeAktif->jumlah_ayam) }}</td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs"></td>
                                        <td class="border border-gray-300 px-2 py-2.5 text-center text-xs">{{ number_format($periodeAktif->jumlah_ayam) }}</td>
                                   </tr>
                                   @endforeach

                                   @else
                                   <tr>
                                        <td colspan="44" class="border border-gray-300 px-2 py-2.5 text-center text-xl">Data standar performa tidak tersedia</td>
                                   </tr>
                                   @endif
                         </tbody>
                    </table>
               </div>
          </div>
     </div>

     <x-catat-performa-harian-modal :periodeAktif=" $periodeAktif" :pakanJenisOptions="$pakanJenisOptions" :stokObatOptions="$stokObatOptions" :standardHariIni="$standardHariIni" />

     @elseif(is_null($periode))
     <!-- Empty period warning display -->
     <div class="min-h-[80vh] flex items-center justify-center">
          <div class="text-center space-y-8 max-w-2xl mx-auto p-8">
               <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-full w-32 h-32 mx-auto flex items-center justify-center shadow-inner">
                    <svg class="h-16 w-16 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
               </div>

               <div class="space-y-4">
                    <h2 class="text-3xl font-bold text-gray-800">Tidak Ada Periode Aktif</h2>
                    <p class="text-gray-600 text-lg">Belum ada periode pemeliharaan yang aktif untuk kandang ini. Mulai periode baru untuk memulai pencatatan data.</p>
               </div>

               <a href="{{ route('developer.periode.create', ['peternakan' => $peternakan->slug, 'kandang' => $kandang->slug]) }}" class="group hover:cursor-pointer relative inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <span class="mr-3">Mulai Periode Baru</span>
                    <svg class="w-6 h-6 transition-transform duration-200 transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
               </a>
          </div>
     </div>
     @endif
</div>
@endsection