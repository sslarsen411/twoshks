<div>
    <div class="progress__bar">
        <p class="text-center mb-0">{{$progress}}% Complete</p>
        <progress class="progress progress-secondary w-56 mx-auto" value="{{$progress}}" max="100"></progress>
    </div>
    <div class="grid grid-cols-12 gap-0">
        <div class="col-span-7">
            <blockquote class="speech bubble">
                <h2 class="">Question {{ $questionNumber }}</h2>
                <p>
                    {{ $question  }}
                </p>
            </blockquote>
        </div>
        <div class="col-span-5">
            <div class="flex">
                <picture class="inline-block align-top my-0">
                    <source media="(max-width: 766px)"
                            srcset="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-speak-' . $random . '.webp') }}">
                    <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-speak-' . $random . '.webp')}}"
                         alt="The Rave Review Guru" class="sm:w-auto">
                </picture>
            </div>
        </div>
    </div>
    <div>
        <x-form handle="handleFormSubmission" class="w-full">
            <div class="flex place-content-center flex-col p-4 border-2">

                {{-- <textarea rows="4" class="textarea-lg"  wire:model.debounce.250ms="answer" id="answer" ></textarea> --}}
                <textarea rows="4" wire:model="answer" id="answer"
                          onfocus="this.placeholder = ''"
                          onblur="this.placeholder = 'Type your answer here'"
                          placeholder="Type your answer here."
                ></textarea>
                @error('answer') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div id="navigation" class="flex justify-end mr-2 my-4">
                <x-secondary-button type="submit">
                    Next
                    <x-fluentui-arrow-join-20-o class="w-6 h-6"/>
                </x-secondary-button>
            </div>
        </x-form>
    </div>
    <div>
        <h2 class="self-start">
            <img src="{{ asset('https://cdn.mojoimpact.com/twoshakes/guru-rp.webp')}}"
                 class="guru-icon h-12 w-auto"
                 alt="guru icon">
            Need help? Ask the Review Guru
        </h2>
        <form wire:submit="handleHelp" class="bg-slate-100 w-full p-3 border-t border-slate-200 flex space-x-2">
            <div
                class="bg-white rounded-full grow relative flex items-center h-10 focus-within:ring-2 ring-inset ring-blue-500">
                <input wire:model="ask" class="bg-transparent rounded-full grow px-4 py-2 text-sm h-10 focus:outline-0"
                       placeholder="I'm not sure what to say..."/>
                <button
                    class="bg-slate-800 text-slate-50 rounded-3xl text-sm font-medium size-10 flex items-center justify-center">
                    <x-fluentui-send-20-o class="w-6 h-6"/>
                </button>
            </div>
        </form>
        <div
            class="h-[25vh] w-full mb-5 border rounded-lg bg-linear-to-t from-slate-100 p-6 flex space-y-1.5 overflow-scroll flex-col-reverse">
            <div class="flex flex-col">
                @foreach($aiMessages as $index => $msg)
                    @if ($msg['role'] === 'user')
                        <div class="w-3/4 space-y-0.5 self-end">
                            <div class="text-xs text-right flex flex-row justify-end">
                                <x-fluentui-person-star-48-o class="w-6 h-6"/>
                                {{ session('cust.first_name') }}
                            </div>
                            <div class="bg-slate-700 text-slate-50 rounded-xl rounded-tr-none px-3 py-1.5 text-sm">
                                {{ $msg['content'] }}
                            </div>
                        </div>
                    @endif
                    @if ($msg['role'] === 'assistant')
                        <livewire:chat-response :key="$index" :question="$question"
                                                :helpText="$aiMessages[$index - 1]"/>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
