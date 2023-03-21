<?php

namespace Tests\Feature\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MeTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * Test get me data with unauthenticated access.
     */
    public function test_get_me_unauthenticated(): void
    {
        $response = $this->get(
            '/api/me',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test update me data with unauthenticated access.
     */
    public function test_update_me_unauthenticated(): void
    {
        $response = $this->put(
            '/api/me',
            ['name'  => 'Updated user'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test get me data with authenticated access.
     */
    public function test_get_me_authenticated(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->get(
            '/api/me',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'email',
            'name',
            'createdAt',
            'updatedAt',
        ]);
    }

    /**
     * Test update me data with unauthenticated access.
     */
    public function test_update_me_authenticated(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->put(
            '/api/me',
            ['name'  => 'Updated user'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'email',
            'name',
            'createdAt',
            'updatedAt',
        ]);
        $response->assertJson(['name' => 'Updated user']);
        $this->assertDatabaseHas($user, ['name' => 'Updated user']);
    }
}
