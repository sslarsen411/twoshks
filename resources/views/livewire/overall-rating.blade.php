
<div>
    <div class="flex flex-col place-content-center border-2 p-4">    
      @if ($rating)
        <p >
            Thanks! You gave us <strong>{{ $rating }}</strong> {{ Str::plural('star', $rating) }} 
        </p>   
      @else                 
        <p class="self-start px-4"> 
          From &half; to 5 stars, rate your overall experience at {{ session('location.company') }}
        </p>                
      @endif            
        <div class="rating rating-lg lg:rating-xl rating-half mx-auto"  x-data="{ rating: false }">
            <input type="radio" wire:model.live="rating" value="0" class="rating-hidden" checked star x-model="rating" />
            <input type="radio" wire:model.live="rating" value="0.5"  class="mask mask-star-2 mask-half-1 star" x-model="rating" />
            <input type="radio" wire:model.live="rating" value="1" class="mask mask-star-2 mask-half-2 star" x-model="rating" />
            <input type="radio" wire:model.live="rating" value="1.5" class="mask mask-star-2 mask-half-1 star" x-model="rating" />
            <input type="radio" wire:model.live="rating" value="2" class="mask mask-star-2 mask-half-2  star" x-model="rating" />
            <input type="radio" wire:model.live="rating" value="2.5" class="mask mask-star-2 mask-half-1 star" x-model="rating" />
            <input type="radio" wire:model.live="rating" value="3" class="mask mask-star-2 mask-half-2 star" x-model="rating" />
            <input type="radio" wire:model.live="rating" value="3.5" class="mask mask-star-2 mask-half-1 star" x-model="rating" />
            <input type="radio" wire:model.live="rating" value="4" class="mask mask-star-2 mask-half-2  star" x-model="rating" />
            <input type="radio" wire:model.live="rating" value="4.5" class="mask mask-star-2 mask-half-1 star" x-model="rating" />
            <input type="radio" wire:model.live="rating" value="5" class="mask mask-star-2 mask-half-2  star" x-model="rating" />               
            <input type="hidden" name="customerID" value='{{session('cust.id')}}'>
            <input type="hidden" name="locID" value='{{session('locID')}}'>       
            @error('rating') <span class="error">{{ $message }}</span> @enderror           
        </div>  
    </div>
    <div id="navigation" class="items-end mt-4 mb-2">       
        <div id="dynamic_content" class="w-full mx-auto" >            
        @if ($rating)                           
            <p class="px-4"  wire:transition.in.duration.900ms>
                <strong>Now</strong>, sign in with your name and email and we&apos;ll move on to the questions&hellip;
            </p>                
        @endif            
        </div>
    </div>
    @if($showInstr)
    <div wire:transition>    
      {{-- <div class="main__content flex-col items-start border-0">      --}}
        <h2>
          <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/new-guru-256.webp')}}" class="guru-icon" alt="guru icon">
          How to choose a star
        </h2>
        <p class="text-base md:text-lg indent-2 mb-0">You can select <strong>full</strong> or <strong>half</strong> (<strong>&frac12;</strong>) stars:</p>
        <p class="text-balance indent-4">
          @desktop
            Click
          @elsedesktop
            Tap 
          @enddesktop
          on the <strong>left side</strong> of a star for a <span class="italic underline">half</span> star, 
          <picture class="inline-block align-top my-0"> 
              <source media="(max-width: 766px)" srcset="{{ asset('https://cdn.mojoimpact.com/twoshakes/star-half-sm.webp')}}">
              <source media="(min-width: 768px)" srcset="{{ asset('https://cdn.mojoimpact.com/twoshakes/star-half-md.webp')}}">
              <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/star-half-sm.webp')}}" alt="Mouse pointer on the left half of a star icon" class="sm:w-auto"> 
          </picture> 
          <span class="ml-2">and</span> on the <strong>right side</strong> for a full star 
          <picture class="inline-block align-top my-0"> 
            <source media="(max-width: 766px)" srcset="{{ asset('https://cdn.mojoimpact.com/twoshakes/star-full-sm.webp')}}">
            <source media="(min-width: 768px)" srcset="{{ asset('https://cdn.mojoimpact.com/twoshakes/star-full-md.webp')}}">
            <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/star-full-sm.webp')}}" alt="Mouse pointer on the right half of a star icon" class="sm:w-auto"> 
          </picture>
        </p>
      {{-- </div> --}}
    </div>
    @endif
    @if(! $showInstr)
      <x-form class="w-[96%] max-w-lg mx-auto p-4 rounded-lg bg-stone-200" autocomplete="on" action="wire:submitForm" wire:transition.duration.900ms>          
        <div class="flex flex-wrap -mx-3 mb-6">
          <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
              First Name
            </label>
            <input class="appearance-none block w-full  text-gray-700 border border-gray-200 @error('first_name') border-red-500 bg-pink-100 @enderror rounded py-3 px-4  leading-tight focus:outline-none focus:bg-white" 
            id="first_name" wire:model.blur="first_name" type="text" value="{{ old('first_name') }}">
            @error('first_name') <span class="error text-xs">{{ $message }}</span> @enderror
          </div>
          <div class="w-full md:w-1/2 px-3">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
              Last Name
            </label>
            <input class="appearance-none block w-full  text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 @error('last_name') border-red-500 bg-pink-100 @enderror" 
            id="last_name" wire:model.blur="last_name" type="text" value="{{ old('last_name') }}">
          @error('last_name') <span class="error text-xs">{{ $message }}</span> @enderror
          </div>
        </div>
        <div class="flex flex-wrap -mx-3">
          <div class="w-full px-3">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-password">
              Email
            </label>
            <input class="appearance-none block w-full  text-gray-700 border border-gray-200 rounded py-3 px-4  leading-tight focus:outline-none focus:bg-white focus:border-gray-500 @error('email') border-red-500 bg-pink-100 @enderror" 
            id="email" wire:model.blur="email" type="email">
            @error('email') <span class="text-xs error">{{ $message }}</span> @enderror                
          </div>
        </div>  
        <x-secondary-button type="submit"  class="mt-5 float-right animate-bounce">
          Sign In
          <x-fluentui-arrow-enter-20-o class="w-6 h-6" />
        </x-secondary-button>   
      </x-form>
      {{-- @if($rating >= 3 )     --}}
        <p class="self-start mt-12 px-4">Or sign in with your Google account</p>
        <div class="flex flex-col place-center">
          <a type="button" href="{{ url('auth/google/') }}" class="w-3/4 md:w-1/2 mx-auto mt-0 mb-4 ring-2 bg-gray-100 !text-blue-900 text-center text-sm md:text-xl rounded-lg hover:bg-zinc-100 hover:!text-teal-400">
              <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/google-logo.svg')}}" class="inline-block h-8 hover:animate-spin" alt="Google aproved logo">Sign in with Google
          </a>   
        </div>      
      {{-- @endif --}}
    @endif
  </div>