<div>
    <!-- HEADER -->
    <x-header title="Your Books" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$books" :sort-by="$sortBy">
            @scope('cell_categories', $book)
                @foreach ($book->categories as $category)
                    <div class="flex items-center justify-center">
                        <x-button wire:click="searchCategory('{{ $category->name }}')" label="{{ $category->name }}"
                            class="btn-primary btn-xs text-xs mb-1" />
                    </div>
                @endforeach
            @endscope
            @scope('actions', $book)
                <x-button icon="o-eye" link="{{ route('books.show', $book['uid']) }}"
                    class="btn-ghost btn-sm text-green-500" />
            @endscope
            <x-slot:empty>
                <x-icon name="o-cube" label="Nothing to see here...." />
            </x-slot:empty>
        </x-table>
    </x-card>

</div>
