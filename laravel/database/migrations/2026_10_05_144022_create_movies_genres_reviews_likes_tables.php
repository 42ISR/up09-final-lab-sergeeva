<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('director');
            $table->integer('year');
            $table->string('country');
            $table->text('description');
            $table->string('posterUrl')->nullable();
            $table->foreignId('createdBy')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('movie_genre', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
            $table->foreignId('genre_id')->constrained('genres')->onDelete('cascade');
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movieId')->constrained('movies')->onDelete('cascade');
            $table->foreignId('userId')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // Оценка от 1 до 10
            $table->string('title');
            $table->text('body');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });

        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewId')->constrained('reviews')->onDelete('cascade');
            $table->foreignId('userId')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['reviewId', 'userId']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('movie_genre');
        Schema::dropIfExists('genres');
        Schema::dropIfExists('movies');
    }
};