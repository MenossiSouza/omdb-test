<?php

namespace Tests\Unit\Controllers;

use App\Http\Requests\MovieValidatorRequest;
use App\Services\AuthService;
use Tests\TestCase;     
use Mockery;


class AuthServiceTest extends TestCase
{
    public function test_auth_returns_false_if_authorization_header_is_invalid()
    {
        $request = Mockery::mock(MovieValidatorRequest::class);
        $request->shouldReceive('header')
        ->with('Authorization')
        ->andReturn('Basic ' . '1910');
        
        $authService = new AuthService();

        $response = $authService->auth($request);

        $this->assertEquals(false, $response);
    }

    public function test_auth_returns_false_if_authorization_token_is_invalid()
    {
        $request = Mockery::mock(MovieValidatorRequest::class);
        $request->shouldReceive('header')
        ->with('Authorization')
        ->andReturn('Bearer ' . '1910');
        
        $authService = new AuthService();

        $response = $authService->auth($request);

        $this->assertEquals(false, $response);
    }

    public function test_auth_returns_true_if_authorization_token_is_valid()
    {
        $request = Mockery::mock(MovieValidatorRequest::class);
        $request->shouldReceive('header')
        ->with('Authorization')
        ->andReturn('Bearer ' . env('API_SECRET'));
        
        $authService = new AuthService();

        $response = $authService->auth($request);

        $this->assertEquals(true, $response);
    }
}
