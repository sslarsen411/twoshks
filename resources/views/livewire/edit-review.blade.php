<div>
    <form action="">
        <textarea  wire:model.blur="review" id="review" rows="15">{!! $review !!}</textarea>
        <x-secondary-button type="button" class="border-0 mt-6 float-right">
            Update
        </x-secondary-button> 
    </form>
</div>