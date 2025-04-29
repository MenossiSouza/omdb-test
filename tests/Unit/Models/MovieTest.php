<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Movie;

class MovieTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_can_create_a_movie_in_database()
    {
        $movie = Movie::create([
            'imdb_id' => 'tt1234567',
            'title' => 'Spider-Man',
            'year' => 2002,
            'director' => 'Sam Raimi',
            'genre' => 'Action',
        ]);

        $this->assertInstanceOf(Movie::class, $movie);
        $this->assertEquals('Spider-Man', $movie->title);
        $this->assertEquals(2002, $movie->year);
    }

    public function test_can_find_movies_by_title()
    {
        Movie::create([
            'imdb_id' => 'tt1234567',
            'title' => 'Spider-Man',
            'year' => 2002,
            'director' => 'Sam Raimi',
            'genre' => 'Action',
        ]);

        $response = $this->getJson('api/movies?title=Spider', [
            'Authorization' => 'Bearer ' . env('API_SECRET')
        ]);

        $response->assertOk()
                ->assertJsonFragment(['title' => 'Spider-Man']);
    }

    public function test_can_find_movies_by_director()
    {
        Movie::create([
            'imdb_id' => 'tt1234567',
            'title' => 'Spider-Man',
            'year' => 2002,
            'director' => 'Sam Raimi',
            'genre' => 'Action',
        ]);

        $response = $this->getJson('api/movies?title=Spider', [
            'Authorization' => 'Bearer ' . env('API_SECRET')
        ]);

        $response->assertOk()
                ->assertJsonFragment(['director' => 'Sam Raimi']);
    }

    public function test_can_find_movies_by_year()
    {
        Movie::create([
            'imdb_id' => 'tt1234567',
            'title' => 'Spider-Man',
            'year' => 2002,
            'director' => 'Sam Raimi',
            'genre' => 'Action',
        ]);

        $response = $this->getJson('api/movies?title=Spider', [
            'Authorization' => 'Bearer ' . env('API_SECRET')
        ]);

        $response->assertOk()
                ->assertJsonFragment(['year' => 2002]);
    }

    public function test_year_is_cast_as_integer()
    {
        $movie = Movie::create([
            'imdb_id' => 'tt9876543',
            'title' => 'Test Movie',
            'year' => '2020',
            'director' => 'Test Director',
            'genre' => 'Drama',
        ]);

        $this->assertIsInt($movie->year);
        $this->assertEquals(2020, $movie->year);
    }
}
