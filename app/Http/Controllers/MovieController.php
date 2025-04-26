<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieValidatorRequest;
use App\Services\MoviesRepository;
use Exception;

class MovieController extends Controller
{
    protected $moviesRepository;

    public function __construct(MoviesRepository $moviesRepository)
    {
        $this->moviesRepository = $moviesRepository;
    }

    public function index(MovieValidatorRequest $request)
    {
        try {
            $movies = $this->moviesRepository->findAllBy($request->validated());
            if ($movies->isEmpty()) {
                return response()->json([
                    'message' => 'Nenhum resultado encontrado.'
                ], 404);
            } 

            return response()->json($movies);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e
            ], 500);
        }
    }
}
