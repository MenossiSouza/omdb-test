<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Movie;

class MovieApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_movies_endpoint_returns_data()
    {
        Movie::create([
            'imdb_id' => 'tt123456',
            'title' => 'Batman Begins',
            'year' => '2005',
            'director' => 'Christopher Nolan',
            'genre' => 'Action',
        ]);

        $response = $this->getJson('/api/movies?title=batman');

        $response->assertStatus(200)->assertJsonFragment([
            'title' => 'Batman Begins'
        ]);
    }
}
