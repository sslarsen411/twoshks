<div class="my-8 w-full flex flex-col place-items-center">
    <a id="goog" type=button class="mt-8 w-52 mx-auto text-center border-2 rounded-md animate-bounce" target="_blank"
       wire:click.prevent="updateAndNotifyReview"
       href="https://search.google.com/local/writereview?placeid={{session('location.PID')}}">
        <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/google-logo.svg')}}"
             class="inline-block h-8 hover:animate-spin" alt="Google aproved logo">
        Post Your Review
    </a>
</div>

