<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['movieId', 'userId', 'rating', 'title', 'body', 'status'];

    public function likes()
    {
        return $this->hasMany(Like::class, 'reviewId');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}