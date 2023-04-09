<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribersTest extends TestCase
{
    /**
     * Test subscribers list
     *
     * @return void
     */
    public function test_list_subscribers()
    {
        $response = $this->get('/subscribers');

        $response->assertStatus(201);
    }

    /**
     * Test redirect if trying to edit and invalid subscriber
     *
     * @return void
     */
    public function test_invalid_edit_id()
    {
        $response = $this->get('/subscribers/edit/1');
        $response->assertStatus(404);
    }

    /**
     * Test new valid subscriber.
     *
     * @return void
     */
    public function testSubscriberAdded()
    {
        $this->json('POST', '/subscribers', ['name' => 'John Doe', 'country' => 'USA', 'email' => 'jd@mail.com'])
             ->assertJson([
                 'success' => true,
             ])->assertStatus(201);
    }

    /**
     * Test new invalid subscriber.
     *
     * @return void
     */
    public function testInvalidSubscriberAdded()
    {
        $this->json('POST', '/subscribers', ['name' => 'John Doe', 'country' => 'USA', 'email' => 'jd'])
             ->assertJson([
                 'success' => false,
             ])->assertStatus(400);
    }
}
