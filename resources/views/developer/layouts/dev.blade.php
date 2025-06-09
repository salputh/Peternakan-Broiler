<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Developer Dashboard</title>
      @vite(['resources/css/app.css'])
      <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

      <style>
            [x-cloak] {
                  display: none !important;
            }
      </style>
</head>

<body x-data="{ showDeleteModal: false, deleteUrl: '', modalTitle: '', modalMessage: '' }" class="bg-gray-50">
      <!-- Top Navigation Bar -->
      <nav class="fixed w-full z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                  <div class="flex justify-between h-16">
                        <div class="flex items-center">
                              <span class="text-2xl font-bold text-gray-800">Developer Panel</span>
                        </div>
                        <div class="flex justify-between items-center gap-5">
                              <div class="flex items-center space-x-4">
                                    <a href="{{ route('developer.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 bg-blue-50 hover:text-blue-600 hover:bg-blue-100 rounded-lg transition-all duration-200">
                                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                          </svg>
                                          <span class="font-medium">Peternakan</span>
                                    </a>
                              </div>

                              <form method="post" action="{{ route('developer.logout') }}" class="flex items-center space-x-4">
                                    @csrf
                                    <button type="submit" class="bg-red-400 hover:cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-red-500 transition duration-200">
                                          Logout
                                    </button>
                              </form>
                        </div>
                  </div>
            </div>
      </nav>

      <!-- Main Content -->
      <div class="pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                  <!-- Dashboard Stats -->
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @isset($peternakan)
                        <!-- Total Peternakan -->
                        <div class="bg-gradient-to-br from-blue-50 to-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-all duration-300">
                              <div class="flex items-center justify-between">
                                    <div>
                                          <h3 class="text-3xl font-bold text-blue-600">{{$totalPeternakan}}</h3>
                                          <p class="text-sm text-gray-600 mt-1">Total Peternakan</p>
                                    </div>
                                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                              </div>
                        </div>

                        <!-- Total Owner -->
                        <div class="bg-gradient-to-br from-green-50 to-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-all duration-300">
                              <div class="flex items-center justify-between">
                                    <div>
                                          <h3 class="text-3xl font-bold text-green-600">{{ $totalOwners }}</h3> <!-- Total Owner dikurangi developer-->
                                          <p class="text-sm text-gray-600 mt-1">Total Owner</p>
                                    </div>
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                              </div>
                        </div>

                        <!-- Peternakan Aktif -->
                        <div class="bg-gradient-to-br from-indigo-50 to-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-all duration-300">
                              <div class="flex items-center justify-between">
                                    <div>
                                          <h3 class="text-3xl font-bold text-indigo-600">{{$activePeternakan}}</h3>
                                          <p class="text-sm text-gray-600 mt-1">Peternakan Aktif</p>
                                    </div>
                                    <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                              </div>
                        </div>
                        @endisset
                  </div>

                  <!-- Content Area -->
                  <div class="bg-white rounded-xl shadow-md">
                        @yield('content')
                  </div>
            </div>
      </div>
      <x-delete-confirmation-modal />
</body>

</html>