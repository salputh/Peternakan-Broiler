<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{ $peternakan->nama_peternakan }} | {{ $kandang->nama_kandang }}</title>
      @vite('resources/css/app.css')
      <script src="//unpkg.com/alpinejs" defer></script>
      <style>
            [x-cloak] {
                  display: none !important;
            }
      </style>


</head>

<body x-data="{ showDeleteModal: false, deleteUrl: '', modalTitle: '', modalMessage: '', tab: '', showUpdateModal: false, updateUrl: '', modalTitle:'', modalMessage:'', showDailyRecordModal: false }" class="bg-blue-50">
      <div class="flex">
            <!-- Sidebar -->
            <div class="w-64 h-screen bg-white shadow-xl fixed">

                  <div class="p-6 border-b border-blue-100">
                        <h2 class="text-2xl font-bold text-blue-900">
                              {{ optional($peternakan)->nama ?? 'Peternakan Tidak Ditemukan' }}
                        </h2>
                        <div class="mt-4">
                              <div class="bg-blue-50 p-3 rounded-lg shadow-sm space-y-4">
                                    <div>
                                          <p class="text-blue-700 font-medium text-xs uppercase tracking-wide mb-1">Kandang</p>
                                          <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-sm font-medium text-blue-700 ring-1 ring-blue-700/10 ring-inset">{{ optional($kandang)->nama_kandang ?? 'Kandang Tidak Ditemukan' }}</span>
                                    </div>
                                    <div class="border-t border-blue-100 pt-4">
                                          <p class="text-blue-700 font-medium text-xs uppercase tracking-wide mb-1">Lokasi</p>
                                          <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-sm font-medium text-blue-700 ring-1 ring-blue-700/10 ring-inset">{{ optional($kandang)->alamat ?? 'Lokasi Tidak Tersedia' }}</span>
                                    </div>
                              </div>
                        </div>

                  </div>


                  <nav class="mt-6">
                        <div class="px-6 py-2">
                              <h3 class="text-xs uppercase text-blue-400 font-semibold tracking-wider">Menu Utama</h3>
                        </div>

                        <a href="{{ route('developer.dashboard.kandang', ['peternakan' => $peternakan, 'kandang' => $kandang]) }}"
                              class="flex items-center px-6 py-3 hover:text-blue-800
                              {{ request()->routeIs('developer.dashboard.kandang') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-500' : 'text-gray-600' }}
                               hover:bg-blue-50 ">
                              <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                              </svg>
                              Dashboard
                        </a>

                        <!-- Menu Periode -->

                        @if(isset($periodeAktif) && $periodeAktif)
                        <div class="mx-4 my-6 overflow-hidden rounded-lg border-l-4 {{ $periodeAktif->aktif 
                                    ? 'bg-green-50/80 border-l-green-500' 
                                    : 'bg-yellow-50/80 border-l-yellow-500' }}">
                              <div class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                          <!-- Icon untuk Periode -->
                                          <div class="p-2 rounded-full {{ $periodeAktif->aktif ? 'bg-green-100' : 'bg-yellow-100' }}">
                                                <svg class="h-5 w-5 {{ $periodeAktif->aktif ? 'text-green-600' : 'text-yellow-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                          </div>

                                          <span class="font-semibold tracking-wide {{ $periodeAktif->aktif ? 'text-green-700' : 'text-yellow-700' }}">
                                                {{ $periodeAktif->aktif ? 'Periode Aktif' : 'Periode Selesai' }}
                                          </span>
                                    </div>

                                    <div class="mt-2 space-y-3">
                                          <!-- Nama Periode dengan Badge -->
                                          <div class="inline-flex items-center space-x-2">
                                                <div class="flex items-center px-4 py-2 bg-white/90 rounded-full shadow-sm backdrop-blur-sm">
                                                      <svg class="h-4 w-4 mr-2 {{ $periodeAktif->aktif ? 'text-green-500' : 'text-yellow-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                      </svg>
                                                      <span class="text-sm font-medium {{ $periodeAktif->aktif ? 'text-green-600' : 'text-yellow-600' }}">
                                                            {{ $periodeAktif->nama_periode }}
                                                      </span>
                                                </div>
                                          </div>

                                          @if($periodeAktif->aktif)
                                          <!-- Info Usia dengan Status Dot -->
                                          <div class="flex items-center space-x-3">
                                                <div class="flex items-center space-x-2">
                                                      <div class="relative">
                                                            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                                            <div class="absolute -inset-1 bg-green-500/20 rounded-full animate-ping"></div>
                                                      </div>
                                                      <div class="flex items-center space-x-2">
                                                            <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <span class="text-sm font-medium text-green-700">
                                                                  Usia ke-{{ (int)\Carbon\Carbon::parse($periodeAktif->tanggal_mulai)->diffInDays(now()) + 1 }} hari
                                                            </span>
                                                      </div>
                                                </div>
                                          </div>
                                          @endif
                                    </div>
                              </div>
                        </div>
                        @endif


                        <!-- Menu Manajemen Periode Aktif -->
                        <div class="mt-2">
                              @if(isset($periodeAktif) && $periodeAktif)
                              <a href="{{ route('developer.stok_pakan.index', 
                                    [     
                                    'peternakan' => $peternakan,
                                    'kandang'    => $kandang,
                                    'periode'    => $periodeAktif->slug,
                                    ]) }}"
                                    class="flex items-center px-6 py-3 hover:bg-blue-50 hover:text-blue-800 transition-colors duration-200
                                    {{ request()->routeIs('developer.stok_pakan.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-500' : 'text-gray-600' }}">
                                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    Stok Pakan {{ $periodeAktif->aktif ? '' : '(Selesai)' }}
                              </a>

                              <a href="{{ route('developer.stok_obat.index', 
                                    [     
                                    'peternakan' => $peternakan,
                                    'kandang'    => $kandang,
                                    'periode'    => $periodeAktif->slug
                                    ]) }}"
                                    class="flex items-center px-6 py-3 hover:bg-blue-50 hover:text-blue-800 transition-colors duration-200
                                    {{ request()->routeIs('developer.stok_obat.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-500' : 'text-gray-600' }}">
                                    <x-ri-medicine-bottle-line class="w-5 h-5 mr-3" />
                                    Stok Obat {{ $periodeAktif->aktif ? '' : '(Selesai)' }}
                              </a>

                              <a href="#"
                                    class="flex items-center px-6 py-3 hover:bg-blue-50 hover:text-blue-800 transition-colors duration-200
                                    ">
                                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Catatan Panen
                              </a>


                              @else
                              <a class="flex items-center px-6 py-3 text-gray-400 cursor-not-allowed">
                                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    Stok Pakan (Belum tersedia)
                              </a>

                              <a class="flex items-center px-6 py-3 text-gray-400 cursor-not-allowed">
                                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    Stok Obat (Belum tersedia)
                              </a>

                              <a class="flex items-center px-6 py-3 text-gray-400 cursor-not-allowed">
                                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Catatan Panen (Belum tersedia)
                              </a>
                              @endif

                        </div>

                        <!-- Menu Periode -->
                        <div x-data="{ periodeAktif: 'true' }} }">
                              <a href="{{ route('developer.periode.index' , [
                              'peternakan' => $peternakan,
                              'kandang' => $kandang,
                              ]) }}"
                                    class="flex items-center px-6 py-3 hover:bg-blue-50 hover:text-blue-800 transition-colors duration-200
                                    {{ request()->routeIs('developer.periode.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-500' : 'text-gray-600' }}">
                                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="flex-1">Periode</span>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                          {{ $totalPeriode ?? 0 }}
                                    </span>
                              </a>
                        </div>

                        <a href="{{ route('developer.kandang.index', ['peternakan' => $peternakan, 'kandang' => $kandang]) }}" class="flex items-center px-6 py-3 text-red-600 hover:bg-red-50 hover:text-red-800 transition-colors duration-200">
                              <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                              </svg>
                              Keluar Kandang
                        </a>

                        <form method="POST" action="{{ auth()->check() ? route('developer.logout') : route('logout') }}" class="mt-2">
                              @csrf
                              <button type="submit" class="flex items-center px-6 py-3 w-full text-left text-red-600 hover:bg-red-50 hover:text-red-800 hover:cursor-pointer transition-colors duration-200">
                                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Keluar
                              </button>
                        </form>
                  </nav>
            </div>

            <!-- Main Content -->
            <div class="ml-64 p-8 w-full min-h-screen bg-blue-50">
                  @yield('content')
            </div>
      </div>
      <x-delete-confirmation-modal />
      <x-update-confirmation-modal />
</body>

</html>