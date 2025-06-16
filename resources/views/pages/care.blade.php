<x-app-layout>
    <x-main-content>
        <div class="grid grid-cols-12 gap-0">
            <div class="col-span-7">
                <blockquote class="speech bubble">
                    <div class="flex flex-col items-center">
                        <p class="mr-4">
                            It looks like your experience did not meet your expectations.
                        </p>
                        <p class=" mr-4">
                            This is not the level of service
                            <span class="font-bold">{{ session('location.company') }}</span>
                            strives for, and we want to make it right.
                        </p>
                    </div>
                </blockquote>
            </div>
            <div class="col-span-5">
                <div class="flex">
                    <picture class="inline-block align-top my-0">
                        <source media="(max-width: 766px)"
                                srcset="{{ asset('images/Guru-neutral-lf.webp') }}">
                        <img src="{{ asset('images/Guru-neutral-lf.webp') }}"
                             alt="The Rave Review Guru" class="sm:w-auto">
                    </picture>
                </div>
            </div>
        </div>
        <livewire:customer-care-form/>
    </x-main-content>
</x-app-layout>
