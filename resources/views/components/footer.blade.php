<footer class="relative top-full w-full mx-auto text-amber-50">
    <div id="copyright" class="text-center mt-4">       
        <div class="text-[.75rem]">
            <form id="frmLogout" class="inline-block" action="{{ url('logout') }}" method="POST">
                @csrf
                <a    onclick="document.getElementById('frmLogout').submit(); ">Logout</a> 
            </form> |
            <button 
                data-dialog-target="modalCookie"
                class="modFooter" type="link">
                Cookies
            </button> | 
            <button 
                data-dialog-target="modalPrivacy"
                class="modFooter" type="link">
                Privacy 
            </button> |
            <button 
                data-dialog-target="modalTOS"
                class="modFooter" type="link">
                Terms of Service
            </button>
        </div>
        <p class="mt-4 text-[.65rem] p-0 leading-tight">
            Another <a href="https://www.mojoimpact.com" class="" target="_blank" rel="noopener">
            <span class="font-semibold">Mojo Impact<span class="trade"></span></span></a> custom web application.<br>
            Design and development by <a href="https://www.mojoimpact.net" class="link link-hover glow"
                target="_blank" rel="noopener">
                <span class="font-semibold ">Mojo Impact Web Development</span></a>
            <br>
            Copyright &copy; 2012 &mdash; {{date('Y')}}
            <span class="font-semibold">Mojo Impact</span>, LLC. All rights
            reserved.
        </p>
    </div>
</footer>    
<div
  data-dialog-backdrop="modalCookie"
  data-dialog-backdrop-close="true"
  class="pointer-events-none fixed inset-0 z-[999] grid h-screen w-screen place-items-center bg-black bg-opacity-60 opacity-0 backdrop-blur-sm transition-opacity duration-300"
>
  <div
    data-dialog="modalCookie"
    class="relative m-4 p-4 w-2/5 min-w-[70%] max-w-[70%] overflow-y-auto h-1/2 overscroll-auto rounded-lg bg-white shadow-sm"
  >
    <x-lgl-cookie />
    <div class="flex shrink-0 flex-wrap items-center pt-4 justify-end">     
        <x-secondary-button  data-dialog-close="true" class="bg-red-700" type="button" >
            Close
            <x-fluentui-share-screen-stop-48-o class="ml-2 w-6 h-6" />
        </x-secondary-button>    
    </div>
  </div>
</div>

<div
  data-dialog-backdrop="modalPrivacy"
  data-dialog-backdrop-close="true"
  class="pointer-events-none fixed inset-0 z-[999] grid h-screen w-screen place-items-center bg-black bg-opacity-60 opacity-0 backdrop-blur-sm transition-opacity duration-300"
>
  <div
    data-dialog="modalPrivacy"
    class="relative m-4 p-4 w-2/5 min-w-[70%] max-w-[70%] overflow-y-auto h-1/2 overscroll-auto rounded-lg bg-white shadow-sm"
  >
    <x-lgl-privacy />
    <div class="flex shrink-0 flex-wrap items-center pt-4 justify-end">
        <x-secondary-button  data-dialog-close="true" class="bg-red-700" type="button" >
            Close
            <x-fluentui-share-screen-stop-48-o class="ml-2 w-6 h-6"/>
        </x-secondary-button>     
    </div>
  </div>
</div>
<div
  data-dialog-backdrop="modalTOS"
  data-dialog-backdrop-close="true"
  class="pointer-events-none fixed inset-0 z-[999] grid h-screen w-screen place-items-center bg-black bg-opacity-60 opacity-0 backdrop-blur-sm transition-opacity duration-300"
>
  <div
    data-dialog="modalTOS"
    class="relative m-4 p-4 w-2/5 min-w-[70%] max-w-[70%] overflow-y-auto h-1/2 overscroll-auto rounded-lg bg-white shadow-sm"
  >
    <x-lgl-tos />
    <div class="flex shrink-0 flex-wrap items-center pt-4 justify-end">
        <x-secondary-button  data-dialog-close="true" class="bg-red-700" type="button" >
            Close
            <x-fluentui-share-screen-stop-48-o class="ml-2 w-6 h-6"/>
        </x-secondary-button>    
    </div>
  </div>
</div>
<script src="https://unpkg.com/@material-tailwind/html@latest/scripts/dialog.js"></script>
