<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\MovieController;
use App\Http\Requests\MovieValidatorRequest;
use App\Services\MoviesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Tests\TestCase;     
use Mockery;
use Exception;

class MovieControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function test_index_returns_200()
    {
        $moviesRepository = Mockery::mock(MoviesRepository::class);
        $request = Mockery::mock(MovieValidatorRequest::class);
        $request->shouldReceive('header')
        ->with('Authorization')
        ->andReturn('Bearer ' . env('API_SECRET'));
        
        $validatedData = ['title' => 'Batman'];
        $moviesData = collect([
            ['title' => 'Batman Begins', 'year' => 2005],
            ['title' => 'The Dark Knight', 'year' => 2008],
        ]);

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $moviesRepository->shouldReceive('findAllBy')->once()->with($validatedData)->andReturn($moviesData);

        $controller = new MovieController($moviesRepository);

        $response = $controller->index($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($moviesData->toArray(), $response->getData(true));
    }

    public function test_index_returns_404()
    {
        $moviesRepository = Mockery::mock(MoviesRepository::class);
        $request = Mockery::mock(MovieValidatorRequest::class);
        $request->shouldReceive('header')
        ->with('Authorization')
        ->andReturn('Bearer ' . env('API_SECRET'));

        $validatedData = ['title' => 'Unknown Movie'];
        $emptyCollection = collect([]);

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $moviesRepository->shouldReceive('findAllBy')->once()->with($validatedData)->andReturn($emptyCollection);

        $controller = new MovieController($moviesRepository);

        $response = $controller->index($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->status());
        $this->assertEquals(['message' => 'Nenhum resultado encontrado.'], $response->getData(true));
    }

    public function test_index_returns_500()
    {
        $moviesRepository = Mockery::mock(MoviesRepository::class);
        $request = Mockery::mock(MovieValidatorRequest::class);
        $request->shouldReceive('header')
        ->with('Authorization')
        ->andReturn('Bearer ' . env('API_SECRET'));

        $validatedData = ['title' => 'Batman'];

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $moviesRepository->shouldReceive('findAllBy')->once()->with($validatedData)->andThrow(new Exception('Database error'));

        $controller = new MovieController($moviesRepository);

        $response = $controller->index($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->status());

        $responseData = $response->getData(true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertArrayHasKey('message', $responseData['error']);
        $this->assertEquals('Database error', $responseData['error']['message']);
    }

    public function test_authorization_failure()
    {
        $request = Mockery::mock(MovieValidatorRequest::class);
        $request->shouldReceive('header')
            ->with('Authorization')
            ->andReturn(null);
    
        $movieService = Mockery::mock(MoviesRepository::class);
        $movieService->shouldReceive('findAllBy')->never();
    
        $controller = new MovieController($movieService);
        $response = $controller->index($request);
    
        $this->assertEquals(401, $response->status());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('Unauthorized', $responseData['error']);
    }
}
