<button 
    {{-- @if($errors->any()) disabled @endif  --}}
    {{ $attributes->merge(['type' => 'button', 
    'class' => 'inline-flex items-center p-4 bg-slate-700 border border-gray-300 rounded-md font-semibold text-xs 
    text-zinc-50 uppercase tracking-widest shadow-sm hover:bg-gray-50 hover:text-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 
    focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot}}
</button>