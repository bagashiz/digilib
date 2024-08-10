<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    * Many to many relationship with Category
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Category>
    */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
