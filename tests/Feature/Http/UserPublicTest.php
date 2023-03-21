<?php

namespace Tests\Feature\Http;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserPublicTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * Test public user index endpoint with unauthenticated access.
     */
    public function test_index_unauthenticated(): void
    {
        User::factory()->create();

        $response = $this->get(
            '/api/user/public',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'email',
                'name',
                'createdAt',
                'updatedAt',
                'roles' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Test public user index endpoint with authenticated access.
     */
    public function test_index_authenticated(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_USER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/user/public',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'email',
                'name',
                'createdAt',
                'updatedAt',
                'roles' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Test public user show endpoint with unauthenticated access.
     */
    public function test_show_unauthenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->get(
            '/api/user/public/' . $user->id,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'email',
            'name',
            'createdAt',
            'updatedAt',
            'roles' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                ],
            ],
        ]);
    }

    /**
     * Test public user show endpoint with authenticated access.
     */
    public function test_show_authenticated(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_USER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/user/' . $user->id,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'email',
            'name',
            'createdAt',
            'updatedAt',
            'roles' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                ],
            ],
        ]);
    }
}
