<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
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
            ['key' => 'title', 'label' => 'Title', 'class' => 'w-48'],
            ['key' => 'author', 'label' => 'Author', 'class' => 'w-36'],
            ['key' => 'categories', 'label' => 'Categories', 'class' => 'w-20', 'sortable' => false],
            ['key' => 'description', 'label' => 'Description',
                'class' => 'w-96 hidden lg:table-cell', 'sortable' => false],
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
            ->with(['categories' => function ($query) {
                $query->select('name');
            }])
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function ($query) {
                return $query->where('title', 'like', "%{$this->search}%")
                    ->orWhere('author', 'like', "%{$this->search}%")
                    ->orWhereHas('categories', function ($query) {
                        $query->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->get();

        $books = $books->map(function ($book) {
            $words = explode(' ', $book->description);
            if (count($words) > 10) {
                $book->description = implode(' ', array_slice($words, 0, 10)) . '...';
            }
            return $book;
        });

        return $books;
    }

    /**
    * Logout when the user clicks the logout button.
    *
    * @return void
    */
    #[On('logout')]
    public function logout(): void
    {
        if (auth()->check()) {
            auth()->logout();
            $this->warning('You have been logged out!');
            $this->redirect('/login', navigate: true);
        }
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
