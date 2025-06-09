@extends('developer.layouts.app')

@section('content')
<div class="mb-6">
     <h1 class="text-2xl font-bold text-gray-800">Dashboard Periode Selesai </h1>
     <nav class="mt-2 text-sm text-gray-500">
          <a href="{{ route('developer.dashboard.kandang', [
                'peternakan'=>$peternakan,
                'kandang'=>$kandang,
            ]) }}" class="hover:text-blue-600">Dashboard</a>
          <span class="mx-2">/</span>
          <a href="{{ route('developer.periode.index', [
                'peternakan'=>$peternakan,
                'kandang'=>$kandang,
            ]) }}" class="hover:text-blue-600">Periode</a>
          <span class="mx-2">/</span>
          <span class="text-yellow-700 font-semibold">{{ $periode->nama_periode }}</span>
     </nav>
</div>


<div class="bg-white rounded-xl shadow-md p-6 border border-green-200 mb-8">
     <div class="flex justify-between items-center">
          <div>
               <div class="flex items-center">
                    <h2 class="text-2xl font-bold text-green-800">{{ $periode->nama_periode }}</h2>
                    <span class="ml-3 px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full">
                         {{ $periode->aktif ? 'Aktif':'Selesai'}}
                    </span>
               </div>
               <div class="mt-4 flex grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                         <div class="flex items-center">
                              <div class="p-2 rounded-full bg-blue-100">
                                   <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                   </svg>
                              </div>
                              <div class="ml-3">
                                   <p class="text-sm text-gray-500">Tanggal Mulai</p>
                                   <p class="font-semibold">{{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d F Y') }}</p>
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
                                   <p class="font-semibold">Hari ke-{{ (int)(\Carbon\Carbon::parse($periode->tanggal_mulai)->diffInDays(now()) + 1) }}</p>
                              </div>
                         </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                         <div class="flex items-center">
                              <div class="p-2 rounded-full bg-purple-100">
                                   <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4C8 3.44772 8.44772 3 9 3H15C15.5523 3 16 3.44772 16 4V5C16 5.55228 15.5523 6 15 6H9C8.44772 6 8 5.55228 8 5V4Z M4 8C4 7.44772 4.44772 7 5 7H19C19.5523 7 20 7.44772 20 8V9C20 9.55228 19.5523 10 19 10H5C4.44772 10 4 9.55228 4 9V8Z M12 12C8.68629 12 6 14.6863 6 18C6 19.1046 6.89543 20 8 20H16C17.1046 20 18 19.1046 18 18C18 14.6863 15.3137 12 12 12Z" />
                                   </svg>
                              </div>
                              <div class="ml-3">
                                   <p class="text-sm text-gray-500">Total Ayam</p>
                                   <p class="font-semibold">{{ number_format($periode->jumlah_ayam) }} ekor</p>
                              </div>
                         </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                         <div class="flex items-center">
                              <div class="p-2 rounded-full bg-red-100">
                                   @php
                                   $standard = collect(config('data_standards'))->sortBy('age')->values();
                                   $currentAge = ($periode->data_periode_age) + 1;
                                   $currentStandard = $standard->where('age', $currentAge)->first();
                                   $deplesiStd = $currentStandard ? $currentStandard['deplesi_std_cum'] : 0;
                                   $fcrStd = $currentStandard? $currentStandard['fcr_std']: 0;
                                   @endphp
                                   <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                   </svg>
                              </div>
                              <div class="ml-3">
                                   <p class="text-sm text-gray-500">Tingkat Mortalitas</p>
                                   <p class="font-semibold">{{ number_format($deplesiStd * 1, 2) }}%</p>
                              </div>
                         </div>
                    </div>

                    @foreach ($stokPakan as $stok)
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 ">
                         <div class="flex items-center">
                              <div class="p-2 rounded-full bg-blue-100">
                                   <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                             d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                   </svg>
                              </div>
                              <div class="ml-3">
                                   <p class="text-sm text-gray-500">Pakan: {{ $stok->pakanJenis->kode ?? '-' }}</p>
                                   <p class="font-semibold">{{ $stok->jumlah_stok }} {{ $stok->pakanJenis->satuan ?? 'zak' }}</p>
                              </div>
                         </div>
                    </div>
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
                                   <p class="font-semibold">{{ $fcrStd }}</p>
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
          <h2 class="text-2xl text-center font-semibold text-blue-900 mb-5">Data Record Performa Ayam Broiler</h2>
          <div class="overflow-x-auto">
               <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                         <tr>
                              <!--TANGGAL -->
                              <th rowspan="2" colspan="1" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">TGL</th>
                              <!-- UMUR -->
                              <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">AGE</th>
                              <!-- DEPLESI -->
                              <th colspan="4" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">DEPLESI ACT</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50"> DEPLESI <br> STD</th>
                              <!-- PAKAN -->
                              <th colspan="4" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">PAKAN <br>STANDARD</th>
                              <th colspan="4 " class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">PAKAN <br>ACTUAL</th>
                              <th colspan="4 " class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">DELTA <br>PAKAN</th>
                              <!-- BODY WEIGHT -->
                              <th colspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">BODY WEIGHT <br>STANDARD</th>
                              <th colspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">BODY WEIGHT <br>ACTUAL</th>
                              <th colspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">DELTA <br>BODY WEIGHT</th>

                              <!-- FCR -->
                              <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">FCR <br>STD</th>
                              <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">FCR <br>ACT</th>
                              <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">DELTA <br>FCR</th>
                              <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-red-100">PREDIKSI <br>FCR</th>
                              <!-- IP -->
                              <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">IP <br> STD</th>
                              <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">IP <br> ACT</th>
                              <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">DELTA <br> IP</th>
                              <!-- SALDO AYAM -->
                              <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">SALDO <br> AYAM</th>
                              <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">Jumlah <br> Panen</th>
                              <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">Saldo ayam <br> dikandang</th>
                              <th rowspan="2" class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">Laporan<br> Harian</th>
                         </tr>
                         <tr>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">MATI</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">AFKIR</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">JML</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">CUM%</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">CUM%</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">ZAK</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">CUM <br>ZAK</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">GR/EK</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">CUM <br>GR/EK</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">ZAK</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">CUM <br>ZAK</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">GR/EK</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">CUM <br>GR/EK</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">ZAK</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">CUM <br>ZAK</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">GR/EK</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">CUM <br>GR/EK</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">ABW(gr)</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">DG(gr)</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">ABW(gr)</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">DG(gr)</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">ABW(gr)</th>
                              <th class="border border-gray-300 px-2 py-1 text-center text-xs bg-gray-50">DG(gr)</th>
                         </tr>

                    </thead>
                    <thead>

                    </thead>
                    <tbody>
                         @php
                         $standards = collect(config('data_standards'))->sortBy('age')->values();
                         $startDate = $periode ? \Carbon\Carbon::parse($periode->periode_tanggal) : now();
                         $dates = [];
                         for($i = 0; $i < 35; $i++) {
                              $dates[]=$startDate->copy()->addDays($i);
                              }
                              $currentWeek = 0;
                              $dayCount = 0;
                              @endphp

                              @if($standards && $standards->count() > 0)
                              @foreach($dates as $index => $date)
                              @php
                              $dayCount++;
                              if($dayCount % 7 == 1) {
                              $currentWeek++;
                              @endphp
                              <tr>
                                   <td colspan="36" class="border border-gray-300 px-2 py-3 text-center text-md bg-blue-50 font-semibold">
                                        Minggu ke-{{ $currentWeek }}
                                   </td>
                              </tr> @php
                              }
                              $standard = $standards->where('age', $dayCount)->first();
                              @endphp
                              <tr class="hover:bg-orange-100 hover:-translate-y-1 transition-transform duration-100">
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">{{ $date->format('d M') }}</td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">{{ $standard['age'] }}</td>
                                   <!-- DEPLESI ACTUAL -->
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <!-- DEPLESI STD -->
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">{{ number_format($standard['deplesi_std_cum'] * 1, 2) }}%</td>
                                   <!-- PAKAN STD -->
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">{{ $standard['pakan_std_zak'] }}</td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">{{ $standard['pakan_std_cum_zak'] }}</td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">{{ $standard['pakan_std_gr_ek'] }}</td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">{{ $standard['pakan_std_cum_gr_ek'] }}</td>
                                   <!-- PAKAN ACTUAL -->
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>

                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <!-- BODY WEIGHT STD -->
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">{{ $standard['bw_std_abw'] }}</td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">{{ $standard['bw_std_dg'] }}</td>
                                   <!-- BODY WEIGHT ACTUAL -->
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <!-- SELISIH BODY WEIGHT -->
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <!-- FCR -->
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">{{ number_format($standard['fcr_std'], 3) }}</td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs bg-red-100"></td>
                                   <!-- IP -->
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">{{ $standard['ip_std'] ?? '0' }}</td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <!-- SALDO -->
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">{{ number_format($periode->jumlah_ayam) }}</td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs"></td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">{{ number_format($periode->jumlah_ayam) }}</td>
                                   <td class="border border-gray-300 px-2 py-1 text-center text-xs">
                                        <button class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-medium rounded-lg shadow-sm hover:from-blue-600 hover:to-blue-700 hover:cursor-pointer">
                                             <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                             </svg>
                                             Lihat
                                        </button>
                                   </td>
                              </tr>
                              @endforeach

                              @else
                              <tr>
                                   <td colspan="26" class="text-center py-4">Data standar performa tidak tersedia</td>
                              </tr>
                              @endif
                    </tbody>
               </table>
          </div>
     </div>
</div>
@endsection