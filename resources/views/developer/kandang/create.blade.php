@extends('developer.layouts.dev')

@section('content')
<div class="py-12">
     <div class="mx-auto sm:px-6 lg:px-8">
          <div class="flex flex-col items-center">
               <div class="bg-white w-full max-w-xl rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-8 py-6">
                         <h2 class="text-2xl font-bold text-white text-center">{{$peternakan->nama}}</h2>
                         <p class="text-blue-100 text-center mt-1">Sistem Manajemen Peternakan</p>
                    </div>

                    <div class="p-8">
                         <div class="space-y-2 text-center mb-8">
                              <h3 class="text-xl font-semibold text-gray-800">Tambah Kandang Baru</h3>
                         </div>

                         @php
                         $peternakanId = request()->route('peternakan')
                         ?? session('active_peternakan_id')
                         ?? Auth::user()->peternakan_id;
                         @endphp
                         <form method="post" action="{{ isset($kandang) 
                              ? route('developer.kandang.update', ['peternakan' => $peternakan, 'kandang' => $kandang])
                              : route('developer.kandang.store', ['peternakan' => $peternakan])
                              }}" class=" space-y-6">
                              @csrf
                              @if (isset($kandang))
                              @method('PUT')
                              @endif
                              <div class="space-y-4">
                                   <div>
                                        <label for="nama_kandang" class="block text-sm font-medium text-gray-700 mb-1">
                                             Nama Kandang
                                        </label>
                                        <div class="relative">
                                             <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                  </svg>
                                             </span>
                                             <input type="text" id="nama_kandang" name="nama_kandang" required
                                                  class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                  value="{{ old('nama_kandang', $kandang->nama_kandang ?? '') }}">
                                        </div>
                                   </div>

                                   <div>
                                        <label for="kapasitas" class="block text-sm font-medium text-gray-700 mb-1">
                                             Kapasitas Kandang
                                        </label>
                                        <div class="relative">
                                             <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                  </svg>
                                             </span>
                                             <input type="number" id="kapasitas" name="kapasitas" required
                                                  class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                  value="{{ old('kapasitas', $kandang->kapasitas ?? '') }}">
                                        </div>
                                   </div>
                                   <div>
                                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                                             Lokasi Kandang
                                        </label>

                                        <div class="relative">
                                             <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                  </svg>
                                             </span>
                                             <input type="text" id="alamat" name="alamat" required
                                                  class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                  value="{{ old('alamat', $kandang->alamat ?? '') }}">
                                        </div>
                                   </div>
                              </div>

                              <div class="flex gap-8">
                                   <a href="{{ route('developer.kandang.index', ['peternakan' => $peternakan]) }}"
                                        class="flex-1 flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition">
                                        Batal
                                   </a>
                                   <button type="submit"
                                        class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 hover:cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition">
                                        Tambah Kandang
                                   </button>
                              </div>
                         </form>
                    </div>
               </div>
          </div>
     </div>
</div>
@endsection