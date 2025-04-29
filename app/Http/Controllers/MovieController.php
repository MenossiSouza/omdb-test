<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieValidatorRequest;
use App\Services\MoviesRepository;
use App\Services\AuthService;
use Exception;

class MovieController extends Controller
{
    protected $moviesRepository;
    protected $authService;

    public function __construct(MoviesRepository $moviesRepository, AuthService $authService)
    {
        $this->moviesRepository = $moviesRepository;
        $this->authService = $authService;
    }

    public function index(MovieValidatorRequest $request)
    {
        if (!$this->authService->auth($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
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
                'error' => [
                    'message' => $e->getMessage(),
                ]
            ], 500);
        }
    }
}
