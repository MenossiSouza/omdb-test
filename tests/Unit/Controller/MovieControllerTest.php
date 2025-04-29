<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\MovieController;
use App\Http\Requests\MovieValidatorRequest;
use App\Services\MoviesRepository;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;     
use Mockery;
use Exception;

class MovieControllerTest extends TestCase
{
    protected $moviesRepository;
    protected $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->moviesRepository = Mockery::mock(MoviesRepository::class);
        $this->authService = Mockery::mock(AuthService::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function test_index_returns_200()
    {
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
        $this->authService->shouldReceive('auth')->once()->with($request)->andReturn(true);
        $this->moviesRepository->shouldReceive('findAllBy')->once()->with($validatedData)->andReturn($moviesData);

        $controller = new MovieController($this->moviesRepository, $this->authService);

        $response = $controller->index($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->status());
        $this->assertEquals($moviesData->toArray(), $response->getData(true));
    }

    public function test_index_returns_401()
    {
        $request = Mockery::mock(MovieValidatorRequest::class);
        $request->shouldReceive('header')
            ->with('Authorization')
            ->andReturn(null);
    
        $this->authService->shouldReceive('auth')->once()->with($request)->andReturn(false);
        $this->moviesRepository->shouldReceive('findAllBy')->never();
    
        $controller = new MovieController($this->moviesRepository, $this->authService);
        $response = $controller->index($request);
    
        $this->assertEquals(401, $response->status());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('Unauthorized', $responseData['error']);
    }
    
    public function test_index_returns_404()
    {
        $request = Mockery::mock(MovieValidatorRequest::class);
        $request->shouldReceive('header')
        ->with('Authorization')
        ->andReturn('Bearer ' . env('API_SECRET'));

        $validatedData = ['title' => 'Unknown Movie'];
        $emptyCollection = collect([]);

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $this->authService->shouldReceive('auth')->once()->with($request)->andReturn(true);
        $this->moviesRepository->shouldReceive('findAllBy')->once()->with($validatedData)->andReturn($emptyCollection);

        $controller = new MovieController($this->moviesRepository, $this->authService);

        $response = $controller->index($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->status());
        $this->assertEquals(['message' => 'Nenhum resultado encontrado.'], $response->getData(true));
    }

    public function test_index_returns_500()
    {
        $request = Mockery::mock(MovieValidatorRequest::class);
        $request->shouldReceive('header')
        ->with('Authorization')
        ->andReturn('Bearer ' . env('API_SECRET'));

        $validatedData = ['title' => 'Batman'];

        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        $this->authService->shouldReceive('auth')->once()->with($request)->andReturn(true);
        $this->moviesRepository->shouldReceive('findAllBy')->once()->with($validatedData)->andThrow(new Exception('Database error'));

        $controller = new MovieController($this->moviesRepository, $this->authService);

        $response = $controller->index($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->status());

        $responseData = $response->getData(true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertArrayHasKey('message', $responseData['error']);
        $this->assertEquals('Database error', $responseData['error']['message']);
    }
}
