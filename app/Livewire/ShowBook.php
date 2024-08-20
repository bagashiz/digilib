<?php

namespace App\Livewire;

use App\Enums\UserRole;
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
            throw new \Exception('Unauthenticated', 401);
        }

        $book = Book::where('uid', $uid)->firstOrFail();

        if ($book->user_id !== $user->id && $user->role !==  UserRole::ADMIN) {
            throw new \Exception('Can not delete the book of another user', 403);
        }

        if ($book->cover_image && File::exists($book->cover_image)) {
            File::delete($book->cover_image);
        }

        if ($book->pdf_file && File::exists($book->pdf_file)) {
            File::delete($book->pdf_file);
        }

        $book->delete();

        $this->success(
            'Book deleted successfully!',
            redirectTo: '/'
        );
    }

    /**
    * Redirect to home with search category
    *
    * @param string $category
    * @return void
    */
    public function searchCategory(string $category): void
    {
        $this->redirect('/?search=' . $category, navigate: true);
    }

    /**
     * Exception hook
     *
     * @param \Exception $e
     * @param mixed $stopPropagation
     */
    public function exception(\Exception $e, mixed $stopPropagation): void
    {
        $this->error($e->getMessage(), redirectTo: '/');
    }

    /**
     * Runs at the beginning of the first initial request
     *
     * @return void
     */
    public function mount(string $uid): void
    {
        try {
            $user = auth()->user();
            if ($user === null) {
                throw new \Exception('Unauthenticated', 401);
            }

            $this->book = Book::where('uid', $uid)->firstOrFail();
            if ($this->book->user_id !== $user->id && $user->role !== UserRole::ADMIN) {
                throw new \Exception('Can not view the book of another user', 403);
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage(), redirectTo: '/');
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
