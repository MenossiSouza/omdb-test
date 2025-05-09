<?php

namespace App\Services;

use App\Models\Movie;

class MoviesRepository
{
    public function findAllBy(array $filters)
    {
        $query = Movie::query();

        foreach ($filters as $key => $value) {
            if ($value) {
                if ($key === 'title' || $key === 'director') {
                    $query->where($key, 'like', "%$value%");
                } else {
                    $query->where($key, $value);
                }
            }
        }

        return $query->get();
    }
}
