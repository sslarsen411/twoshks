<x-app-layout> 
    <x-main-content class="flex place-content-center border-2">   
        <h1 class="text-4xl text-red-800">Sorry!</h1>
        <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/forbidden-icon.svg')}}" alt="error icon image">
        <p class="text-2xl">
            We&apos;re unable to process any reviews  for <span class="text-red-800 font-bold">{{session('company')}}</span> right now.
        </p>
        <p class="text-xl mt-2">
            Try back later.
        </p>
    </x-main-content>  
</x-app-layout>