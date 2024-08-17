<div>
    <x-button label="Back" link="/" icon="o-arrow-left-circle" class="btn-ghost btn-sm text-sm mb-4" />
    <section class="flex flex-wrap">
        <div class="w-full sm:w-1/3 justify-center aspect-w-3 aspect-h-2">
            <img src="{{ $book->cover_image ? asset($book->cover_image) : asset('placeholder.svg') }}"
                alt="{{ $book->title }}" class="object-fill" />
        </div>
        <div class="w-full sm:w-2/3">
            <x-card title="{{ $book->title }}" subtitle="{{ $book->author }}" shadow>
                <div class="mb-3">
                    @foreach ($book->categories as $categories)
                        <x-button class="btn-outline btn-sm mt-1"
                            wire:click="searchCategory('{{ $categories->name }}')">
                            {{ $categories->name }}
                        </x-button>
                    @endforeach
                </div>
                <hr>
                <p class="my-3">{{ $book->description }}</p>
                <x-slot:actions>
                    <x-button label="Read" icon="o-book-open" link="{{ asset($book->pdf_file) }}"
                        class="btn-ghost btn-sm text-green-500" external spinner responsive />
                    <x-button label="Edit" icon="o-pencil-square" link="{{ route('books.edit', $book['uid']) }}"
                        class="btn-ghost btn-sm text-primary" spinner responsive />
                    <x-button label="Delete" icon="o-trash" wire:click="delete('{{ $book->uid }}')"
                        class="btn-ghost btn-sm text-red-500" spinner responsive />
                </x-slot:actions>
            </x-card>
        </div>
    </section>
</div>
