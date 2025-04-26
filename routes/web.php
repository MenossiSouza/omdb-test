<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

Route::get('api/movies', [MovieController::class, 'index']);