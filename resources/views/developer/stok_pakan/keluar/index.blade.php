<div class="bg-white rounded-xl shadow-md overflow-hidden">
     <div x-data="{ currentView: 'table' }">
          <div class="flex items-center justify-between p-6">
               <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <h2 class="ml-2 text-xl font-semibold text-gray-800">Stok Pakan Terpakai</h2>
               </div>
          </div>

          <!-- Table View -->
          <div x-show="currentView === 'table'"
               class="bg-white shadow rounded-lg overflow-x-auto">
               <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                         <tr>
                              <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal</th>
                              <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Jenis Pakan</th>
                              <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Jumlah Terpakai</th>
                              <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Satuan</th>
                              <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Keterangan</th>
                              <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                         </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                         @forelse($keluarList as $item)
                         <tr class="hover:bg-gray-50 transition">
                              <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">{{ $item->tanggal }}</td>
                              <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">{{ $item->pakanJenis->nama ?? '-' }}</td>
                              <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">{{ $item->jumlah }}</td>
                              <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">{{ $item->satuan }}</td>
                              <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">{{ $item->keterangan ?? '-' }}</td>
                              <td class="px-6 py-4 text-center whitespace-nowrap text-sm">
                                   <a href="#" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">Edit</a>
                                   <form action="#" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition ml-2" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                   </form>
                              </td>
                         </tr>
                         @empty
                         <tr>
                              <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                   <div class="flex flex-col items-center py-6">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <p class="text-lg">Belum ada data stok pakan harian</p>
                                   </div>
                              </td>
                         </tr>
                         @endforelse
                    </tbody>
               </table>
          </div>
     </div>
</div>