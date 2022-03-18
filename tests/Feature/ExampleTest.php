<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\CreatesApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use CreatesApplication;

    public function testBasicTest()
    {
        // $this->assertTrue(true);
        $response = $this->get('/');
        echo $response;
        // $response->assertStatus(200);
    }
}
