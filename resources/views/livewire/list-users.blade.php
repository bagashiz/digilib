<div>
    <!-- HEADER -->
    <x-header title="Users" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy">
            @scope('actions', $user)
                <x-button label="Delete" icon="o-trash" wire:click="delete('{{ $user->uid }}')"
                    class="btn-ghost btn-sm text-red-500" spinner responsive />
            @endscope
            <x-slot:empty>
                <x-icon name="o-cube" label="Nothing to see here...." />
            </x-slot:empty>
        </x-table>
    </x-card>

</div>
