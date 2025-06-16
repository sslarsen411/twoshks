<x-app-layout>
    <x-main-content class="flex place-content-center border-2">
        <div class="grid grid-cols-12 gap-0">
            <div class="col-span-7">
                <blockquote class="speech bubble">
                    <h2 class="">I'm Sorry</h2>
                    <p>
                        We&apos;re unable to process any reviews for <span
                            class="text-red-800 font-bold">{{session('company')}}</span> right now.
                    </p>
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
        <p class="text-xl mt-2">
            Try back later.
        </p>
    </x-main-content>
</x-app-layout>
