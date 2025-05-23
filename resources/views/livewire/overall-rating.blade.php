<div>
    <div class="flex flex-col place-content-center border-2 p-4">
        @if ($rating)
            <p class="text-transparent text-center px-4">
                Thanks!
            </p>
        @else
            <p class="text-center px-4">
                To begin, rate your <strong>overall experience</strong> at {{ session('location.company') }}
            </p>
        @endif
        <div class="rating rating-lg lg:rating-xl rating-half mx-auto" x-data="{ rating: false }">
            <input type="radio" wire:model.live="rating" value="0" class="rating-hidden" checked x-model="rating"/>
            <input type="radio" wire:model.live="rating" value="0.5" class="mask mask-star-2 mask-half-1 star"
                   x-model="rating"/>
            <input type="radio" wire:model.live="rating" value="1" class="mask mask-star-2 mask-half-2 star"
                   x-model="rating"/>
            <input type="radio" wire:model.live="rating" value="1.5" class="mask mask-star-2 mask-half-1 star"
                   x-model="rating"/>
            <input type="radio" wire:model.live="rating" value="2" class="mask mask-star-2 mask-half-2  star"
                   x-model="rating"/>
            <input type="radio" wire:model.live="rating" value="2.5" class="mask mask-star-2 mask-half-1 star"
                   x-model="rating"/>
            <input type="radio" wire:model.live="rating" value="3" class="mask mask-star-2 mask-half-2 star"
                   x-model="rating"/>
            <input type="radio" wire:model.live="rating" value="3.5" class="mask mask-star-2 mask-half-1 star"
                   x-model="rating"/>
            <input type="radio" wire:model.live="rating" value="4" class="mask mask-star-2 mask-half-2  star"
                   x-model="rating"/>
            <input type="radio" wire:model.live="rating" value="4.5" class="mask mask-star-2 mask-half-1 star"
                   x-model="rating"/>
            <input type="radio" wire:model.live="rating" value="5" class="mask mask-star-2 mask-half-2  star"
                   x-model="rating"/>
            <input type="hidden" name="customerID" value='{{session('cust.id')}}'>
            <input type="hidden" name="locID" value='{{session('locID')}}'>
            @error('rating') <span class="error">{{ $message }}</span> @enderror
        </div>
        @if ($rating)
            <p class="text-center mt-5">
                You gave us <strong>{{ $rating }}</strong> {{ Str::plural('star', $rating) }}
            </p>
        @else
            <p class="text-center mt-5">
                Choose from &half; to 5 stars
            </p>
        @endif
    </div>
    <div id="navigation" class="items-end mt-4 mb-2">
        <div id="dynamic_content" class="w-full mx-auto">
            @if ($rating)
                <p class="px-4" wire:transition.in.duration.900ms>
                    <strong>Now</strong>, enter your name and email and we&apos;ll move on to the six questions&hellip;
                </p>
            @endif
        </div>
    </div>
    @if($showInstr)
        <div wire:transition>
            <h2>
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-full.webp')}}"
                     class="guru-icon max-w-12"
                     alt="guru icon">
                How to choose your rating

            </h2>
            <p class="text-base md:text-lg indent-2 mb-0">
                You can select <strong>full</strong> or <strong>half</strong> (<strong>&frac12;</strong>) stars:</p>
            <p class="text-pretty indent-4">
                @desktop
                Click
                @elsedesktop
                Tap
                @enddesktop
                on the <strong>left side</strong> for a <span class="italic underline">half</span> star,
                and on the <strong>right side</strong> for a full star

            </p>
            <div class="flex flex-row justify-center gap-8">
                <picture class="inline-block align-top my-0">
                    <source media="(max-width: 766px)"
                            srcset="{{ asset('https://cdn.mojoimpact.com/twoshakes/star-half-sm.webp')}}">
                    <source media="(min-width: 768px)"
                            srcset="{{ asset('https://cdn.mojoimpact.com/twoshakes/star-half-md.webp')}}">
                    <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/star-half-sm.webp')}}"
                         alt="Mouse pointer on the left half of a star icon" class="sm:w-auto">
                </picture>
                <picture class="inline-block align-top my-0">
                    <source media="(max-width: 766px)"
                            srcset="{{ asset('https://cdn.mojoimpact.com/twoshakes/star-full-sm.webp')}}">
                    <source media="(min-width: 768px)"
                            srcset="{{ asset('https://cdn.mojoimpact.com/twoshakes/star-full-md.webp')}}">
                    <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/star-full-sm.webp')}}"
                         alt="Mouse pointer on the right half of a star icon" class="sm:w-auto">
                </picture>
            </div>
        </div>
    @endif
    @if(! $showInstr)
        <x-form autocomplete="on" action="wire:submitForm" x-data="{ spin: false }"
                class="w-[96%] max-w-lg mx-auto p-4 rounded-lg bg-stone-200"
                wire:transition.duration.900ms>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                           for="grid-first-name">
                        First Name
                    </label>
                    <input wire:model.change="first_name"
                           class="appearance-none block w-full  text-gray-700 border border-gray-200
                            @error('first_name')  error @enderror
                            rounded py-3 px-4  leading-tight focus:outline-hidden focus:bg-white"
                           id="first_name" type="text"
                           value="{{ old('first_name') }}">
                    @error('first_name') <span class="error text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                           for="grid-last-name">
                        Last Name
                    </label>
                    <input
                        class="appearance-none block w-full  text-gray-700 border border-gray-200 rounded py-3
                            @error('last_name') error @enderror
                            px-4 leading-tight focus:outline-hidden focus:bg-white focus:border-gray-500 "
                        id="last_name" wire:model.change="last_name" type="text"
                        value="{{ old('last_name') }}">
                    @error('last_name') <span class="error text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex flex-wrap -mx-3">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                           for="grid-password">
                        Email
                    </label>
                    <input
                        class="appearance-none block w-full  text-gray-700 border border-gray-200 rounded py-3
                            @error('email') error  @enderror
                            px-4  leading-tight focus:outline-hidden focus:bg-white focus:border-gray-500"
                        id="email" wire:model.change="email" type="email">
                    @error('email') <span class="text-xs error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex place-content-end">
                <x-secondary-button type="submit" x-on:click="spin = ! spin"
                                    class="mt-5 float-right animate-bounce">
                    Sign In
                    <x-fluentui-arrow-enter-20-o class="w-6 h-6"/>
                </x-secondary-button>
            </div>
            <x-spinner/>
        </x-form>
        <p class="self-start mt-12 px-4">Or sign in with your Google account</p>
        <div class="flex flex-col place-center">
            <a type="button" href="{{ url('auth/google/') }}"
               class="w-3/4 md:w-1/2 mx-auto mt-0 mb-4 ring-2 bg-gray-100 text-blue-900! text-center text-sm md:text-xl rounded-lg hover:bg-zinc-100 hover:text-teal-400!">
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/google-logo.svg')}}"
                     class="inline-block h-8 hover:animate-spin" alt="Google approved logo">Sign in with Google
            </a>
        </div>
    @endif
</div>
