<x-app-layout>
    @push('styles')
        @vite(['resources/css/rateTW.css'])
    @endpush
    <x-main-content>
        <div class="progress__bar">
            <progress class="progress progress-secondary w-56 mx-auto mb-5" value="5" max="100"></progress>
        </div>
        <div class="grid grid-cols-12 gap-0">
            <div class="col-span-7">
                <blockquote class="speech bubble">
                    <h2 class="">I&apos;m the Review Guru AI</h2>
                    <p>
                        I&apos;ll turn your feedback into an polished review in
                        &ldquo;<strong>Two Shakes</strong> of a lamb&apos;s tail.&rdquo;
                    </p>
                </blockquote>
            </div>
            <div class="col-span-5">
                <div class="flex">
                    <picture class="inline-block align-top my-0">
                        <source media="(max-width: 766px)"
                                srcset="{{ asset('https://cdn.mojoimpact.com/twoshakes/review-guru-speak-xs.png')}}">
                        <source media="(min-width: 768px)"
                                srcset="{{ asset('https://cdn.mojoimpact.com/twoshakes/review-guru-speak-sm.png')}}">
                        <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/review-guru-speak-sm.png')}}"
                             alt="The Rave Review Guru" class="sm:w-auto">
                    </picture>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 grid-rows-1 gap-0 px-4 place-items-center">
            <div class="col-span-2 ">
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/reviewguru-xs.png')}}" class="guru-icon"
                     alt="Review Guru icon">
            </div>
            <div class="col-span-10 w-full">
                <h3>
                    I make reviews <span class="font-semibold italic">fast</span> &amp; <span
                        class="font-semibold">easy</span>!
                </h3>
                <ul class="mb-2 list-decimal list-inside">
                    <li class="ml-2 indent-2">
                        I&apos;ll ask you six questions about your experience at
                        <strong>{{ session('location.company') }}</strong>&hellip;
                    </li>
                    <li class="ml-2 indent-2">
                        Then I&apos;ll turn your answers into a polished review you can post online.
                    </li>
                </ul>
            </div>
            <div class="col-span-10 w-full">
                <h3>
                    I&apos;m Here to Help
                </h3>
                <p>
                    At a loss? Not sure what to say? You can chat with me for help, suggestions, and examples.
                </p>
            </div>
            <div class="col-span-2 ">
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/reviewguru-xs.png')}}" class="guru-icon"
                     alt="Review Guru icon">
            </div>
            <div class="col-span-2 ">
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/reviewguru-xs.png')}}" class="guru-icon"
                     alt="Review Guru icon">
            </div>
            <div class="col-span-10 w-full">
                <h2>
                    We&apos;ll Start with an Overall Rating&hellip;
                </h2>
            </div>
        </div>
        <livewire:overall-rating/>
    </x-main-content>
</x-app-layout>
