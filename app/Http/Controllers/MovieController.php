<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieFilterRequest;
use App\Services\MovieFilterService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $movieFilterService;

    public function __construct(MovieFilterService $movieFilterService)
    {
        $this->movieFilterService = $movieFilterService;
    }

    public function index(MovieFilterRequest $request)
    {
        $movies = $this->movieFilterService->filterMovies($request->validated());

        return response()->json($movies);
    }
}
