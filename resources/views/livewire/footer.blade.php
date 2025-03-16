<footer class="relative top-full w-full mx-auto text-amber-50" x-data="{ modelOpen : @entangle('isOpen') }">
    <div id="copyright" class="text-center mt-4">
        <div class="text-[.75rem]">
            <form id="frmLogout" class="inline-block" action="{{ url('logout') }}" method="POST">
                @csrf
                <a onclick="document.getElementById('frmLogout').submit(); ">Logout</a>
            </form>
            |
            <button wire:click.prevent="openModal('lgl-cookie')" class="modFooter" type="button">
                Cookies
            </button>
            |
            <button wire:click="openModal('lgl-privacy')" class="modFooter" type="button">
                Privacy
            </button>
            |
            <button wire:click.prevent="openModal('lgl-tos')" class=" modFooter" type="button">
                Terms of Service
            </button>
        </div>
        <p class="mt-4 text-[.65rem] p-0 leading-tight">
            Another <a href="https://www.mojoimpact.com" class="" target="_blank" rel="noopener">
                <span class="font-semibold">Mojo Impact<span class="trade"></span></span></a> custom web
            application.<br>
            Design and development by <a href="https://www.mojoimpact.net" class="link link-hover glow"
                                         target="_blank" rel="noopener">
                <span class="font-semibold ">Mojo Impact Web Development</span></a>
            <br>
            Copyright &copy; 2012 &mdash; {{date('Y')}}
            <span class="font-semibold">Mojo Impact</span>, LLC. All rights
            reserved.
        </p>
    </div>

    <div x-cloak x-show="modelOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
            <div x-cloak @click="modelOpen = false" x-show="modelOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-slate-700/80" aria-hidden="true"
            ></div>

            <div x-cloak x-show="modelOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block w-full max-w-5xl p-8 my-20 overflow-hidden text-left transition-all transform
                 bg-white rounded-lg shadow-xl  "
            >
                <div class="flex items-center justify-end space-x-4">
                    <button @click="modelOpen = false" class="text-gray-600 focus:outline-hidden hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>
                </div>
                <x-dynamic-component :component="$componentName"/>
                <div class="p-4 flex justify-end items-center gap-2">
                    <button type="button" @click="modelOpen = false"
                            class="inline-flex border font-sans font-medium text-center transition-all duration-300 ease-in
                        disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full
                        data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md
                         bg-red-800 border-red-800 text-slate-50 hover:bg-red-600 hover:border-red-700">
                        Close
                    </button>

                </div>
            </div>
        </div>
    </div>
</footer>
