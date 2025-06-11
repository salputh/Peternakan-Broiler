<div x-show="showDailyRecordModal"
     x-data="{ postUrl: '{{ route('developer.daily.record.store') }}' }"
     x-cloak
     class=" fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">
     <div class="flex items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
          {{-- Overlay with improved opacity and backdrop filter --}}
          <div x-show="showDailyRecordModal"
               x-transition:enter="ease-out duration-300"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100"
               x-transition:leave="ease-in duration-200"
               x-transition:leave-start="opacity-100"
               x-transition:leave-end="opacity-0"
               @click="showDailyRecordModal = false"
               class="fixed inset-0 bg-gray-500/70 transition-opacity"></div>

          <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

          {{-- Modal panel with improved layout --}}
          <div x-show="showDailyRecordModal"
               x-transition:enter="ease-out duration-300"
               x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
               x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
               x-transition:leave="ease-in duration-200"
               x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
               x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
               class="relative inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl sm:align-middle">

               {{-- Close button --}}
               <button type="button"
                    @click="showDailyRecordModal = false"
                    class="absolute right-4 top-4 text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="sr-only">Tutup</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
               </button>

               <form x-bind:action='postUrl' method="POST" class="divide-y divide-gray-200">
                    @csrf
                    <input type="hidden" name="periode_id" value="{{ $periodeAktif->id }}">

                    {{-- Header --}}
                    <div class="bg-white px-6 py-5">
                         <h3 class="text-xl font-semibold text-gray-900" id="modal-title">Catat Performa Harian</h3>
                         <p class="mt-1 text-sm text-gray-500">Masukkan data performa harian untuk periode ini.</p>
                    </div>

                    {{-- Form content --}}
                    <div class="bg-white px-6 py-5">
                         <h4 class="text-xl font-medium text-gray-900 text-center mb-5">Data Performa Harian</h4>
                         <div class="grid grid-cols-1 gap-y-8 md:grid-cols-2 md:gap-x-8">
                              {{-- Data Harian Section --}}
                              <div class="space-y-6">
                                   <div class="rounded-lg p-4 shadow-md outline-1 outline-gray-200">
                                        <div class="space-y-4">
                                             {{-- Date and Age inputs --}}
                                             @php
                                             // Cari usia terakhir yang sudah terisi
                                             $usiaSelanjutnya = 1; // default usia pertama
                                             $tanggalSelanjutnya = $periodeAktif->tanggal_mulai; // default tanggal mulai periode

                                             if($ringkasanPerformaHarian && $ringkasanPerformaHarian->count() > 0) {
                                             // Ambil usia tertinggi yang sudah ada
                                             $usiaMax = $ringkasanPerformaHarian->max('usia_ke');
                                             $usiaSelanjutnya = $usiaMax + 1;

                                             // Hitung tanggal berdasarkan usia selanjutnya
                                             $tanggalSelanjutnya = \Carbon\Carbon::parse($periodeAktif->tanggal_mulai)
                                             ->addDays($usiaSelanjutnya - 1)
                                             ->format('Y-m-d');
                                             } else {
                                             // Jika belum ada data, gunakan tanggal mulai periode
                                             $tanggalSelanjutnya = \Carbon\Carbon::parse($periodeAktif->tanggal_mulai)->format('Y-m-d');
                                             }
                                             @endphp
                                             <div class="grid grid-cols-2 gap-4">
                                                  <div>
                                                       <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                                       <input type="date" name="tanggal" id="tanggal"
                                                            class="mt-1 block w-full rounded-md border-gray-300 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 px-2 py-1.5"
                                                            value="{{ $tanggalSelanjutnya }}" required>
                                                  </div>
                                                  <div>
                                                       <label for="usia" class="block text-sm font-medium text-gray-700">Usia (Hari)</label>
                                                       <input type="number" name="usia" id="usia"
                                                            class="mt-1 block w-full rounded-md border-gray-300  outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 px-2 py-1.5"
                                                            value="{{ $usiaSelanjutnya }}" required>
                                                  </div>
                                             </div>

                                             {{-- Temperature inputs --}}
                                             <div class="grid grid-cols-2 gap-4">
                                                  <div>
                                                       <label for="suhu_min" class="block text-sm font-medium text-gray-700">Suhu Min (°C)</label>
                                                       <input type="number" step="0.1" name="suhu_min" id="suhu_min"
                                                            placeholder="STD: {{ number_format((float)($standardHariIni->std_suhu_min ?? 0), 1) }}"
                                                            data-debug-placeholder-suhu-min-raw="{{ $standardHariIni->std_suhu_min ?? 'DEBUG_NULL' }}"
                                                            class="px-2 py-1.5 mt-1 block w-full rounded-md border-gray-300 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" required>
                                                  </div>
                                                  <div>
                                                       <label for="suhu_max" class="block text-sm font-medium text-gray-700">Suhu Max (°C)</label>
                                                       <input type="number" step="0.1" name="suhu_max" id="suhu_max"
                                                            placeholder="STD: {{ number_format((float)($standardHariIni->std_suhu_max ?? 0), 1) }}"
                                                            data-debug-placeholder-suhu-min-raw="{{ $standardHariIni->std_suhu_max ?? 'DEBUG_NULL' }}"
                                                            class="px-2 py-1.5 mt-1 block w-full rounded-md border-gray-300 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" required>
                                                  </div>
                                             </div>

                                             {{-- Humidity and Notes --}}
                                             <div>
                                                  <label for="kelembapan" class="block text-sm font-medium text-gray-700">Kelembaban (%)</label>
                                                  <input type="number" step="0.1" name="kelembapan" id="kelembapan"
                                                       placeholder="STD: {{ number_format((float)($standardHariIni->std_kelembapan ?? 0), 1) }}"
                                                       data-debug-placeholder-suhu-min-raw="{{ $standardHariIni->std_kelembapan ?? 'DEBUG_NULL' }}"
                                                       class="px-2 py-1.5 mt-1 block w-full rounded-md border-gray-300 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" required>
                                             </div>
                                             <div>
                                                  <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                                                  <textarea name="catatan" id="catatan" rows="2"
                                                       placeholder="Masukkan catatan tambahan jika ada"
                                                       class="px-2 py-1.5 mt-1 block w-full rounded-md border-gray-300 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                                             </div>
                                        </div>
                                   </div>

                                   {{-- Deplesi Card --}}
                                   <div class="rounded-lg p-4 shadow-md outline-1 outline-gray-200">
                                        <h5 class="font-medium text-gray-900">Deplesi</h5>
                                        <div class="mt-4 space-y-4">
                                             <div class="grid grid-cols-2 gap-4">
                                                  <div>
                                                       <label for="jumlah_mati" class="block text-sm font-medium text-gray-700">Jumlah Mati</label>
                                                       <input type="number" name="jumlah_mati" id="jumlah_mati"
                                                            placeholder="0"
                                                            class="px-2 py-1.5 mt-1 block w-full rounded-md border-gray-300 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                            required>
                                                  </div>
                                                  <div>
                                                       <label for="jumlah_afkir" class="block text-sm font-medium text-gray-700">Jumlah Afkir</label>
                                                       <input type="number" name="jumlah_afkir" id="jumlah_afkir"
                                                            placeholder="0"
                                                            class="px-2 py-1.5 mt-1 block w-full rounded-md border-gray-300 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                            value="0" required>
                                                  </div>
                                             </div>

                                             <div>
                                                  <label for="foto_deplesi" class="block text-sm font-medium text-gray-700">Foto Deplesi</label>
                                                  <input type="file"
                                                       name="foto_deplesi"
                                                       id="foto_deplesi"
                                                       accept="image/*"
                                                       max="10485760"
                                                       onchange="validateFileSize(this)"
                                                       class="mt-1 block w-full text-sm text-gray-500
                                                            file:mr-4 file:py-2 file:px-4
                                                            file:border-0
                                                            file:text-sm file:font-medium
                                                            file:bg-indigo-50 file:text-indigo-700
                                                            hover:file:bg-indigo-100 outline-1 -outline-offset-1 outline-gray-300 rounded-md
                                                            focus:outline-none hover:cursor-pointer">
                                                  <script>
                                                       function validateFileSize(input) {
                                                            if (input.files[0].size > 10485760) { // 10MB in bytes
                                                                 alert('File terlalu besar. Maksimal ukuran file adalah 10MB.');
                                                                 input.value = '';
                                                            }
                                                       }
                                                  </script>
                                             </div>

                                             <div>
                                                  <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                                                  <textarea name="keterangan" id="keterangan" rows="2"
                                                       placeholder="Masukkan keterangan deplesi jika ada"
                                                       class="px-2 py-1.5 mt-1 block w-full rounded-md border-gray-300 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              {{-- Data Transaksi Section --}}
                              <div class=" space-y-6">
                                   {{-- Pakan & Obat Card --}}
                                   <div class="rounded-lg p-4 shadow-md outline-1 outline-gray-200">
                                        <div class="space-y-4">
                                             {{-- Pakan Section --}}
                                             <div x-data="{ open: false, selectedPakan: '', selectedPakanId: '' }">
                                                  <h5 class="font-medium text-gray-900">Pakan</h5>
                                                  <div class="mt-3 grid gap-4">
                                                       <div>
                                                            <label for="pakan_keluar_jenis_id" class="block text-sm/6 font-medium text-gray-900">Jenis Pakan</label>
                                                            <div class="relative mt-2">
                                                                 <button type="button" @click="open = !open" class="grid w-full cursor-default grid-cols-1 rounded-md bg-white py-1.5 pr-2 pl-3 text-left text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" aria-haspopup="listbox" :aria-expanded="open.toString()" aria-labelledby="pakan-listbox-label">
                                                                      <span class="col-start-1 row-start-1 flex items-center gap-3 pr-6">
                                                                           <span x-text="selectedPakan || 'Pilih Jenis Pakan'" class="block truncate"></span>
                                                                      </span>
                                                                      <svg class="col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-500 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                                                           <path fill-rule="evenodd" d="M5.22 10.22a.75.75 0 0 1 1.06 0L8 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-2.25 2.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 0 1 0-1.06ZM10.78 5.78a.75.75 0 0 1-1.06 0L8 4.06 6.28 5.78a.75.75 0 0 1-1.06-1.06l2.25-2.25a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
                                                                      </svg>
                                                                 </button>
                                                                 <ul x-show="open" @click.away="open = false" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm" tabindex="-1" role="listbox" aria-labelledby="pakan-listbox-label">
                                                                      @foreach($pakanJenisOptions as $pakanJenis)
                                                                      @php
                                                                      // Cari stok pakan untuk jenis pakan ini di periode aktif
                                                                      $stokPakan = $periodeAktif->stokPakans()->where('pakan_jenis_id', $pakanJenis->id)->first();
                                                                      $stokTersedia = 0;

                                                                      if ($stokPakan) {
                                                                      $totalMasuk = $stokPakan->stokPakanMasuk->sum('jumlah_masuk');
                                                                      $totalKeluar = $stokPakan->stokPakanKeluar->sum('jumlah_keluar');
                                                                      $stokTersedia = $totalMasuk - $totalKeluar;
                                                                      }
                                                                      @endphp
                                                                      <li @click="selectedPakan = '{{ $pakanJenis->kode }} - {{ $pakanJenis->keterangan }}'; selectedPakanId = {{ $pakanJenis->id }}; open = false; $dispatch('input', '{{ $pakanJenis->id }}')" class="relative cursor-default select-none py-2 pr-9 pl-3 text-gray-900 hover:bg-indigo-600 hover:text-white" id="pakan-listbox-option-{{ $loop->index }}" role="option">
                                                                           <div class="flex items-center">
                                                                                <span x-bind:class="selectedPakanId == {{ $pakanJenis->id }} ? 'font-semibold' : 'font-normal'" class="ml-3 block truncate">{{ $pakanJenis->kode }} - {{ $pakanJenis->keterangan }}</span>
                                                                           </div>
                                                                           <span x-show="selectedPakanId == {{ $pakanJenis->id }}" class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600 hover:text-white">
                                                                                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                                     <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                                                                </svg>
                                                                           </span>
                                                                      </li>

                                                                      @endforeach
                                                                 </ul>
                                                                 <input type="hidden" name="pakan_keluar_jenis_id" x-model="selectedPakanId">
                                                            </div>
                                                       </div>
                                                  </div>
                                                  <div class="mt-3">
                                                       <label for="pakan_keluar_jumlah" class="block text-sm font-medium text-gray-700">Jumlah ZAK</label>
                                                       <div class="flex items-center gap-2">
                                                            <div class="relative flex-1">
                                                                 <input type="number" name="pakan_keluar_jumlah" id="pakan_keluar_jumlah"
                                                                      placeholder="STD: {{ number_format((float)($standardHariIni->pakan_std_zak ?? 0)) }}"
                                                                      data-debug-placeholder-suhu-min-raw="{{ $standardHariIni->pakan_std_zak ?? 'DEBUG_NULL' }}"
                                                                      class="px-2 py-1.5 block w-full rounded-md border-gray-300 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                                      required>
                                                            </div>
                                                            <div x-show="selectedPakanId" class="flex items-center whitespace-nowrap">
                                                                 <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                                      Tersedia: <span x-text="(() => {
                                                                      if (!selectedPakanId) return '- zak';
                                                                      
                                                                      // Buat mapping stok dari PHP
                                                                      const stokMapping = {
                                                                           @foreach($periodeAktif->stokPakans ?? [] as $stok)
                                                                           {{ $stok->pakan_jenis_id }}: {
                                                                                jumlah_stok: {{ $stok->jumlah_stok }},
                                                                                satuan: '{{ $stok->pakanJenis->satuan ?? 'zak' }}'
                                                                           },
                                                                           @endforeach
                                                                      };
                                                                      
                                                                      const stokData = stokMapping[selectedPakanId];
                                                                      if (stokData) {
                                                                           return stokData.jumlah_stok + ' ' + stokData.satuan;
                                                                      }
                                                                      
                                                                      return '0 zak';
                                                                 })()" class="ml-1 font-semibold">
                                                                      </span>
                                                                 </span>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>


                                             {{-- Obat Section --}}
                                             <div class="pt-5 border-t border-gray-200" x-data="{ open: false, selectedObat: '', selectedObatId: '' }">
                                                  <h5 class="font-medium text-gray-900">Obat</h5>
                                                  <div class="mt-3 grid gap-4">
                                                       <div>
                                                            <label for="stok_obat_id" class="block text-sm/6 font-medium text-gray-900">Nama Obat</label>
                                                            <div class="relative mt-2">
                                                                 @if(count($stokObatOptions) > 0)
                                                                 <button type="button" @click="open = !open" class="grid w-full cursor-default grid-cols-1 rounded-md bg-white py-1.5 pr-2 pl-3 text-left text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" aria-haspopup="listbox" :aria-expanded="open.toString()" aria-labelledby="obat-listbox-label">
                                                                      <span class="col-start-1 row-start-1 flex items-center gap-3 pr-6">
                                                                           <span x-text="selectedObat || 'Pilih Obat'" class="block truncate"></span>
                                                                      </span>
                                                                      <svg class="col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-500 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                                                           <path fill-rule="evenodd" d="M5.22 10.22a.75.75 0 0 1 1.06 0L8 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-2.25 2.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 0 1 0-1.06ZM10.78 5.78a.75.75 0 0 1-1.06 0L8 4.06 6.28 5.78a.75.75 0 0 1-1.06-1.06l2.25-2.25a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
                                                                      </svg>
                                                                 </button>
                                                                 <ul x-show="open" @click.away="open = false" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm" tabindex="-1" role="listbox" aria-labelledby="obat-listbox-label">
                                                                      @foreach($stokObatOptions as $obat)
                                                                      <li @click="selectedObat = '{{ $obat->nama_obat }} ({{ $obat->kategori }})'; selectedObatId = '{{ $obat->id }}'; open = false; $dispatch('input', '{{ $obat->id }}')" class="relative cursor-default select-none py-2 pr-9 pl-3 text-gray-900 hover:bg-indigo-600 hover:text-white" id="obat-listbox-option-{{ $loop->index }}" role="option">
                                                                           <div class="flex items-center">
                                                                                <span x-bind:class="selectedObatId === '{{ $obat->id }}' ? 'font-semibold' : 'font-normal'" class="ml-3 block truncate">{{ $obat->nama_obat }} ({{ $obat->kategori }})</span>
                                                                           </div>
                                                                           <span x-show="selectedObatId === '{{ $obat->id }}'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600 hover:text-white">
                                                                                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                                     <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                                                                </svg>
                                                                           </span>
                                                                      </li>
                                                                      @endforeach
                                                                 </ul>
                                                                 <input type="hidden" name="stok_obat_id" x-model="selectedObatId">
                                                                 @else
                                                                 <div class="text-sm text-gray-500 py-2">Tidak ada stok obat tersedia</div>
                                                                 @endif
                                                            </div>
                                                       </div>
                                                       <div>
                                                            <label for="obat_keluar_jumlah" class="block text-sm font-medium text-gray-700">
                                                                 Jumlah
                                                            </label>
                                                            <div class="flex items-center gap-2 whitespace-nowrap">
                                                                 <input type="number" name="obat_keluar_jumlah" id="obat_keluar_jumlah" placeholder="0" class="px-2 py-1.5 mt-1 block w-full rounded-md border-gray-300 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" required>

                                                                 <div x-show="selectedObatId" class="mt-1 flex items-center">
                                                                      <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Tersedia:<span x-text="(() => {const obat = {{ Js::from($stokObatOptions) }}.find(o => o.id == selectedObatId);if (obat) {const jumlahTersedia = obat.jumlah_tersedia ?? 0;const satuan = obat.satuan ? ' ' + obat.satuan : '';return jumlahTersedia + ' pcs';}return '';})()" class="ml-1 font-semibold">
                                                                           </span>
                                                                      </span>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>

                                   {{-- Body Weight Card --}}
                                   <div class="rounded-lg p-4 shadow-md outline-1 outline-gray-200 ">
                                        <h5 class="font-medium text-gray-900">Berat Badan</h5>
                                        <div class="mt-4">
                                             <label for="body_weight_hasil" class="block text-sm font-medium text-gray-700">Berat Rata-rata (Gram)</label>
                                             <input type="number" step="0.01" name="body_weight_hasil" id="body_weight_hasil"
                                                  placeholder="STD: {{ number_format((float)($standardHariIni->bw_std_abw ?? 0), 1) }}"
                                                  data-debug-placeholder-suhu-min-raw="{{ $standardHariIni->bw_std_abw ?? 'DEBUG_NULL' }}"
                                                  class="px-2 py-1.5 mt-2 block w-full rounded-md border-gray-300 outline-1 outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" required>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                         <button type="submit"
                              class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                              Simpan Data
                         </button>
                         <button type="button"
                              @click="showDailyRecordModal = false"
                              class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm">
                              Batal
                         </button>
                    </div>
               </form>
          </div>
     </div>
</div>