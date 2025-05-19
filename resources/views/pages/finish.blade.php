<x-app-layout>
    <x-main-content class="border-2 bg-stone-100" x-data="{shiftPressed: false}">
        <div class="flex flex-col items-center justify-center top-0 left-0 ">
            <div class="grid grid-cols-12 gap-0">
                <div class="col-span-7">
                    <blockquote class="speech bubble">
                        <h2> Well done {{session('cust.first_name')}}!
                        </h2>
                        <p>
                            Here&apos;s your completed review.
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
        </div>
        <div class="grid grid-cols-12 grid-rows-1 gap-0 px-4 place-items-center">
            {{--            <div class="col-span-10 w-full">--}}
            {{--                <p>--}}
            {{--                    It&apos;s been copied to your clipboard, ready to paste online.--}}
            {{--                </p>--}}
            {{--                <p class=" ">--}}
            {{--                    {{session('cust.first_name')}}, feel free to personalize it &mdash; any changes you make are--}}
            {{--                    saved and copied automatically--}}
            {{--                </p>--}}
            {{--            </div>--}}
            {{--            <div class="col-span-2 ">--}}
            {{--                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-lp.webp')}}" class="guru-icon h-24 mr-4"--}}
            {{--                     alt="Review Guru icon">--}}
            {{--            </div>--}}
            <div class="col-span-2 ">
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-rp.webp')}}" class="guru-icon h-24 mr-4"
                     alt="Review Guru icon">
            </div>
            <div class="col-span-10 w-full">
                <h2>
                    Your Review
                </h2>
                <p>
                    It&apos;s been copied to your clipboard, ready to paste online.
                </p>
                <p>
                    {{session('cust.first_name')}}, feel free to personalize it &mdash; any changes you make are
                    saved and copied automatically
                </p>

            </div>
        </div>
        <livewire:edit-review :review="$review"/>
        <div class="grid grid-cols-12 grid-rows-1 gap-0 px-4 place-items-center mt-5">
            <div class="col-span-12 w-full">
                <h2>
                    Next
                </h2>
                <p>
                    @desktop
                    Click
                    @elsedesktop
                    Tap
                    @enddesktop the button below to go directly to Google&apos;s review application to post your review:
                </p>
                <livewire:go-to-google :review="$review" :reply="$reply"/>
                <p>
                    Thanks again from <strong>{{session('location.company')}}</strong>.
                </p>
            </div>
        </div>
        <div class="grid grid-cols-12 grid-rows-1 gap-0 px-4 place-items-center">
            <div class="col-span-12 w-full">
                <h2>
                    How to post your review on Google
                </h2>
                <div id="directions" class="direction grid grid-cols-2 items-center gap-4 px-0">
                    <div>
                        <ol class="list-decimal ml-6">
                            <li>
                                @desktop
                                Click
                                @elsedesktop
                                Tap
                                @enddesktop the button above
                            </li>
                            <li>
                                Enter your star rating
                            </li>
                            <li>
                                Paste your review
                            </li>
                            <li>
                                @desktop
                                Click
                                @elsedesktop
                                Tap
                                @enddesktop
                                Post &mdash; Done!
                            </li>
                        </ol>
                    </div>
                    <div>
                        <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/how-to-post.webp')}}"
                             alt="Picture of a phone showing how to paste the review on Google">
                    </div>
                </div>
                {{--                <livewire:go-to-google/>--}}
                <div class="grid grid-cols-12 grid-rows-1 gap-0 px-4 place-items-center">
                    <div class="col-span-2 ">
                        <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-rp-full.webp')}}"
                             class="guru-icon"
                             alt="Review Guru icon">
                    </div>
                    <div class="col-span-10 w-full">
                        <p>
                            Thanks for using the Two Shakes Review App.
                        </p>
                    </div>
                </div>

            </div>
        </div>
        @push('scripts')
            <script>
                window.addEventListener('copyTextToClipboard', event => {
                    navigator.clipboard.writeText(event.detail[0]['text'])
                        .then(() => {
                            console.log('Copied to clipboard')
                            swal.fire({
                                title: "Your Review has been copied to your clipboard",
                                text: "Just paste and post it",
                                icon: "success"
                            })
                        })
                        .catch(err => {
                            console.error('Failed to copy to clipboard', err);
                        });
                });
            </script>
        @endpush
    </x-main-content>
</x-app-layout>
