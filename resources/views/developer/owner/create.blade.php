@extends('developer.layouts.dev')

@section('content')
<div class="flex flex-col items-center justify-center py-8">
     <div class="text-center mb-4 mt-5">
          <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-900">
               Tambah Owner Baru
          </h2>
     </div>

     <div class="w-full">
          <hr class="h-px my-4 bg-blue-500 border-0">
     </div>
     <!-- Card Form -->
     <div class="max-w-5xl w-full">
          <div class="overflow-hidden">
               <div class="p-8">
                    @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-800 bg-red-100 rounded-lg">
                         <ul class="list-disc pl-5">
                              @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                              @endforeach
                         </ul>
                    </div>
                    @endif
                    <form action="{{ route('developer.owner.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                         @csrf
                         <div class="border-b border-gray-100 pb-6">
                              <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Personal</h3>
                              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                   <div class="col-span-1">
                                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                        <input type="text" name="name" id="name" required
                                             class="mt-2 block w-full px-4 py-2 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition"
                                             value="{{ old('name') }}" placeholder="Masukkan nama lengkap">
                                        @error('name')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                   </div>

                                   <div class="col-span-1">
                                        <label for="nama_peternakan" class="block text-sm font-medium text-gray-700">Nama Peternakan</label>
                                        <input type="text" name="nama_peternakan" id="nama_peternakan" required
                                             class="mt-2 block w-full px-4 py-2 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition"
                                             value="{{ old('nama_peternakan') }}" placeholder="Masukkan nama peternakan">
                                        @error('nama_peternakan')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                   </div>

                                   <div class="col-span-1">
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="email" id="email" required
                                             class="mt-2 block w-full px-4 py-2 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition"
                                             value="{{ old('email') }}" placeholder="contoh@email.com">
                                        @error('email')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                   </div>

                                   <div class="col-span-1">
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor HP</label>
                                        <input type="text" name="phone" id="phone" required
                                             class="mt-1 block w-full px-4 py-2 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition"
                                             value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                                        @error('phone')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                   </div>

                                   <div class="col-span-1 md:col-span-2">
                                        <label for="photo" class="block text-sm font-medium text-gray-700">Foto Profil (Opsional)</label>
                                        <input type="file" name="photo" id="photo" accept="image/*"
                                             class="mt-2 block w-full px-4 py-2 border hover:bg-gray-50 border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition hover:cursor-pointer">
                                        @error('photo')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                   </div>
                              </div>
                         </div>
                         <div class="border-b border-gray-100 pb-6">
                              <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Akun</h3>
                              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                   <div class="col-span-1">
                                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                        <input type="password" name="password" id="password" required
                                             class="mt-2 block w-full px-4 py-2 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition"
                                             placeholder="Minimal 6 karakter">
                                        @error('password')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                   </div>
                                   <div class="col-span-1">
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required
                                             class="mt-2 block w-full px-4 py-2 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition"
                                             placeholder="Masukkan ulang password">
                                   </div>
                              </div>
                         </div>
                         <div class="flex gap-5 justify-end">
                              <a href="{{ route('developer.dashboard') }}"
                                   class="px-4 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                   Batal
                              </a>
                              <button type="submit"
                                   class="py-3 px-4 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 hover:cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                   Tambah Owner Baru
                              </button>
                         </div>
                    </form>
               </div>
          </div>
     </div>
</div>
@endsection