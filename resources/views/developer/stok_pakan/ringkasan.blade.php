<div class="max-w-7xl mx-auto">
     <!-- Header -->
     <div class="p-6 flex justify-between items-center">
          <div class="flex items-center">
               <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
               </svg>
               <h2 class="ml-2 text-xl font-semibold text-gray-800">Stok Pakan</h2>
          </div>
     </div>

     <div class="overflow-x-auto">
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
          <table class="min-w-full divide-y divide-gray-200">
               <thead class="bg-gray-50">
                    <tr>
                         <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                         <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Jenis</th>
                         <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Satuan</th>
                         <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Keterangan</th>
                         <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Jumlah Masuk</th>
                         <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Jumlah Terpakai</th>
                         <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Jumlah Tersedia</th>
                    </tr>
               </thead>
               <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ringkasan->where('tersedia', '>', 0) as $stok)
                    <tr class="hover:bg-gray-50 transition-colors">
                         <td class="px-7 py-4 text-center whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                         <td class="px-6 py-4 text-center  whitespace-nowrap text-sm font-medium text-gray-900">
                              @php
                              $badgeColors = [
                              'SB10' => 'purple',
                              'SB11' => 'red',
                              'SB12' => 'green'
                              ];
                              $color = $badgeColors[$stok->kode] ?? 'gray';
                              @endphp
                              <span class="inline-flex items-center rounded-md bg-{{ $color }}-50 px-2 py-1 text-xs font-medium text-{{ $color }}-700 ring-1 ring-{{ $color }}-700/10 ring-inset">{{ $stok->kode }}</span>
                         </td>
                         <td class="px-6 py-4 text-center  whitespace-nowrap text-sm font-bold text-gray-700">{{ $stok->satuan }}</td>
                         <td class="px-6 py-4 text-center whitespace-nowrap text-sm font-bold text-gray-700">{{ explode(' ', $stok->keterangan)[0] }}</td>
                         <td class="px-6 py-4 text-center  whitespace-nowrap text-sm text-gray-900">{{ $stok->masuk }}</td>
                         <td class="px-6 py-4 text-center  whitespace-nowrap text-sm text-gray-900">{{ $stok->keluar }}</td>
                         <td class="px-6 py-4 text-center  whitespace-nowrap text-sm text-gray-900">{{ $stok->tersedia }}</td>
                    </tr>
                    @empty
                    <tr>
                         <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                              <div class="flex flex-col items-center py-6">
                                   <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                   </svg>
                                   <p class="text-lg">Belum ada data stok pakan</p>
                              </div>
                         </td>
                    </tr>
                    @endforelse
               </tbody>
          </table>
     </div>
</div>