@props(['title' => 'Success', 'message' => 'Operasi berhasil dilakukan'])

<div x-show="showAlertModal" x-cloak
     class="relative z-50"
     role="dialog"
     aria-modal="true">

     <div x-show="showAlertModal"
          x-transition:enter="ease-out duration-300"
          x-transition:enter-start="opacity-0"
          x-transition:enter-end="opacity-100"
          x-transition:leave="ease-in duration-200"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          @click="showAlertModal = false"
          class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>

     <div class="fixed inset-0 z-50 w-screen h-screen flex items-center justify-center">
          <div class="p-4 w-full max-w-lg">
               <div x-show="showAlertModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.outside="showAlertModal = false"
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all">

                    <div class="mx-auto bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                         <div class="flex flex-col items-center justify-center">
                              <div class="mx-auto flex h-36 w-36 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 sm:h-30 sm:w-30">
                                   <svg class="h-18 w-18 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                   </svg>
                              </div>
                              <div class="mt-3 text-center">
                                   <h3 x-text="alertTitle" class="text-lg font-semibold leading-6 text-gray-900">
                                   </h3>
                                   <div class="mt-2">
                                        <p x-text="alertMessage" class="text-sm text-gray-500">
                                        </p>
                                   </div>
                              </div>
                         </div>
                    </div>


                    <button type="button"
                         @click="showAlertModal = false"
                         class=" hover:cursor-pointer w-full flex justify-center bg-red-600 px-3 py-4 text-md font-semibold text-white shadow-sm hover:bg-red-500">
                         OK
                    </button>

               </div>
          </div>
     </div>
</div>