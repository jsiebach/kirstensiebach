<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->seed();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_homepage_loads()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSeeText('Martian Geologist');
    }
}
