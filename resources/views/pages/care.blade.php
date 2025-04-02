<x-app-layout>
    <x-main-content>
        @php
            ray(session()->all());
        @endphp
        <p class="text-lg md:text-xl mb-3">
            It looks like your experience did not meet your expectations. This is not the level of service we
            strive for, and we want to make it right.
        </p>
        <livewire:customer-care-form/>
    </x-main-content>
</x-app-layout>
