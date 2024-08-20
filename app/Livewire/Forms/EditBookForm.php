<?php

namespace App\Livewire\Forms;

use App\Enums\UserRole;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class EditBookForm extends Form
{
    #[Validate('required')]
    public string $title = '';

    #[Validate('required')]
    public string $author = '';

    #[Validate('required')]
    public string $description = '';

    /** @var array<int, int> */
    #[Validate('required|array')]
    public array $categoryIds;

    /** @var Collection<int, Category> */
    #[Validate('required')]
    public Collection $categories;

    #[Validate('nullable|image|max:2048')]
    public ?TemporaryUploadedFile $coverImage = null;

    #[Validate('nullable|file|max:10240')]
    public ?TemporaryUploadedFile $pdfFile = null;

    public bool $isCoverRemoved = false;
    public Book $book;

    /**
    * Fetch book record
    *
    * @param string $uid
    * @throws \Exception
    * @return Book
    */
    public function fetchBook(string $uid): Book
    {
        $book = Book::where('uid', $uid)->first();
        if (!$book) {
            throw new \Exception('Book not found');
        }

        $this->book = $book;
        $this->title = $book->title;
        $this->author = $book->author;
        $this->description = $book->description;
        $this->categoryIds = $book->categories->pluck('id')->toArray();

        return $book;
    }

    /**
    * Get all categories
    *
    * @return void
    */
    public function fetchAllCategories(): void
    {
        try {
            $this->categories = Category::all();
        } catch (\Exception $e) {
            throw new \Exception('An error occurred while fetching categories');
        }
    }

    /**
    * Update book record
     *
     * @param int $userId
     * @throws \Exception
     * @return void
     */
    public function update(int $userId): void
    {
        try {
            $validated = $this->validate();

            if ($this->book == null) {
                throw new \Exception('Book not found');
            }

            if ($this->book->user_id !== $userId && auth()->user()->role !== UserRole::ADMIN) {
                throw new \Exception('Can not update the book of another user');
            }

            if ($this->coverImage) {
                $coverUrl = 'storage/' . $this->coverImage->store(path: 'covers');
                $this->book->cover_image = $coverUrl;
            }

            if ($this->pdfFile) {
                $pdfUrl = 'storage/' . $this->pdfFile->store(path: 'books');
                $this->book->pdf_file = $pdfUrl;
            }

            if ($this->isCoverRemoved) {
                $this->book->cover_image = null;
            }

            Book::where('uid', $this->book->uid)->update([
                'title' => $validated['title'],
                'author' => $validated['author'],
                'description' => $validated['description'],
                'cover_image' => $this->book->cover_image,
                'pdf_file' => $this->book->pdf_file,
            ]);
            $this->book->categories()->sync($validated['categoryIds']);
        } catch (QueryException $e) {
            if (is_array($e->errorInfo) && count($e->errorInfo) > 1) {
                $errorCode = $e->errorInfo[1];
                switch ($errorCode) {
                    case 1062:
                        throw new \Exception('Book already exists');
                    default:
                        throw new \Exception('An error occurred while adding the book');
                }
            }
        }
    }
}
