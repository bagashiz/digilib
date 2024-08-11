<div class="flex flex-wrap">
    <div class="w-full sm:w-1/2 p-2">
        <x-card title="Cover" shadow>
            <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="w-full h-auto" />
            <x-slot:actions>
                <x-button icon="o-pencil-square" wire:click="update({{ $book->uid }})" spinner
                    class="btn-ghost btn-sm text-primary" />
                <x-button icon="o-trash" wire:click="delete({{ $book->uid }})" spinner
                    class="btn-ghost btn-sm text-red-500" />
            </x-slot:actions>
        </x-card>
    </div>
    <div class="w-full sm:w-1/2 p-2">
        <x-card title="{{ $book->title }}" subtitle="{{ $book->author }}" shadow separator>
            {{ $book->description }}
        </x-card>
    </div>
</div>
