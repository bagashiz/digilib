<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Mary\Traits\Toast;

class ShowBook extends Component
{
    use Toast;

    public Book $book;

    /**
     * Delete book handler
     *
     * @param string $uid
     * @return void
     */
    public function delete(string $uid): void
    {
        $user = auth()->user();
        if (!$user) {
            abort(403);
        }

        $book = Book::where('uid', $uid)->firstOrFail();

        if ($book->user_id !== $user->id) {
            abort(403);
        }

        if ($book->cover_image && File::exists($book->cover_image)) {
            File::delete($book->cover_image);
        }

        if ($book->pdf_file && File::exists($book->pdf_file)) {
            File::delete($book->pdf_file);
        }

        $book->delete();

        $this->success('Book deleted successfully!');
        $this->redirect('/', navigate: true);
    }

    /**
     * Exception hook
     *
     * @param \Exception $e
     * @param mixed $stopPropagation
     */
    public function exception(\Exception $e, mixed $stopPropagation): void
    {
        $this->error('An error occurred while deleting the book.');
        $stopPropagation();
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
