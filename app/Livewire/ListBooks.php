<?php

namespace App\Livewire;

use App\Enums\UserRole;
use App\Models\Book;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Mary\Traits\Toast;

class ListBooks extends Component
{
    use Toast;

    #[Url]
    public string $search = '';

    /** @var array{column: string, direction: string} */
    public array $sortBy = ['column' => 'title', 'direction' => 'asc'];

    /**
     * Table headers
     *
     * @return array<int, array{key: string, label: string, class?: string, sortable?: bool}>
     */
    public function headers(): array
    {
        $headers = null;

        $user = auth()->user();
        if (!$user) {
            return [];
        }

        switch ($user->role) {
            case UserRole::ADMIN:
                $headers = [
                    ['key' => 'user', 'label' => 'User', 'class' => 'w-32', 'sortable' => false],
                    ['key' => 'title', 'label' => 'Title', 'class' => 'w-36'],
                    ['key' => 'author', 'label' => 'Author', 'class' => 'w-36'],
                    ['key' => 'categories', 'label' => 'Categories', 'class' => 'w-20', 'sortable' => false],
                    ['key' => 'description', 'label' => 'Description',
                        'class' => 'w-96 hidden lg:table-cell', 'sortable' => false],
                ];
                break;

            case UserRole::MEMBER:
                $headers = [
                    ['key' => 'title', 'label' => 'Title', 'class' => 'w-48'],
                    ['key' => 'author', 'label' => 'Author', 'class' => 'w-36'],
                    ['key' => 'categories', 'label' => 'Categories', 'class' => 'w-20', 'sortable' => false],
                    ['key' => 'description', 'label' => 'Description',
                        'class' => 'w-96 hidden lg:table-cell', 'sortable' => false],
                ];
                break;

            default:
                break;
        }

        if (!$headers) {
            return [];
        }

        return $headers;
    }

    /**
     * Get books
     *
     * @return Collection<int, Book>
     */
    public function books(): Collection
    {
        $books = null;

        $user = auth()->user();
        if (!$user) {
            return collect();
        }

        switch ($user->role) {
            case UserRole::ADMIN:
                $books = Book::with([
                    'categories' => function ($query) {
                        $query->select('name');
                    },
                    'user' => function ($query) {
                        $query->select('id', 'uid');
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
                break;

            case UserRole::MEMBER:
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
                break;

            default:
                break;
        }

        if (!$books) {
            return collect();
        }

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
    * Search based on category
    *
    * @param string $category
    * @return void
    */
    public function searchCategory(string $category): void
    {
        $this->search = $category;
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
            $this->warning(
                'You have been logged out!',
                redirectTo: route('login')
            );
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
