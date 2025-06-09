@extends('developer.layouts.dev')

@section('content')
<div class="flex items-center justify-center py-5 px-4 sm:px-6 lg:px-8">
     <div class="max-w-2xl w-full">
          <!-- Header dengan efek gradient -->
          <div class="text-center">
               <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-900">
                    Edit Owner
               </h2>
          </div>

          <!-- Card Form -->
          <div class="">
               <div class=" p-8">
                    @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-800 bg-red-100 rounded-lg">
                         <ul class="list-disc pl-5">
                              @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                              @endforeach
                         </ul>
                    </div>
                    @endif

                    <form action="{{ route('developer.owner.update', $owner->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                         @csrf
                         @method('PUT')
                         <!-- Informasi Personal -->
                         <div class="border-b border-gray-100 pb-6">
                              <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Personal</h3>
                              <div class="grid grid-cols-1 gap-6">
                                   <div>
                                        <label for="name" class="block text-md font-medium text-gray-700">Nama Lengkap</label>
                                        <input type="text" name="name" id="name" required
                                             class="mt-2 block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition"
                                             value="{{ old('name', $owner->name) }}" placeholder="Masukkan nama lengkap">
                                        @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                   </div>
                                   <div>
                                        <label for="nama_peternakan" class="block text-md font-medium text-gray-700">Nama Peternakan</label>
                                        <input type="text" name="nama_peternakan" id="nama_peternakan" required
                                             class="mt-2 block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition"
                                             value="{{ old('nama_peternakan', $owner->peternakan->nama) }}" placeholder="Masukkan nama peternakan">
                                        @error('nama_peternakan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                   </div>

                                   <div>
                                        <label for="phone" class="block text-md font-medium text-gray-700">Nomor HP</label>
                                        <input type="text" name="phone" id="phone" required
                                             class="mt-2 block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition"
                                             value="{{ old('phone', $owner->phone) }}" placeholder="08xxxxxxxxxx">
                                        @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                   </div>

                                   <div>
                                        <label for="photo" class="block text-md font-medium text-gray-700">Foto Profil (Opsional)</label>
                                        @if($owner->photo)
                                        <div class="mt-3">
                                             <img src="{{ asset('storage/' . $owner->photo) }}" alt="Current photo" class="w-32 h-32 object-cover">
                                        </div>
                                        @endif
                                        <input type="file" name="photo" id="photo" accept="image/*"
                                             class="mt-4 block w-full px-4 py-2 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition hover:cursor-pointer ">
                                        @error('photo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                   </div>
                              </div>
                         </div>


                         <div class="flex items-center justify-end gap-5">
                              <a href="{{ route('developer.dashboard') }}"
                                   class="px-4 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                   Batal
                              </a>
                              <button type="submit"
                                   class="py-3 px-4 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition duration-200 hover:cursor-pointer">
                                   Simpan Perubahan
                              </button>
                         </div>
                    </form>
               </div>
          </div>
     </div>
</div>
@endsection