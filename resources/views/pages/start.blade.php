<x-app-layout>
    <x-main-content>
        <div class="progress__bar">
            <p class="text-center mb-0">5% Complete</p>
            <progress class="progress progress-secondary w-56 mx-auto mb-5" value="5" max="100"></progress>
        </div>
        <div class="grid grid-cols-12 gap-0">
            <div class="col-span-7">
                <blockquote class="speech bubble">
                    <h2>I&apos;m the Review Guru AI</h2>
                    <p class="text-xl">
                        Let me show you how <span class="fast">fast</span> and
                        <strong>easy</strong> it is to write a review for
                        <strong>{{ session('location.company') }}</strong>!
                    </p>
                </blockquote>
            </div>
            <div class="col-span-5">
                <div class="flex">
                    <picture class="inline-block align-top my-0">
                        <source media="(max-width: 766px)"
                                srcset="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-speak-lp.webp')}}">
                        <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-speak-lp.webp')}}"
                             alt="The Rave Review Guru" class="sm:w-auto">
                    </picture>
                </div>
            </div>
        </div>
        <h2 class="ml-4">
            How Two Shakes Review works&hellip;
        </h2>
        <div class="grid grid-cols-12 grid-rows-1 gap-0 px-4 place-items-center">
            <div class="col-span-2 mr-4 ">
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-rp.webp')}}" class="guru-icon h-24"
                     alt="Review Guru icon">
            </div>
            <div class="col-span-10 w-full">
                <ul class="mb-2 list-none list-inside">
                    <li>
                        First, I&apos;ll ask you <strong>six</strong> quick <strong>questions</strong> about your
                        experience at <strong>{{ session('location.company') }}</strong>.
                    </li>
                    <li>
                        Then, <strong>I&apos;ll</strong> turn your answers into an <strong>polished review</strong>
                        and I&apos;ll show you how to <strong>post</strong> it <strong>online</strong>.
                    </li>
                </ul>
                <p class="mb-5">
                    That&apos;s it! You&apos;ll be done in &ldquo;<strong>Two Shakes</strong> of a lamb&apos;s tail,&rdquo;
                </p>
            </div>
            <div class="col-span-10 w-full">
                <h2>
                    I&apos;ll be your guide&hellip;
                </h2>
                <p class="ml-2">
                    Need help? Not sure what to write? <strong>Chat</strong> with me anytime for help,
                    suggestions, and examples.
                </p>
            </div>
            <div class="col-span-2 ">
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-full.webp')}}" class="guru-icon h-24"
                     alt="Review Guru icon">
            </div>
            <div class="col-span-10 w-full mb-5">
                <h2 class="text-2xl">
                    Let&apos;s get started&hellip;
                </h2>
            </div>
        </div>
        <livewire:overall-rating/>
    </x-main-content>
</x-app-layout>
