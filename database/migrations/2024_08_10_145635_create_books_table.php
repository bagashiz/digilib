<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->ulid()->primary()->default(Str::ulid());
            $table->string('title')->index();
            $table->string('author')->index();
            $table->text('description');
            $table->integer('quantity')->default(0);
            $table->string('cover_image')->nullable();
            $table->string('pdf_file')->nullable();
            $table->timestampsTz();
            $table->unique(['title', 'author']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
