@extends('developer.layouts.app')

@section('content')
<div x-data="
{ showAlertModal: false, alertTitle: '' , alertMessage: '' , 
 showUpdateModal: false, modalTitle: '' , modalMessage: '', updateUrl: '',
 showDeleteModal: false, modalTitle: '', modalMessage: '', deleteUrl: '' }"
     class="p-8 max-w-7xl mx-auto">
     <div class="mb-6">
          <h1 class="text-2xl font-bold text-gray-800">Daftar Periode</h1>
          <nav class="mt-2" aria-label="Breadcrumb">
               <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="#" class="hover:text-blue-600">Dashboard</a></li>
                    <li>
                         <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                         </svg>
                    </li>
                    <li class="text-blue-600">Daftar Periode</li>
               </ol>
          </nav>
     </div>
     <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

          <div class="p-6">
               <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Daftar Periode</h2>
                    @if($periodeAktif)
                    <button
                         @click="
                                   showAlertModal = true;
                                   alertTitle = 'Tidak Bisa Membuat Periode Baru';
                                   alertMessage = 'Masih ada periode yang aktif. Silakan tutup periode aktif terlebih dahulu sebelum membuat periode baru.';"
                         type="button"
                         class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 hover:cursor-pointer transition-colors duration-200 flex items-center">
                         <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                         </svg>
                         Buat Periode Baru
                    </button>
                    @else
                    <a href="{{ route('developer.periode.create', ['peternakan' => $peternakan, 'kandang' => $kandang]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 hover:cursor-pointer transition-colors duration-200 flex items-center">
                         <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                         </svg>
                         Tambah Periode Baru
                    </a>
                    @endif
               </div>

               <div class="overflow-x-auto rounded-tl-xl rounded-tr-xl ">
                    <table class="min-w-full divide-y divide-gray-200">
                         <thead>
                              <tr class="bg-gray-50">
                                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Periode</th>
                                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Ayam</th>
                                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                              </tr>
                         </thead>
                         <tbody class="bg-white divide-y divide-gray-200">
                              @php
                              $activePeriode = $periodes->where('aktif', true)->first();
                              $inactivePeriodes = $periodes->where('aktif', false)->sortByDesc('tanggal_mulai');
                              @endphp

                              @if($activePeriode)
                              <tr class="hover:bg-gray-50 bg-green-50">
                                   <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $activePeriode->nama_periode }}</div>
                                   </td>
                                   <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ date('d M Y', strtotime($activePeriode->tanggal_mulai)) }}</div>
                                   </td>
                                   <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ number_format($activePeriode->jumlah_ayam) }} ekor</div>
                                   </td>
                                   <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                             Aktif
                                        </span>
                                   </td>
                                   <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                             <a href="{{ route('developer.dashboard.kandang', [
                                                       'peternakan' => $peternakan,
                                                       'kandang'    => $kandang,
                                                       'periode'    => $activePeriode,
                                                       ]) }}"
                                                  class="flex items-center px-6 py-3 transition-colors duration-200 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                  <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                                  </svg>
                                                  Masuk
                                             </a>
                                             <button @click="
                                                            showUpdateModal = true;
                                                            updateUrl = '{{ route('developer.periode.end', [$peternakan->slug, 'kandang' => $kandang->slug, 'periode' => $activePeriode->slug]) }}';
                                                            modalTitle = 'Konfirmasi Mengakhiri Periode'; 
                                                            modalMessage = 'Yakin ingin mengakhiri periode ini. Periode yang di nonaktifkan tidak dapat diubah kembali.';"
                                                  type="button"
                                                  class="hover:cursor-pointer inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                                                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                  </svg>
                                                  Akhiri Periode
                                             </button>
                                        </div>
                                   </td>
                              </tr>
                              @endif

                              @if($inactivePeriodes->count() > 0)
                              <tr>
                                   <td colspan="5" class="py-3">
                                        <hr class="border-t-2 border-gray-300">
                                        <div class="text-center text-sm font-medium text-gray-500 my-2">Periode Selesai</div>
                                        <hr class="border-t-2 border-gray-300">
                                   </td>
                              </tr>

                              @foreach($inactivePeriodes as $periode)
                              <tr class="hover:bg-gray-50">
                                   <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $periode->nama_periode }}</div>
                                   </td>
                                   <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ date('d M Y', strtotime($periode->tanggal_mulai)) }}</div>
                                   </td>
                                   <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ number_format($periode->jumlah_ayam) }} ekor</div>
                                   </td>
                                   <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                             Selesai
                                        </span>
                                   </td>
                                   <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                             <a href="{{ route('developer.kandang.show', [
                                                       'peternakan' => $peternakan,
                                                       'kandang'    => $kandang,
                                                       'periode'    => $periode,
                                                       ]) }}"
                                                  class="flex items-center px-6 py-3 bg-yellow-100 rounded-xl transition-colors duration-200 text-gray-600 hover:text-yellow-800 hover:bg-yellow-200">
                                                  <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                                  </svg>
                                                  Masuk
                                                  <span class="ml-auto text-xs">(Selesai)</span>
                                             </a>
                                             <button @click="
                                                            showDeleteModal = true; 
                                                            deleteUrl = '{{ route('developer.periode.destroy', [$peternakan->slug, $kandang->slug,  $periode->slug]) }}';
                                                            modalTitle = 'Konfirmasi Menghapus Periode';
                                                            modalMessage = 'Apakah Anda yakin ingin menghapus periode ini?';"
                                                  type="button"
                                                  class="hover:cursor-pointer inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                                                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16m-4-4v4H8V3" />
                                                  </svg>
                                                  Hapus
                                             </button>
                                        </div>
                                   </td>
                              </tr>
                              @endforeach
                              @endif

                              @if($periodes->isEmpty())
                              <tr>
                                   <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada periode yang tercatat
                                   </td>
                              </tr>
                              @endforelse
                         </tbody>
                    </table>
               </div>
          </div>
     </div>
     <x-alert-confirmation-modal />
     <x-update-confirmation-modal />
     <x-delete-confirmation-modal />
</div>

@endsection