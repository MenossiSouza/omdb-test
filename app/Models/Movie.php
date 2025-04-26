<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ['imdb_id', 'title', 'year', 'director', 'genre'];
    protected $casts = [
        'year' => 'integer',
    ];
}
