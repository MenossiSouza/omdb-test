<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Movie;

class MovieImporterService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OMDB_API_KEY');
    }

    public function import(string $search): string
    {
        $response = Http::get("http://www.omdbapi.com/?apikey={$this->apiKey}&s={$search}");
    
        $movies = $response['Search'] ?? [];
    
        if (empty($movies)) {
            return 'Filmes não encontrados.';
        }
    
        foreach ($movies as $item) {
            $details = Http::get("http://www.omdbapi.com/?apikey={$this->apiKey}&i={$item['imdbID']}");
            $data = $details->json();
    
            Movie::updateOrCreate(
                ['imdb_id' => $data['imdbID']],
                [
                    'title' => $data['Title'],
                    'year' => $data['Year'],
                    'director' => $data['Director'],
                    'genre' => $data['Genre'],
                ]
            );
        }
    
        return 'Filmes importados com sucesso!';
    }
}
