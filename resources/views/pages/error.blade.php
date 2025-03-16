<x-app-layout>
    <div class="bg-twoshk_navy w-full">
        <div class="px-4 py-8 mx-auto">
            <x-main-content class="text-twoshk_tan">                
                <div class="main__content flex-col items-center border-2 rounded-sm text-gray-800 bg-zinc-50 ">         
                <div class="grid grid-cols-5">
                    <div class="col-span-2">
                        <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/homer-doh.svg')}}" alt="The Review Guru">
                      </div>
                    <div class="px-4 py-1 w-full col-span-3">      
                        <h1 class="text-red-800 text-xl">Oops! Something has gone horribly wrong</h1>
                        <p class="text-base my-5">
                            {{ $error ?? 'An unexpected error occurred while processing your request. Please try again later.' }}
                        </p>
                        <div class="actions">   
                            <x-primary-link href="{{ route('pages.home') }}" class="mt-8 bg-red-900 hover:bg-pink-600 hover:text-yellow-100">
                                Return to Homepage
                            </x-primary-link> 
                        </div>
                    </div>                    
                  </div>
                </div>
            </x-main-content>  
        </div>
    </div>
</x-app-layout>
