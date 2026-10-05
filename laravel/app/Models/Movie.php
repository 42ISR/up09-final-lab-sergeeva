<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ['title', 'director', 'year', 'country', 'description', 'posterUrl', 'createdBy'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'movieId');
    }
}