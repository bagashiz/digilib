<?php

namespace App\Livewire\Forms;

use App\Models\Book;
use Illuminate\Database\QueryException;
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

    #[Validate('nullable|image|max:2048')]
    public ?TemporaryUploadedFile $coverImage = null;

    #[Validate('nullable|file')]
    public ?TemporaryUploadedFile $pdfFile = null;


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

            if ($this->coverImage !== null) {
                $cover = $this->coverImage->storePublicly(path: 'covers');
                $coverUrl =  asset('storage/' . $cover);
            }

            if ($this->pdfFile !== null) {
                $pdf = $this->pdfFile->storePublicly(path: 'books');
                $pdfUrl =  asset('storage/' . $pdf);
            }

            Book::create([
                'user_id' => $userId,
                'title' => trim($validated['title']),
                'author' => trim($validated['author']),
                'description' => trim($validated['description']),
                'cover_image' => $coverUrl ?? null,
                'pdf_file' => $pdfUrl ?? null,
            ]);
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
