@extends('developer.layouts.dev')

@section('content')
<div class="p-8">
     <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-800">Daftar Peternakan</h2>
          <a href='{{ route('developer.peternakan.create') }}'
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center gap-2">
               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
               </svg>
               Tambah Peternakan
          </a>
     </div>

     @if(session('success'))
     <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700">
          {{ session('success') }}
     </div>
     @endif

     <div class="bg-white rounded-xl shadow-md overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
               <thead class="bg-gray-50">
                    <tr>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Informasi Peternakan</th>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kandang</th>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
               </thead>
               <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($peternakans as $index => $peternakan)
                    <tr class="hover:bg-gray-50">
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                              {{ $index + 1 }}
                         </td>
                         <td class="px-6 py-4">
                              <div class="text-sm">
                                   <div class="font-medium text-gray-900">{{ $peternakan->peternakan_nama }}</div>
                                   <div class="text-gray-500">{{ $peternakan->peternakan_alamat }}</div>
                                   <div class="text-gray-500">
                                        <span class="inline-flex items-center">
                                             <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                             </svg>
                                             {{ $peternakan->peternakan_telp }}
                                        </span>
                                   </div>
                              </div>
                         </td>
                         <td class="px-6 py-4">
                              <div class="flex items-center">
                                   @if($peternakan->owner->user_foto)
                                   <img class="h-10 w-10 rounded-full mr-3" src="{{ asset('storage/' . $peternakan->owner->user_foto) }}" alt="Foto {{ $peternakan->owner->user_nama }}">
                                   @else
                                   <div class="h-10 w-10 rounded-full bg-gray-200 mr-3 flex items-center justify-center">
                                        <span class="text-gray-500 text-lg">{{ substr($peternakan->owner->user_nama, 0, 1) }}</span>
                                   </div>
                                   @endif
                                   <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $peternakan->owner->user_nama }}</div>
                                        <div class="text-sm text-gray-500">{{ $peternakan->owner->user_email }}</div>
                                   </div>
                              </div>
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                              {{ $peternakan->kandangs_count }} Kandang
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap">
                              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $peternakan->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                   {{ $peternakan->is_active ? 'Aktif' : 'Non-aktif' }}
                              </span>
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-y-2">
                              <a href="{{ route('developer.dashboard.kandang', ['peternakan' => $peternakan->id, 'kandang' => $kandangs->id]) }}"
                                   class="block text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition duration-200 text-center">
                                   <span class="flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        Lihat Kandang
                                   </span>
                              </a>
                              <a href="{{ route('developer.peternakan.edit', $peternakan->peternakan_id) }}"
                                   class="block text-green-600 hover:text-green-900 bg-green-50 px-3 py-1.5 rounded-lg hover:bg-green-100 transition duration-200 text-center">
                                   <span class="flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                   </span>
                              </a>
                              <form action="{{ route('peternakan.destroy', $peternakan->peternakan_id) }}" method="POST" class="inline-block w-full">
                                   @csrf
                                   @method('DELETE')
                                   <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus peternakan ini?')"
                                        class="w-full text-red-600 hover:text-red-900 bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition duration-200 text-center">
                                        <span class="flex items-center justify-center gap-1">
                                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                             </svg>
                                             Hapus
                                        </span>
                                   </button>
                              </form>
                         </td>
                    </tr>
                    @endforeach
               </tbody>
          </table>
     </div>
</div>
@endsection