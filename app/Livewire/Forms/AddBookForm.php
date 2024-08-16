<?php

namespace App\Livewire\Forms;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class AddBookForm extends Form
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

    #[Validate('required|file|max:10240')]
    public TemporaryUploadedFile $pdfFile;

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
     * Create a new book record
     *
     * @param int $userId
     * @throws \Exception
     * @return void
     */
    public function create(int $userId): void
    {
        try {
            $validated = $this->validate();

            $coverUrl = $this->coverImage ? 'storage/' . $this->coverImage->store(path: 'covers') : null;
            $pdfUrl =  'storage/' . $this->pdfFile->store(path: 'books');

            $book = Book::create([
                'user_id' => $userId,
                'title' => trim($validated['title']),
                'author' => trim($validated['author']),
                'description' => trim($validated['description']),
                'cover_image' => $coverUrl,
                'pdf_file' => $pdfUrl,
            ]);
            $book->categories()->attach($validated['categoryIds']);
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
