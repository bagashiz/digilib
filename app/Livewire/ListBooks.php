<?php

namespace App\Livewire;

use App\Models\Book;
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
    public array $sortBy = ['column' => 'title', 'direction' => 'asc'];

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
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
            ['key' => 'title', 'label' => 'Title', 'class' => 'w-48'],
            ['key' => 'author', 'label' => 'Author', 'class' => 'w-36'],
            ['key' => 'description', 'label' => 'Description', 'class' => 'w-96', 'sortable' => false],
            ['key' => 'quantity', 'label' => 'qty', 'class' => 'w-1', 'sortable' => false],
        ];
    }

    /**
     * Get books
     *
     * @return Collection<int, Book>
     */
    public function books(): Collection
    {
        $user = auth()->user();
        if (!$user) {
            return collect();
        }

        $books = Book::where('user_id', $user->id)
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function ($query) {
                return $query->where('title', 'like', "%{$this->search}%")
                    ->orWhere('author', 'like', "%{$this->search}%");
            })
            ->get();

        $books = $books->map(function ($book) {
            $words = explode(' ', $book->description);
            if (count($words) > 20) {
                $book->description = implode(' ', array_slice($words, 0, 20)) . '...';
            }
            return $book;
        });

        return $books;
    }


    /**
     * Render the Livewire component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.list-books', [
            'books' => $this->books(),
            'headers' => $this->headers()
        ]);
    }
}
