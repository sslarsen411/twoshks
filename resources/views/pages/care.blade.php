<x-app-layout>
  <x-main-content>
    <p class="text-lg md:text-xl mb-3">
        It looks like your experience may not have met your expectations. We want to hear about it. 
    </p>    
    <livewire:customer-care-form/>
  </x-main-content>
</x-layout> 
<script>
   window.addEventListener('phError', event => {  
        Swal.fire({
            title: event.detail.title, 
            text: event.detail.text, 
            icon: "error"
        })
      })
</script>