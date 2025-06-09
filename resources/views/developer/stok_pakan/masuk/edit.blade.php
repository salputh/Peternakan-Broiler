<div class="p-8 max-w-7xl mx-auto">
     <!-- Form Card -->
     <div class="max-w-3xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">
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
                         <h2 class="text-lg font-medium text-gray-900">Edit Stok Pakan</h2>
                         <p class="mt-1 text-sm text-gray-500">Masukkan informasi stok pakan yang akan diubah</p>
                    </div>
               </div>
          </div>

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


               <div x-data="{ 
                              open: false,
                              selected: { id: null, kode: '-- Pilih Jenis Pakan --' }
                              }"
                    x-init="$watch('masukToEdit', (newValue) => {
                              console.log('Watcher Terpicu! Menerima data baru:', newValue); // MATA-MATA 1

                              if (newValue && newValue.stok_pakans && newValue.stok_pakans.pakan_jenis) {
                                   console.log('Relasi ditemukan. Mengatur `selected`...'); // MATA-MATA 2
                                   selected = {
                                        id: newValue.stok_pakans.pakan_jenis_id,
                                        kode: newValue.stok_pakans.pakan_jenis.kode
                                   };
                              } else {
                                   console.error('GAGAL: Data `stok_pakans` atau `pakan_jenis` tidak lengkap atau null.', newValue); // MATA-MATA 3
                              }
                              })">
                    <label id="listbox-label" class="block text-sm font-medium text-gray-700">
                         Jenis Pakan
                    </label>
                    <div class="relative mt-2">
                         <button type="button"
                              @click="open = !open"
                              class="grid w-full cursor-default grid-cols-1 rounded-md bg-white py-1.5 pr-2 pl-3 text-left text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 sm:text-sm/6"
                              aria-haspopup="listbox"
                              :aria-expanded="open"
                              aria-labelledby="listbox-label"
                              :class="{ 'border-red-500': !selected && $refs.pakanInput.classList.contains('touched') }">
                              <span class="col-start-1 row-start-1 flex items-center gap-3 pr-6">
                                   <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-blue-100">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                   </span>
                                   <span class="block truncate" x-text="selected.kode || '-- Pilih Jenis Pakan --'"></span>
                              </span>
                              <svg class="col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-500 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                   <path fill-rule="evenodd" d="M5.22 10.22a.75.75 0 0 1 1.06 0L8 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-2.25 2.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 0 1 0-1.06ZM10.78 5.78a.75.75 0 0 1-1.06 0L8 4.06 6.28 5.78a.75.75 0 0 1-1.06-1.06l2.25-2.25a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
                              </svg>
                         </button>

                         <input type="hidden"
                              name="pakan_jenis_id"
                              :value="selected.id"
                              required
                              x-ref="pakanInput"
                              @blur="$el.classList.add('touched')">

                         <ul x-show="open"
                              @click.away="open = false"
                              x-transition:enter="transition ease-out duration-100"
                              x-transition:enter-start="transform opacity-0 scale-95"
                              x-transition:enter-end="transform opacity-100 scale-100"
                              x-transition:leave="transition ease-in duration-75"
                              x-transition:leave-start="transform opacity-100 scale-100"
                              x-transition:leave-end="transform opacity-0 scale-95"
                              class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
                              tabindex="-1"
                              role="listbox"
                              aria-labelledby="listbox-label">

                              @foreach ($pakanJenis as $pj)
                              <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900 hover:bg-blue-50"
                                   id="listbox-option-{{ $pj->id }}"
                                   role="option"
                                   @click="selected = { id: '{{ $pj->id }}', kode: '{{ $pj->kode }}' }; open = false">
                                   <div class="flex items-center">
                                        <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-blue-100">
                                             <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                             </svg>
                                        </span>
                                        <span class="ml-3 block truncate" :class="{ 'font-semibold': selected.id === '{{ $pj->id }}' }">
                                             {{ $pj->kode }}
                                        </span>
                                   </div>

                                   <span x-show="selected.id === '{{ $pj->id }}'"
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                             <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                        </svg>
                                   </span>
                              </li>
                              @endforeach
                         </ul>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Pilih jenis pakan yang akan diubah stoknya</p>
                    @error('pakan_jenis_id')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-red-500" x-show="!selected && $refs.pakanInput.classList.contains('touched')">
                         Jenis pakan harus dipilih
                    </p>
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
                              <span class="text-gray-500 sm:text-sm">zak</span>
                         </div>
                    </div>
                    @error('jumlah_masuk')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Masukkan jumlah stok dalam satuan zak</p>
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