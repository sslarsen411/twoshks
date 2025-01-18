<x-app-layout>
    @push('styles')
        @vite([  'resources/css/spinner.css'])
    @endpush
    <x-main-content class="border-2 bg-stone-100" x-data="{shiftPressed: false}">
        <div class="flex flex-col items-center justify-center top-0 left-0 ">
            <div class="grid grid-cols-12 gap-0">
                <div class="col-span-7">
                    <blockquote class="speech bubble">
                        <h2>
                            {{session('cust.first_name')}}, you&apos;re done!
                        </h2>
                        <p class="text-lg">
                            @desktop
                            Click
                            @elsedesktop
                            Tap
                            @enddesktop the button below and I&apos;ll craft your review&hellip;
                        </p>
                    </blockquote>
                </div>
                <div class="col-span-5">
                    <div class="flex">
                        <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-speak.webp')}}"
                             alt="Review Guru  icon">
                    </div>
                </div>
            </div>
            <div>
                <button type="button" onclick="doSpinner()"
                        class="px-5 py-4 rounded-xl text-base text-white  btn bg-slate-700 hover:bg-teal-500 mb-4 text-center float-right">
                    Generate Your Review
                </button>
            </div>
        </div>
        <div id="spinner" class="hidden place-content-center w-1/2 mx-auto my-4 bg-teal-700 px-8 py-4 rounded-lg  z-50"
             wire:loading>
            <div class="la-ball-beat la-1x">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div
            class="w-full divide-y divide-stone-300 overflow-hidden rounded-xl border border-slate-300 bg-stone-100/40 text-slate-700">
            <div x-data="{ isExpanded: false }" class="divide-y divide-slate-300 dark:divide-slate-700">
                <button id="controlsAccordionItemOne" type="button" class="flex w-full items-center justify-between gap-4 bg-stone-100 p-4 text-left underline-offset-2
              hover:bg-stone-200/75 focus-visible:bg-slate-100/75 focus-visible:underline focus-visible:outline-none"
                        aria-controls="accordionItemOne"
                        @click="isExpanded = ! isExpanded"
                        :class="isExpanded ? 'text-onSurfaceStrong  font-bold'  : 'text-onSurface font-medium'"
                        :aria-expanded="isExpanded ? 'true' : 'false'">
                    @desktop
                    Click
                    @elsedesktop
                    Tap
                    @enddesktop
                    to review your answers
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2"
                         stroke="currentColor" class="size-5 shrink-0 transition"
                         aria-hidden="true" :class="isExpanded  ?  'rotate-180'  :  ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>
                <div x-cloak x-show="isExpanded" id="accordionItemOne" role="region"
                     aria-labelledby="controlsAccordionItemOne" x-collapse>
                    <div class="p-4 text-sm sm:text-base text-pretty">
                        <livewire:edit-answers-form/>
                    </div>
                </div>
            </div>
        </div>
    </x-main-content>
    <script>
        function doSpinner() {
            document.getElementById('spinner').classList.remove('hidden')
            document.getElementById('spinner').classList.add('flex')
            window.location.href = "/finish";
        }

        window.addEventListener('ansUpdated', event => {
            // console.log(event)
            Swal.fire({
                title: event.detail.title,
                icon: "success"
            })
        })
    </script>
</x-app-layout>
