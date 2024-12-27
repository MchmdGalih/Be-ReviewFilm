<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casts extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'casts';

    protected $fillable = ['name', 'bio', 'age'];

    function list_movie()
    {
        return $this->belongsToMany(Movie::class, 'cast__movies', 'cast_id', 'movie_id');
    }
}
