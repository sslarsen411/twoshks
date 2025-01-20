<div>
    <form action="">
        <textarea wire:model.blur="review" id="review" rows="15">{!! $review !!}</textarea>
        <div id="navigation" class="flex justify-end mr-2 my-4">
            <x-secondary-button type="submit">
                Update
            </x-secondary-button>
        </div>
    </form>
</div>
