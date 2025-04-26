<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Movie;
use Illuminate\Support\Facades\Http;
use App\Services\MovieImporterService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MovieImporterServiceTest extends TestCase
{
    use RefreshDatabase; 

    public function test_import_movies_successfully()
    {
        Http::fake([
            'http://www.omdbapi.com/*' => Http::sequence()
                ->push([
                    'Search' => [
                        [
                            'imdbID' => 'tt0372784',
                        ]
                    ]
                ])
                ->push([
                    'Title' => 'Batman Begins',
                    'Year' => '2005',
                    'Director' => 'Christopher Nolan',
                    'Genre' => 'Action, Crime, Drama',
                    'imdbID' => 'tt0372784',
                ])
        ]);

        $service = new MovieImporterService(env('OMDB_API_KEY'));
        $message = $service->import('batman');

        $this->assertDatabaseHas('movies', [
            'imdb_id' => 'tt0372784',
            'title' => 'Batman Begins',
        ]);

        $this->assertEquals('Filmes importados com sucesso!', $message);
    }

    public function test_import_movies_not_found()
    {
        Http::fake([
            'http://www.omdbapi.com/*' => Http::response([
                'Response' => 'False',
                'Error' => 'Movie not found!'
            ])
        ]);

        $service = new MovieImporterService(env('OMDB_API_KEY'));
        $message = $service->import('abcxyz');

        $this->assertEquals('Filmes não encontrados.', $message);
    }
}
