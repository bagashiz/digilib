<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class ShowBook extends Component
{
    use Toast;

    public Book $book;

    /**
     * Delete book
     *
     * @param string $uid
     */
    public function delete(string $uid): void
    {
        try {
            $user = auth()->user();
            if (!$user) {
                abort(403);
            }

            $book = Book::where('uid', $uid)->firstOrFail();

            if ($book->user_id !== $user->id) {
                abort(403);
            }

            $this->success('Book deleted successfully!');
            $this->redirect('/', navigate: true);
        } catch (\Exception $e) {
            $this->error('An error occurred while deleting the book.');
        }
    }

    /**
     * Runs at the beginning of the first initial request
     *
     * @return void
     */
    public function mount(string $uid): void
    {
        $user = auth()->user();
        if ($user === null) {
            abort(403);
        }

        $this->book = Book::where('uid', $uid)->firstOrFail();
        if ($this->book->user_id !== $user->id) {
            abort(403);
        }
    }

    /**
     * Render the Livewire component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.show-book');
    }
}
