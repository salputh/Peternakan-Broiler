<div class="bg-gray-50 py-12">
     <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Form Card -->
          <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
               <!-- Header -->
               <div class="px-8 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                    <div class="flex items-center space-x-4">
                         <div class="flex-shrink-0">
                              <span class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-blue-100 bg-opacity-70">
                                   <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                   </svg>
                              </span>
                         </div>
                         <div>
                              <h2 class="text-xl font-semibold text-gray-900">Tambah Stok Obat</h2>
                              <p class="mt-1 text-sm text-gray-600">Masukkan informasi stok obat yang akan ditambahkan ke sistem</p>
                         </div>
                    </div>
               </div>

               <form method="POST" action="{{ route('developer.stok_obat.masuk.store', [
                'peternakan' => $peternakan,
                'kandang' => $kandang,
                'periode' => $periodeAktif->slug,
            ]) }}" class="p-8 space-y-6">
                    @csrf

                    <!-- Tanggal -->
                    <div class="space-y-2">
                         <label for="tanggal" class="block text-sm font-medium text-gray-700">
                              Tanggal Masuk
                         </label>
                         <div class="relative">
                              <input type="text" name="tanggal" id="tanggal"
                                   class="block w-full px-4 py-3 pl-11 text-gray-700 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition duration-150 ease-in-out"
                                   placeholder="Pilih tanggal masuk"
                                   readonly>
                              <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                   <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                   </svg>
                              </div>
                         </div>
                         @error('tanggal')
                         <p class="text-red-500 text-sm">{{ $message }}</p>
                         @enderror
                    </div>

                    <!-- Nama Obat Input -->
                    <div class="space-y-2">
                         <label for="nama_obat_input" class="block text-sm font-medium text-gray-700">
                              Nama Obat
                         </label>
                         <input type="text"
                              name="nama_obat_input"
                              id="nama_obat_input"
                              class="block w-full px-4 py-3 text-gray-700 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition duration-150 ease-in-out"
                              placeholder="Masukkan nama obat">
                         @error('nama_obat_input')
                         <p class="text-red-500 text-sm">{{ $message }}</p>
                         @enderror
                    </div>

                    <!-- Kategori Input -->
                    <div class="space-y-2">
                         <label for="kategori_input" class="block text-sm font-medium text-gray-700">
                              Kategori
                         </label>
                         <input type="text"
                              name="kategori_input"
                              id="kategori_input"
                              class="block w-full px-4 py-3 text-gray-700 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition duration-150 ease-in-out"
                              placeholder="Masukkan kategori obat">
                         @error('kategori_input')
                         <p class="text-red-500 text-sm">{{ $message }}</p>
                         @enderror
                    </div>

                    <!-- Jumlah Masuk -->
                    <div class="space-y-2">
                         <label for="jumlah_masuk" class="block text-sm font-medium text-gray-700">
                              Jumlah Masuk
                         </label>
                         <div class="relative">
                              <input type="number"
                                   name="jumlah_masuk"
                                   id="jumlah_masuk"
                                   class="block w-full px-4 py-3 text-gray-700 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition duration-150 ease-in-out"
                                   placeholder="0">
                              <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                   <span class="text-gray-500 text-sm mr-5">pcs</span>
                              </div>
                         </div>
                         @error('jumlah_masuk')
                         <p class="text-red-500 text-sm">{{ $message }}</p>
                         @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-6 border-t border-gray-100">
                         <div class="flex justify-end">
                              <button type="submit"
                                   class="px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                   Simpan Data
                              </button>
                         </div>
                    </div>
               </form>
          </div>
     </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script>
     document.addEventListener('DOMContentLoaded', function() {
          flatpickr("#tanggal", {
               dateFormat: "d M Y",
               locale: "id",
               disableMobile: "true",
               allowInput: true,
               monthSelectorType: "static",
               position: "below"
          });
     });
</script>