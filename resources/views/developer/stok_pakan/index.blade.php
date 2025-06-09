@extends('developer.layouts.app')

@section('content')
@php
// Default tab if no parameter is set
$initialTab = request()->query('tab', 'ringkasan');
@endphp

<div class="p-8 max-w-7xl mx-auto">
     <h1 class="text-2xl font-bold text-gray-800">Stok Pakan</h1>
     <nav class="mt-2 text-sm text-gray-500" aria-label="Breadcrumb">
          <ol class="flex items-center space-x-2">
               <li><a href="#" class="hover:text-blue-600">Dashboard</a></li>
               <li>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                         <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
               </li>
               <li class="text-blue-600">Stok Pakan</li>
          </ol>
     </nav>
</div>

<div x-data="{ tab: '{{ $initialTab }}' }" class="px-8 max-w-7xl mx-auto">
     <div class="mb-6 border-b border-gray-300">
          <nav class="flex space-x-8" aria-label="Tabs">
               <button type="button" @click="tab = 'ringkasan'" :class="tab === 'ringkasan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:cursor-pointer'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Ringkasan</button>
               <button type="button" @click="tab = 'masuk'" :class="tab === 'masuk' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:cursor-pointer'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Stok Masuk</button>
               <button type="button" @click="tab = 'keluar'" :class="tab === 'keluar' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:cursor-pointer'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Stok Terpakai</button>
          </nav>
     </div>

     <!-- Tab Contents -->
     <div class="bg-white rounded-xl shadow-md overflow-hidden">
          <div x-show="tab === 'ringkasan'" x-cloak>
               @include('developer.stok_pakan.ringkasan')
          </div>

          <div x-show="tab === 'masuk'" x-cloak>
               @include('developer.stok_pakan.masuk.index')
          </div>

          <div x-show="tab === 'keluar'" x-cloak>
               @include('developer.stok_pakan.keluar.index')
          </div>
     </div>
</div>

@endsection