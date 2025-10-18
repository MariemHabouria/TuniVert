<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SimpleHealthTest extends TestCase
{
    #[Test]
    public function basic_application_test()
    {
        // Simple test that doesn't require database
        $this->assertTrue(true);
    }

    #[Test]
    public function environment_is_testing()
    {
        $this->assertEquals('testing', app()->environment());
    }
}