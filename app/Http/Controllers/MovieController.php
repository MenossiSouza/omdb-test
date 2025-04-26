<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query();

        $request->validate([
            'title' => ['sometimes', 'string'],
            'year' => ['sometimes', 'integer'],
            'director' => ['sometimes', 'string'],
        ]);

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('director')) {
            $query->where('director', 'like', '%' . $request->director . '%');
        }

        return response()->json($query->get());
    }
}
