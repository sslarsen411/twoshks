<x-app-layout>  
    <x-main-content class="border-2 bg-stone-100" x-data="{shiftPressed: false}"> 
        <div class="flex flex-col items-center justify-center top-0 left-0 ">
            <div class="grid grid-cols-12 gap-0" >
                <div class="col-span-7">
                  <blockquote class="speech bubble">
                    <h2> Well done {{session('cust.first_name')}}!</h2>
                    <p class="mb-2">
                        Here&apos;s your completed review. It&apos;s been copied
                        to your clipboard, ready to paste online.
                    </p>    
                    <p class="mb-8">
                        Feel free to edit this review to personalize it. The changes will saved and copied to your clipboard.
                    </p>                      
                  </blockquote>
                </div>
                <div class="col-span-5"> 
                  <div class="flex">
                    <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-speak.webp')}}" alt="Review Guru  icon">
                  </div>
                </div>
            </div>
        </div>
        <livewire:edit-review :review="$review" />
        <div class="px-4 mt-12">
            <h2>
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/new-guru-256.webp')}}" class="guru-icon" alt="Review Guru icon">
                Next
            </h2>
            <p>          
            @desktop
                Click
            @elsedesktop
                Tap 
            @enddesktop the button below to go directly to Google&apos;s review application to post your review:
            </p>
            <livewire:go-to-google />
            <p>
                Thanks again from <strong>{{session('location.company')}}</strong>, we appreciate your feedback.
            </p>
        </div>
       <div class="px-4">            
            <h2>
                <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/new-guru-256.webp')}}" class="guru-icon" alt="Review Guru icon">
                How to post your review on Google
            </h2>     
            <div class="main__content flex-col border-2 p-2 ">    
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
                        <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/how-to-post.webp')}}" alt="Picture of a phone showing how to paste the review on Google">
                    </div>
                </div>    
                <p class="my-4 self-start">Thanks for using the <span class="font-bold text-twoshk_navy">Two Shakes Review Web App</span>.</p>
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