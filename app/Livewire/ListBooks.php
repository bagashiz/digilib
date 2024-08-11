<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use Mary\Traits\Toast;

class ListBooks extends Component
{
    use Toast;

    public string $search = '';

    public bool $drawer = false;

    /** @var array{column: string, direction: string} */
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    /**
     * Delete action
     *
     * @param int $id
     */
    public function delete($id): void
    {
        $this->warning("Will delete #$id", 'It is fake.', position: 'toast-bottom');
    }

    /**
     * Table headers
     *
     * @return array<int, array{key: string, label: string, class?: string, sortable?: bool}>
     */
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-64'],
            ['key' => 'age', 'label' => 'Age', 'class' => 'w-20'],
            ['key' => 'email', 'label' => 'E-mail', 'sortable' => false],
        ];
    }

    /**
     * For demo purpose, this is a static collection.
     *
     * On real projects you do it with Eloquent collections.
     * Please, refer to maryUI docs to see the eloquent examples.
     *
     * @return Collection<int, array{id: int, name: string, email: string, age: int}>
     */
    public function users(): Collection
    {
        return collect([
            ['id' => 1, 'name' => 'Mary', 'email' => 'mary@mary-ui.com', 'age' => 23],
            ['id' => 2, 'name' => 'Giovanna', 'email' => 'giovanna@mary-ui.com', 'age' => 7],
            ['id' => 3, 'name' => 'Marina', 'email' => 'marina@mary-ui.com', 'age' => 5],
        ])
            ->sortBy([[...array_values($this->sortBy)]])
            ->when($this->search, function (Collection $collection) {
                return $collection->filter(fn (array $item) => str($item['name'])->contains($this->search, true));
            });
    }


    /**
     * Render the Livewire component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.list-books', [
            'users' => $this->users(),
            'headers' => $this->headers()
        ]);
    }
}
