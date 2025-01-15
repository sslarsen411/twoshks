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
                    <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-speak.webp')}}"
                         alt="Review Guru  icon">
                </div>
            </div>
        </div>
        <h3 class="mt-0">
            <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/new-guru-256.webp')}}" class="guru-icon"
                 alt="Review Guru icon">
            I make reviews <span class="font-semibold italic">fast</span> &amp; <span class="font-semibold">easy</span>!
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
        <h3>
            <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/new-guru-256.webp')}}" class="guru-icon"
                 alt="Review Guru icon">
            I&apos;m Here to Help
        </h3>
        <p class="indent-4">
            You can chat with me for suggestions, and examples.
        </p>
        <h2>
            <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/new-guru-256.webp')}}" class="h-8 w-8 inline-block"
                 alt="guru icon">
            We&apos;ll Start with an Overall Rating&hellip;
        </h2>
        <livewire:overall-rating/>
    </x-main-content>
</x-app-layout>
