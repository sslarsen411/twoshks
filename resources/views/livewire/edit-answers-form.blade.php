<div>
    <h3 class="my-2">To edit your answers, type then press the update button</h3>    
    <table class="w-full border-2 text-sm text-left text-gray-500">      
        @foreach ( $answers  as $key => $answer)  
            <tr>
                <td class="w-12 text-center text-xl bg-slate-900 text-yellow-200 border-b  border-yellow-100" >
                    {{$key + 1 }}
                </td>
                <td class="pl-2 w-[80%] border-2">                    
                    <input  type="textbox" row="3" value="{{$answer}}" class="textarea text-wrap w-full bg-zinc-50" wire:key.live="$key" wire:model.live="answers.{{ $key }}" />               
                </td>
                <td class="text-right align-middle border-2" >                    
                    <x-form action="/doAnEdit" >                    
                        <button class="text-white bg-slate-700 hover:bg-teal-500 p-2 md:px-4 rounded-md inline-block mx-auto text-xs md:text-sm w-auto">Update</button>
                    </x-form>
                </td>
            </tr>
            @endforeach
    </table>
</div>