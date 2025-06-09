@extends('developer.layouts.dev')

@section('content')
<div class="p-8 w-full">
     <div class="max-w-7xl mx-auto">
          <!-- Header Section dengan Statistik -->
          <div class="mb-8">
               <h1 class="text-3xl font-bold text-gray-800 mb-6">Manajemen Kandang {{ $peternakan->nama ?? 'Tidak Ditemukan' }}</h1>
               <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Kandang Card -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-blue-100 hover:shadow-md transition-shadow">
                         <div class="flex items-center">
                              <div class="p-3 rounded-full bg-blue-100">
                                   <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                   </svg>
                              </div>
                              <div class="ml-4">
                                   <p class="text-sm font-medium text-gray-500">Total Kandang</p>
                                   <p class="text-2xl font-semibold text-gray-900">{{ $kandangs->count() }}</p>
                              </div>
                         </div>
                    </div>

                    <!-- Kandang Aktif Card -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-green-100 hover:shadow-md transition-shadow">
                         <div class="flex items-center">
                              <div class="p-3 rounded-full bg-green-100">
                                   <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                   </svg>
                              </div>
                              <div class="ml-4">
                                   <p class="text-sm font-medium text-gray-500">Kandang Aktif</p>
                                   <p class="text-2xl font-semibold text-gray-900">{{ $kandangs->where('periode_aktif', true)->count() }}</p>
                              </div>
                         </div>
                    </div>

                    <!-- Total Kapasitas Card -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-purple-100 hover:shadow-md transition-shadow">
                         <div class="flex items-center">
                              <div class="p-3 rounded-full bg-purple-100">
                                   <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                   </svg>
                              </div>
                              <div class="ml-4">
                                   <p class="text-sm font-medium text-gray-500">Total Kapasitas</p>
                                   <p class="text-2xl font-semibold text-gray-900">{{ number_format($kandangs->sum('kapasitas')) }}</p>
                              </div>
                         </div>
                    </div>
               </div>
          </div>

          <!-- Tabel dan Tombol Tambah -->
          <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
               @if(session('success'))
               <div class="p-4 mb-4 bg-green-50 border-l-4 border-green-500">
                    <div class="flex items-center">
                         <div class="flex-shrink-0">
                              <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                              </svg>
                         </div>
                         <div class="ml-3">
                              <p class="text-sm text-green-700">{{ session('success') }}</p>
                         </div>
                    </div>
               </div>
               @endif

               @if(session('warning'))
               <div class="p-4 mb-4 bg-yellow-50 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                         <div class="flex-shrink-0">
                              <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                              </svg>
                         </div>
                         <div class="ml-3">
                              <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                         </div>
                    </div>
               </div>
               @endif

               @if(session('error'))
               <div class="p-4 mb-4 bg-red-50 border-l-4 border-red-500">
                    <div class="flex items-center">
                         <div class="flex-shrink-0">
                              <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                              </svg>
                         </div>
                         <div class="ml-3">
                              <p class="text-sm text-red-700">{{ session('error') }}</p>
                         </div>
                    </div>
               </div>
               @endif
               <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Daftar Kandang</h2>
                    <a href='{{ route('developer.kandang.create', ['peternakan' => $peternakan]) }}'
                         class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg transition-all">
                         <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                         </svg>
                         Tambah Kandang
                    </a>
               </div>



               <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                         <thead class="bg-gray-50">
                              <tr>
                                   <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kandang</th>
                                   <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                                   <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                   <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode Aktif</th>
                                   <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                   <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                              </tr>
                         </thead>
                         <tbody class="bg-white divide-y divide-gray-200">
                              @forelse($kandangs as $kandang)
                              <tr class="hover:bg-gray-50 transition-colors">
                                   <td class="px-6 py-4">
                                        <div class="flex items-center">
                                             <div class="flex-shrink-0 h-10 w-10">
                                                  <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                                       <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                       </svg>
                                                  </div>
                                             </div>
                                             <div class="ml-4">
                                                  <div class="text-sm font-medium text-gray-900">{{ $kandang->nama_kandang }}</div>
                                             </div>
                                        </div>
                                   </td>
                                   <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ number_format($kandang->kapasitas) }}</div>
                                        <div class="text-xs text-gray-500">ekor</div>
                                   </td>
                                   <td class="px-6 py-4">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $kandang->periode_aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-gray-800' }}">
                                             {{ $kandang->periode_aktif ? 'Aktif' : 'Off' }}
                                        </span>
                                   </td>
                                   <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                             {{ $kandang->nama_periode_aktif ?? 'Kosong' }}
                                        </div>
                                   </td>
                                   <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $kandang->alamat }}</div>
                                   </td>
                                   <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                             <a href="{{ route('developer.dashboard.kandang', [
                                             'peternakan' => $peternakan, 'kandang'=> $kandang]) }}"
                                                  class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition-colors">
                                                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                  </svg>
                                                  Masuk
                                             </a>
                                             <a href="{{ route('developer.kandang.edit', [
                                                  'peternakan' => $peternakan, 
                                                  'kandang' => $kandang
                                                  ]) }}"
                                                  class="inline-flex items-center px-3 py-1.5 bg-yellow-50 text-yellow-700 rounded-md hover:bg-yellow-100 transition-colors">
                                                  <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                  </svg>
                                                  Edit
                                             </a>
                                             <button @click="
                                                            showDeleteModal = true;
                                                            deleteUrl = '{{ route('developer.kandang.destroy', [
                                                            'peternakan' => $peternakan, 
                                                            'kandang' => $kandang,
                                                            ]) }}';
                                                            modalTitle = 'Konfirmasi Hapus Kandang';
                                                            modalMessage = 'Apakah Anda yakin ingin menghapus kandang ini? Semua data terkait kandang ini akan dihapus secara permanen.';
                                                      "
                                                  type="button"
                                                  class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 rounded-md hover:cursor-pointer hover:bg-red-100 transition-colors">
                                                  <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                  </svg>
                                                  Hapus
                                             </button>
                                        </div>
                                   </td>
                              </tr>
                              @empty
                              <tr>
                                   <td colspan="6" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center">
                                             <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                             </svg>
                                             <p class="mt-4 text-gray-500 text-lg">Belum ada kandang yang terdaftar</p>
                                        </div>
                                   </td>
                              </tr>
                              @endforelse
                         </tbody>
                    </table>
               </div>
          </div>
     </div>
</div>
@endsection