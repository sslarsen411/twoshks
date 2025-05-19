<x-app-layout>
    <x-main-content>
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
                                srcset="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-speak-full.webp')}}">
                        <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-speak-full.webp')}}"
                             alt="The Rave Review Guru" class="sm:w-auto">
                    </picture>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 grid-rows-1 gap-0 px-4 place-items-center">
            <div class="col-span-10 w-full">
                <h3>
                    I make reviews <span class="font-semibold italic">fast</span> &amp; <span
                        class="font-semibold">easy</span>!
                </h3>
                <p>
                    I ask six questions about someone&apos;s experience at a business and then I transform the answers
                    into a
                    review that can be posted online.
                </p>
            </div>
            <div class="col-span-2 ">
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-lp.webp' ) }}" class="guru-icon"
                     alt="Review Guru icon">
            </div>
            <div class="col-span-2 mr-4">
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-rp.webp' ) }}" class="guru-icon"
                     alt="Review Guru icon">
            </div>
            <div class="col-span-10 w-full">
                <h3>
                    I&apos;m Here to Help
                </h3>
                <p>
                    Want to know more about me? Go <a href="https://ravereview.guru" target="_blank">here</a>
                </p>
            </div>
        </div>
    </x-main-content>
</x-app-layout>
