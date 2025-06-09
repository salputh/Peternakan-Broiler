@extends('developer.layouts.app')

@section('content')
<div class="min-h-full flex items-center justify-center py-12">
     <div class="w-full max-w-md">
          <!-- Form Card -->
          <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
               <!-- Header -->
               <div class="px-8 py-6 bg-gradient-to-r from-blue-500 to-blue-600">
                    <div class="flex items-center">
                         <div class="flex-shrink-0">
                              <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-white/20">
                                   <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                   </svg>
                              </span>
                         </div>
                         <div class="ml-4">
                              <h2 class="text-xl font-bold text-white">Buat Periode Baru</h2>
                              <p class="mt-1 text-sm text-blue-100">Masukkan informasi periode pemeliharaan</p>
                         </div>
                    </div>
               </div>

               <!-- Alert Messages -->
               @if(session('success'))
               <div class="mx-6 mt-4">
                    <div class="flex items-center p-4 rounded-lg bg-green-50">
                         <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                         </svg>
                         <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
               </div>
               @endif

               <!-- Form -->
               <form action="{{ route('developer.periode.store', ['peternakan' => $peternakan, 'kandang' => $kandang]) }}" method="POST" class="p-8">
                    @csrf
                    <input type="hidden" name="kandang_id" value="{{ $kandang->kandang_id }}">

                    <div class="space-y-6">
                         <!-- Nama Periode -->
                         <div>
                              <label class="block text-sm font-medium text-gray-700 mb-2">
                                   Nama Periode
                              </label>
                              <input type="text" name="nama_periode"
                                   class="block w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring focus:ring-blue-100 focus:border-blue-500 transition-all duration-200"
                                   placeholder="Masukkan nama periode">
                         </div>

                         <!-- Tanggal Mulai -->
                         <div>

                              <label class="block text-sm font-medium text-gray-700 mb-2">
                                   Tanggal Mulai
                              </label>

                              <div class="relative">
                                   <input type="text" name="tanggal_mulai" id="tanggal_mulai"
                                        class="block w-full px-4 py-3 pl-10 rounded-xl border border-gray-300 focus:ring focus:ring-blue-100 focus:border-blue-500 transition-all duration-200"
                                        placeholder="Pilih tanggal mulai"
                                        readonly>
                                   <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                   </div>
                              </div>
                         </div>
                         <!-- Jumlah Ayam -->
                         <div>
                              <label class="block text-sm font-medium text-gray-700 mb-2">
                                   Jumlah Ayam
                              </label>
                              <div class="relative">
                                   <input type="text"
                                        name="jumlah_ayam"
                                        id="jumlah_ayam"
                                        min="1"
                                        max="{{ $kandang->kapasitas }}"
                                        class="block w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring focus:ring-blue-100 focus:border-blue-500 transition-all duration-200"
                                        placeholder="0">
                                   <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <span class="text-gray-500 mr-6">ekor</span>
                                   </div>
                              </div>
                              <p class="mt-1 text-sm text-gray-500">
                                   Maksimal kapasitas kandang: {{ number_format($kandang->kapasitas) }} ekor
                              </p>
                         </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex space-x-3">
                         <a href="{{ route('developer.dashboard.kandang', ['peternakan' => $peternakan, 'kandang' => $kandang])  }}"
                              class="flex-1 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors duration-200 text-center">
                              Batal
                         </a>

                         <button type="submit"
                              class="flex-1 px-4 py-3 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 hover:cursor-pointer transition-colors duration-200">
                              Buat Periode
                         </button>
                    </div>
               </form>
          </div>
     </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script>
     document.addEventListener('DOMContentLoaded', function() {
          flatpickr("#tanggal_mulai", {
               dateFormat: "d M Y",
               locale: "id",
               disableMobile: "true",
               allowInput: true,
               monthSelectorType: "static",
               position: "below"
          });
     });
</script>