<x-app-layout>   
    <x-main-content>
        <div class="grid grid-cols-5 gap-0">
          <div class="col-span-3">
            <blockquote class="speech bubble bg-bubble ">
              <h2 class="pt-4 -ml-2 text-center mb-5">I&apos;m the Review Guru AI</h2>
              <p class="text-base md:text-lg -ml-.5 w-3/4 mx-auto  "> 
                  I  turn feedback into a review in
                  &ldquo;<strong>Two Shakes</strong> of a lamb&apos;s tail.&rdquo;        
              </p>
            </blockquote>
          </div>
          <div class="col-span-2"> 
            <div class="flex">
              <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/new-guru-256.webp')}}" alt="Review Guru  icon">
            </div>


          </div>
        </div>
        {{-- <div class="flex flex-row item-center justify-between">           
         <div class="flex flex-col">
              <blockquote class="speech bubble ">
                <h2 class="pl-8 pt-4 text-center ">I&apos;m the Review Guru AI</h2>
                <p class="text-base md:text-lg w-[80%] mx-auto text-justify "> 
                    I  turn feedback into an engaging review in
                    &ldquo;<strong>Two Shakes</strong> of a lamb&apos;s tail.&rdquo;        
                </p>
              </blockquote>
         </div>
          <div class="flex">
            <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/new-guru-256.webp')}}" alt="Review Guru  icon">
          </div>
    
      </div>
        <h2>I&apos;m the Review Guru AI</h2>
        <p class="text-base md:text-lg"> 
            I  turn feedback into an engaging review in
            &ldquo;<strong>Two Shakes</strong> of a lamb&apos;s tail.&rdquo;        
        </p> --}}
        <h3>
            <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/new-guru-256.webp')}}" class="guru-icon" alt="Review Guru  icon">
            I make the review process <span class="font-semibold italic">fast</span> &amp; <strong>easy</strong>! 
          </h3> 
          <p class="ml-2 indent-2">
            I ask six questions about someone&apos;s experience at a business and then I transform the answers into a polished 
            review that can be posted online.
          </p> 
          <h3>
            <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/new-guru-256.webp')}}" class="guru-icon" alt="Review Guru icon">
            I&apos;m Here to Help 
          </h3> 
          <p>
           As an AI, users can chat with me during the process for help, suggestions, and examples.
          </p>
          <p>
            To learn more, contact me at TheGuru(at)raveriew.guru
          </p>
    </x-main-content>
</x-app-layout>
