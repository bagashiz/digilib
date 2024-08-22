<?php

namespace App\Exports;

use App\Enums\UserRole;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksDataExport implements FromCollection, WithHeadings
{
    use Exportable;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
    * Headers for excel data.
    *
    * @return array<int, string>
    */
    public function headings(): array
    {
        $headers = array();

        switch ($this->user->role) {
            case UserRole::ADMIN:
                $headers = [
                    'user_uid',
                    'user_email',
                    'title',
                    'author',
                    'categories',
                    'description',
                    'created_at',
                ];
                break;

            case UserRole::MEMBER:
                $headers = [
                    'title',
                    'author',
                    'categories',
                    'description',
                    'created_at',
                ];
                break;

            default:
                break;
        }

        return $headers;
    }

    /**
    * Export data in collection to excel.
    *
    * @return Collection<int, Book>
    */
    public function collection(): Collection
    {
        try {
            $books = collect();

            switch ($this->user->role) {
                case UserRole::ADMIN:
                    $books = Book::with(['user', 'categories'])
                            ->select('books.*')->get()->map(function ($book) {
                                return [
                                    'user_uid' => $book->user->uid,
                                    'user_email' => $book->user->email,
                                    'title' => $book->title,
                                    'author' => $book->author,
                                    'categories' => $book->categories->pluck('name')->toArray(),
                                    'description' => $book->description,
                                    'created_at' => $book->created_at,
                                ];
                            });
                    break;

                case UserRole::MEMBER:
                    $books = Book::where('user_id', $this->user->id)
                            ->with(['user', 'categories'])
                                ->select('books.*')->get()->map(function ($book) {
                                    return [
                                        'title' => $book->title,
                                        'author' => $book->author,
                                        'categories' => $book->categories->pluck('name')->toArray(),
                                        'description' => $book->description,
                                        'created_at' => $book->created_at,
                                    ];
                                });
                    break;

                default:
                    break;
            }

            return $books;
        } catch (\Exception $e) {
            throw new \Exception("Failed to export data.");
        }
    }
}
