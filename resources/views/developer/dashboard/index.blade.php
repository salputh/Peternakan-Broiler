@extends('developer.layouts.dev')

@section('content')
<div class="p-8">
     <div class="flex justify-between">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Owner & Peternakan</h2>
          <div class="flex space-x-4 mb-8">
               <a href="{{ route('developer.owner.create') }}" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Tambah Owner
               </a>
          </div>
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
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Informasi Owner</th>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peternakan</th>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
               </thead>
               <tbody class="bg-white divide-y divide-gray-200 ">
                    @foreach($peternakans as $index => $peternakan)
                    <tr class="hover:bg-gray-50">
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                              {{ $index + 1 }}
                         </td>
                         <td class="px-6 py-4">
                              <div class="flex items-center">

                                   @if(optional($peternakan->owner)->photo)

                                   <img class="h-10 w-10 object-cover rounded-full mr-3" src="{{ asset('storage/' . $peternakan->owner->photo) }}">
                                   @else
                                   <div class="h-10 w-10 rounded-full bg-gray-200 mr-3 flex items-center justify-center">
                                        <span class="text-gray-500 text-lg">{{ substr($peternakan->owner->name, 0, 1) }}</span>
                                   </div>

                                   @endif
                                   <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $peternakan->owner->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $peternakan->owner->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $peternakan->owner->phone }}</div>
                                   </div>
                              </div>
                         </td>
                         <td class="px-6 py-4">
                              <div class="text-xs text-gray-500">
                                   <span class="inline-flex items-center">
                                        {{ $peternakan->nama }}
                                   </span>
                              </div>
                         </td>
                         <td class="px-6 py-4">
                              <div class="text-xs text-gray-500">
                                   <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-green-600/20 ring-inset">{{ $peternakan->is_active == 1 ? 'Active' : 'Off' }}</span>
                              </div>
                         </td>
                         <td class="flex px-6 py-8 whitespace-nowrap text-sm font-medium">
                              <a href="{{ route('developer.kandang.index', ['peternakan' => $peternakan->slug]) }}"
                                   class="block text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition duration-200 text-center mr-4">
                                   <span class="flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Daftar Kandang
                                   </span>
                              </a>
                              <a href="{{ route('developer.owner.edit', $peternakan->owner->slug) }}"
                                   class="block text-green-600 hover:text-green-900 bg-green-50 px-3 py-1.5 rounded-lg hover:bg-green-100 transition duration-200 text-center mr-4">
                                   <span class="flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit Owner
                                   </span>
                              </a>

                              <button
                                   @click="
                                                showDeleteModal = true;
                                                deleteUrl = '{{ route('developer.owner.destroy', $peternakan->owner->slug) }}';
                                                modalTitle = 'Konfirmasi Hapus Owner';
                                                modalMessage = 'Apakah Anda yakin ingin menghapus owner ini? Semua data terkait owner ini akan dihapus secara permanen.';"
                                   type="button"
                                   class="text-red-600 hover:cursor-pointer hover:text-red-900 bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition duration-200 text-center">
                                   <span class="flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus Owner
                                   </span>
                              </button>
                         </td>
                    </tr>
                    @endforeach
               </tbody>
          </table>
     </div>
</div>
@endsection