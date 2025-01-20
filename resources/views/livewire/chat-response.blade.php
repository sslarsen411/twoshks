<div class="space-y-0.5 has-[.stream:empty]:hidden">
    <div class="text-xs">
        <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/reviewguru-xs.webp')}}" class="guru-icon inline-block"
             alt="guru icon">
        Review Guru
    </div>
    <div class="bg-stone-200 text-slate-800 rounded-xl rounded-tl-none px-3 py-1.5 text-sm">
        <div wire:stream="stream-{{ $this->getId()  }}">
            {!! $response !!}
        </div>
    </div>
</div>
