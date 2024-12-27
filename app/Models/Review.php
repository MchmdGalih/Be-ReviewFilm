<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Review extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'reviews';
    protected $fillable = ['critic', 'point', 'user_id', 'movie_id'];
}
