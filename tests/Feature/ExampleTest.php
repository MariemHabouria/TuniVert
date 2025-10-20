<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example that doesn't require complex setup.
     */
    public function test_basic_feature_test(): void
    {
        // Simple test that verifies the testing framework works
        $this->assertTrue(true);
        $this->assertEquals('testing', app()->environment());
    }
}
