<x-app-layout>
    <x-main-content>
        <div class="progress__bar">
            <progress class="progress progress-secondary w-56 mx-auto mb-5" value="5" max="100"></progress>
        </div>
        <div class="grid grid-cols-12 gap-0">
            <div class="col-span-7">
                <blockquote class="speech bubble">
                    <h2>I&apos;m the Review Guru AI</h2>
                    <p class="text-xl text-pretty">
                        I make writing a review
                        <span class="font-semibold italic">fast</span>, <span
                            class="font-bold text-emerald-800">easy</span>
                        and <span class="font-semibold underline">fun</span>!
                    </p>
                    <div class="grid grid-cols-6">
                        <div class="flex place-items-center justify-end">
                            <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/tslamb-128.webp')}}"
                                 class="h-8 lg:h-16 w-auto hidden sm:inline" alt="logo">
                        </div>
                        <div class="col-span-5">
                            <p class="mb-5">
                                You&apos;ll be done in &ldquo;<strong>Two Shakes</strong> of a lamb&apos;s tail,&rdquo;
                            </p>
                        </div>
                    </div>
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
        <h2 class="ml-4">
            How it works
        </h2>
        <div class="grid grid-cols-12 grid-rows-1 gap-0 px-4 place-items-center">
            <div class="col-span-2 ">
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/reviewguru-xs.png')}}" class="guru-icon"
                     alt="Review Guru icon">
            </div>
            <div class="col-span-10 w-full">
                <ul class="mb-2 list-decimal list-inside">
                    <p class="">
                        First, I&apos;ll ask you <strong>six questions</strong> about your experience at
                        <strong>{{ session('location.company') }}</strong>.
                    </p>
                    <p class="">
                        Then, I&apos;ll turn your feedback into an <strong>polished review</strong>.
                    </p>
                    <p>
                        Lastly, I&apos;ll show you exactly how to <strong>post your</strong> newly minted review
                        <strong>online</strong>.
                    </p>
                </ul>
            </div>
            <div class="col-span-10 w-full">
                <h2>
                    I&apos;ve got your back
                </h2>
                <p>
                    Need help? Not sure what to say? You can chat with me for help, suggestions, and examples.
                </p>
            </div>
            <div class="col-span-2 ">
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/reviewguru-xs.png')}}" class="guru-icon"
                     alt="Review Guru icon">
            </div>
            <div class="col-span-10 w-full">
                <h2 class="text-3xl">
                    Start
                </h2>
            </div>
        </div>
        <livewire:overall-rating/>
    </x-main-content>
</x-app-layout>
