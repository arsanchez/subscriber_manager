<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiKeyTest extends TestCase
{
    /**
     * Tests that the API form is shown if no api key is available
     *
     * @return void
     */
    public function test_enter_key_view()
    {
        $view = $this->view('index', ['settings' => false]);
         
        $view->assertSee('missing your api key');
    }

    /**
     * Tests that the subscribers table is shown if the api key is provided
     *
     * @return void
     */
    public function test_enter_valid_key_view()
    {
        $view = $this->view('index', ['settings' => true]);
         
        $view->assertSee('Filter by email');
    }

    /**
     * Tests the error if a key of invalid lenght is submitted
     *
     * @return void
    */
    public function test_enter_invalid_key_length()
    {
        $this->json('POST', '/save', ['key' => 'testshortkey'])
             ->assertJson([
                 'success' => false,
                 'errors' => 'The key must be at least 30 characters.',
             ])->assertStatus(400);
    }

    /**
     * Tests the error if an invalid key is submitted
     *
     * @return void
    */
    public function test_enter_invalid_key()
    {
        $this->json('POST', '/save', ['key' => 'D54A0OXjGaCY60WW5IRJUInpS_wXxiGrEAe2L0lC8eGmT_eCdPYACVNi5XVqnVrI9AR7aO-E5dW1tujwjy3h3FR0NSiA'])
             ->assertJson([
                 'success' => false,
                 'errors' => 'Unauthenticated.',
             ])->assertStatus(400);
    }
}
