<x-app-layout>
    <x-main-content>
        <div class="grid grid-cols-12 gap-0">
            <div class="col-span-7">
                <blockquote class="speech bubble">
                    <h2 class="">Thank You</h2>
                    <p>
                        For taking the time to share your feedback.
                    </p>
                </blockquote>
            </div>
            <div class="col-span-5">
                <div class="flex">
                    <picture class="inline-block align-top my-0">
                        <source media="(max-width: 766px)"
                                srcset="{{ asset('images/Guru-neutral.webp') }}">
                        <img src="{{ asset('images/Guru-neutral.webp') }}"
                             alt="The Rave Review Guru" class="sm:w-auto">
                    </picture>
                </div>
            </div>
        </div>
        <div class="w-[90%] mx-auto">
            <p class="mt-4 text-xl">
                Your experience matters to us at [COMPANY]. It helps us understand where we can improve and how we can
                better
                serve you and others in the future.
            </p>
            <p>Your comments will be reviewed with care.</p>
            <p>We appreciate you taking the time to help us do better.</p>
        </div>
    </x-main-content>
</x-app-layout>
