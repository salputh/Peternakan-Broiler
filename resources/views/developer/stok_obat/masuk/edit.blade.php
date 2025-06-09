<div class="p-8 max-w-7xl mx-auto">
     <!-- Form Card -->
     <div class="max-w-3xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200 mb-7">
          <div class="px-6 py-4 border-b border-gray-200">
               <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                         <span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-50">
                              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                              </svg>
                         </span>
                    </div>

                    <div>
                         <h2 class="text-lg font-medium text-gray-900">Ubah Stok Obat</h2>
                         <p class="mt-1 text-sm text-gray-500">Masukkan informasi stok obat yang akan diubah ke sistem</p>
                    </div>

               </div>
          </div>

          @if(session('error'))
          <div class="p-4 m-6 bg-red-50 border-l-4 border-red-500">
               <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="ml-3 text-sm text-red-700">{{ session('error') }}</p>
               </div>
          </div>
          @endif

          <form :action='editUrl' method="POST"
               class="p-6 space-y-6">
               @csrf
               @method('PUT')
               <!-- Tanggal -->
               <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">
                         Tanggal Masuk
                    </label>
                    <div class="mt-2">
                         <input type="date" name="tanggal" id="tanggal" required
                              :value="new Date(masukToEdit.tanggal).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})"
                              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                    @error('tanggal')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
               </div>

               <input type="hidden"
                    name="stok_obat_id"
                    id="stok_obat_id"
                    :value="masukToEdit.stok_obat_id"
                    required>

               <div>
                    <label for="nama_obat_input" class="block text-sm font-medium text-gray-700">
                         Nama Obat
                    </label>
                    <div class="mt-2">
                         <div class="relative rounded-md">
                              <div class="flex">
                                   <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50">
                                        <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-blue-100">
                                             <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                             </svg>
                                        </span>
                                   </span>
                                   <input type="text"
                                        name="nama_obat_input"
                                        id="nama_obat_input"
                                        :value="masukToEdit.nama_obat_input"
                                        required
                                        class="block w-full rounded-r-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                              </div>
                         </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Masukkan nama obat yang akan diubah stoknya</p>
                    @error('nama_obat_input')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
               </div>

               <div>
                    <label for="kategori_input" class="block text-sm font-medium text-gray-700">
                         Nama Obat
                    </label>
                    <div class="mt-2">
                         <div class="relative rounded-md">
                              <div class="flex">
                                   <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50">
                                        <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-blue-100">
                                             <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                             </svg>
                                        </span>
                                   </span>
                                   <input type="text"
                                        name="kategori_input"
                                        id="kategori_input"
                                        :value="masukToEdit.kategori_input"
                                        required
                                        class="block w-full rounded-r-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                              </div>
                         </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Masukkan kategori obat yang akan diubah stoknya</p>
                    @error('kategori_input')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
               </div>

               <!-- Jumlah Stok -->
               <div>
                    <label for="jumlah_masuk" class="block text-sm font-medium text-gray-700">
                         Jumlah Stok
                    </label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                         <input type="number" name="jumlah_masuk" id="jumlah_masuk"
                              :value="masukToEdit.jumlah_masuk"
                              autocomplete="given-name"
                              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                         <div class="absolute inset-y-0 right-5 flex items-center pr-3">
                              <span class="text-gray-500 sm:text-sm">pcs</span>
                         </div>
                    </div>
                    @error('jumlah_masuk')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Masukkan jumlah stok dalam satuan pcs</p>
               </div>

               <!-- Form Actions -->
               <div class="pt-5 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                         <button type="submit"
                              class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                              Simpan
                         </button>
                    </div>
               </div>
          </form>
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