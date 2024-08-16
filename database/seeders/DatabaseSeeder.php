<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $categories = [
            ['name' => 'Fiction'],
            ['name' => 'Non-fiction'],
            ['name' => 'Science'],
            ['name' => 'History'],
            ['name' => 'Biography'],
            ['name' => 'Fantasy'],
            ['name' => 'Romance'],
            ['name' => 'Mystery'],
            ['name' => 'Thriller'],
            ['name' => 'Young Adult'],
            ['name' => 'Children'],
            ['name' => 'Graphic Novel'],
            ['name' => 'Horror'],
            ['name' => 'Self-help'],
            ['name' => 'Travel'],
            ['name' => 'Health'],
            ['name' => 'Guide'],
            ['name' => 'Religion'],
            ['name' => 'Science Fiction'],
            ['name' => 'Math'],
            ['name' => 'Art'],
            ['name' => 'Cookbook'],
            ['name' => 'Diary'],
            ['name' => 'Journal'],
            ['name' => 'Series'],
            ['name' => 'Trilogy'],
            ['name' => 'Drama'],
            ['name' => 'Poetry'],
        ];

        Category::insert($categories);
    }
}
