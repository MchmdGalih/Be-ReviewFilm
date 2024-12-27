<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'movies';
    protected $fillable = ['title', 'summary', 'poster', 'genre_id', 'year'];

    public function genre()
    {
        return $this->belongsTo(Genres::class, 'genre_id');
    }

    function list_review()
    {
        return $this->hasMany(Review::class, 'movie_id');
    }

    function list_cast()
    {
        return $this->belongsToMany(Casts::class, 'cast__movies', 'movie_id', 'cast_id');
    }
}
