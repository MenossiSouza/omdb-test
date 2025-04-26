<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Movie;

class MovieTest extends TestCase
{
    public function test_can_create_movie_instance()
    {
        $movie = new Movie([
            'imdb_id' => 'tt123456',
            'title' => 'The Dark Knight',
            'year' => '2008',
            'director' => 'Christopher Nolan',
            'genre' => 'Action'
        ]);

        $this->assertEquals('The Dark Knight', $movie->title);
    }
}
