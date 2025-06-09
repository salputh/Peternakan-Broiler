<div class="bg-white rounded-xl shadow-md overflow-hidden">
     <div x-data="{ currentView: 'table', masukToEdit: {}, editUrl: '', openEditForm(masuk) {
        this.masukToEdit = masuk;
        this.editUrl = '{{ route('developer.stok_obat.masuk.update', ['peternakan' => $peternakan->slug, 'kandang' => $kandang->slug, 'masuk' => ':id']) }}'.replace(':id', masuk.id);
        this.currentView = 'formEdit';
    } }">
          <!-- Header Section -->
          <div class="flex items-center justify-between p-6">
               <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <h2 class="ml-2 text-xl font-semibold text-gray-800">Daftar Stok Obat Masuk</h2>
               </div>

               <div>
                    <button
                         @click="currentView = currentView === 'table' ? 'form' : 'table'"
                         class="px-4 py-2 bg-blue-600 text-white rounded hover:cursor-pointer text-xs">
                         <span x-text="currentView === 'table' ? '+ Tambah Masuk' : 'Batal'"></span>
                    </button>
               </div>
          </div>

          <!-- Form Views -->
          <div x-show="currentView === 'form'" x-transition:enter="transition ease-out duration-300">
               @include('developer.stok_obat.masuk.create')
          </div>

          <div x-show="currentView === 'formEdit'" class="max-w-5xl mx-auto">
               @include('developer.stok_obat.masuk.edit')
          </div>

          <!-- Table View -->
          <div x-show="currentView === 'table'" x-transition:enter="transition ease-out duration-300">
               <!-- Alert Messages -->
               @foreach(['success','warning','error'] as $msg)
               @if(session($msg))
               @php
               $color = $msg === 'success' ? 'green' : ($msg === 'warning' ? 'yellow' : 'red');
               @endphp
               <div class="p-4 mb-4 bg-{{ $color }}-50 border-l-4 border-{{ $color }}-500">
                    <div class="flex items-center">
                         <svg class="h-5 w-5 text-{{ $color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              @if($msg ==='success')
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                              @elseif($msg ==='warning')
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M6 16h12M6 8h12" />
                              @else
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                              @endif
                         </svg>
                         <p class="ml-3 text-sm text-{{ $color }}-700">{{ session($msg) }}</p>
                    </div>
               </div>
               @endif
               @endforeach


               <!-- Transaction Table -->
               <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                         <tr>
                              <th class="px-6 py-3 text-xs text-center font-bold text-gray-700 uppercase tracking-wider">Tanggal</th>
                              <th class="px-6 py-3 text-xs text-center font-bold text-gray-700 uppercase tracking-wider">Nama Obat</th>
                              <th class="px-6 py-3 text-xs text-center font-bold text-gray-700 uppercase tracking-wider">Kategori</th>
                              <th class="px-6 py-3 text-xs text-center font-bold text-gray-700 uppercase tracking-wider">Jumlah Masuk</th>
                              <th class="px-6 py-3 text-xs text-center font-bold text-gray-700 uppercase tracking-wider">Satuan</th>
                              <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                         </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                         @forelse($masukList as $masuk)
                         <tr class="hover:bg-gray-50 transition">
                              <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900">{{ date('d M Y', strtotime($masuk->tanggal)) }}</td>
                              <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                   <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-red-700/10 ring-inset">
                                        {{ $masuk->nama_obat_input ?? '-' }}
                                   </span>
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">
                                   {{ $masuk->kategori_input ?? '-' }}
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $masuk->jumlah_masuk }}</td>
                              <td class="px-6 py-4 whitespace-nowrap text-center text-sm uppercase font-bold text-gray-900">{{ $masuk->satuan ?? 'PCS'}}</td>
                              <td class="px-6 py-4 whitespace-nowrap text-left text-sm">
                                   <button
                                        @click="openEditForm({{ json_encode($masuk) }})"
                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:cursor-pointer text-xs">
                                        Edit
                                   </button>
                                   <button
                                        @click="
                                                  showDeleteModal = true;
                                                  deleteUrl = '{{ route('developer.stok_obat.masuk.destroy', [
                                                       'peternakan' => $peternakan->slug,
                                                       'kandang' => $kandang->slug,
                                                       'masuk' => $masuk->id
                                                  ]) }}';
                                                  modalTitle = 'Konfirmasi Hapus Stok Obat Masuk';
                                                  modalMessage = 'Apakah Anda yakin ingin menghapus stok ini? Semua data terkait stok obat ini akan dihapus secara permanen.';"
                                        type="button"
                                        class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition ml-2">
                                        Hapus
                                   </button>
                              </td>
                         </tr>
                         @empty
                         <tr>
                              <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                   <div class="flex flex-col items-center py-6">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <p class="text-lg">Belum ada data stok obat masuk untuk periode ini</p>
                                   </div>
                              </td>
                         </tr>
                         @endforelse
                    </tbody>
               </table>
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