<div>
    <section class="flex flex-wrap">
        <div class="w-full sm:w-1/3 justify-center aspect-w-3 aspect-h-2">
            <img src="{{ $book->cover_image ? asset($book->cover_image) : asset('placeholder.svg') }}"
                alt="{{ $book->title }}" class="object-fill" />
        </div>
        <div class="w-full sm:w-2/3">
            <x-card title="{{ $book->title }}" subtitle="{{ $book->author }}" shadow>
                <div class="mb-5">
                    @foreach ($book->categories as $categories)
                        <x-button class="btn-outline btn-sm">
                            {{ $categories->name }}
                        </x-button>
                    @endforeach
                </div>
                <hr>
                <p class="mt-5">{{ $book->description }}</p>
                <x-slot:actions>
                    <x-button icon="o-book-open" link="{{ asset($book->pdf_file) }}" external spinner
                        class="btn-ghost btn-sm text-green-500" />
                    <x-button icon="o-pencil-square" wire:click="update('{{ $book->uid }}')" spinner
                        class="btn-ghost btn-sm text-primary" />
                    <x-button icon="o-trash" wire:click="delete('{{ $book->uid }}')" spinner
                        class="btn-ghost btn-sm text-red-500" />
                </x-slot:actions>
            </x-card>
        </div>
    </section>
</div>
