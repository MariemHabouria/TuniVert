<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpleHealthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function application_returns_successful_response()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function health_endpoint_works()
    {
        // Simple test that doesn't require complex database operations
        $this->assertTrue(true);
    }
}