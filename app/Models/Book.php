<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'author',
        'description',
        'quantity',
        'cover_image',
        'pdf_file',
    ];

    /**
    * Many to one relationship with User
    *
    * @return BelongsTo<User, Book>
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
    * Many to many relationship with Category
    *
    * @return BelongsToMany<Category>
    */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
