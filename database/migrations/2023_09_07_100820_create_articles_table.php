<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('api', 500);
            $table->string('source', 250);
            $table->string('author', 250);
            $table->string('title', 250);
            $table->text('description');
            $table->text('image_url');
            $table->text('article_url');
            $table->timestamp('publishedAt');
            $table->string('category', 100);
            $table->string('lang', 5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
