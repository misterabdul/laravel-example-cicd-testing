<?php

namespace Tests\Feature\Http;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * Test user index endpoint with unauthenticated access.
     */
    public function test_index_unauthenticated(): void
    {
        User::factory()->create();

        $response = $this->get(
            '/api/user',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test user index endpoint with authenticated access.
     */
    public function test_index_authenticated(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_USER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/user',
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
     * Test user show endpoint with unauthenticated access.
     */
    public function test_show_unauthenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->get(
            '/api/user/' . $user->id,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test user show endpoint with authenticated access.
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

    /**
     * Test user store endpoint with unauthenticated access.
     */
    public function test_store_unauthenticated(): void
    {
        Role::query()->create(['name' => Role::ROLE_USER]);
        $response = $this->postJson(
            '/api/user',
            [
                'name'      => 'test',
                'email'     => 'test@example.com',
                'password'  => 'password',
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test user store endpoint with unauthorized access.
     */
    public function test_store_unauthorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        Role::query()->create(['name' => Role::ROLE_USER]);
        $response = $this->postJson(
            '/api/user',
            [
                'name'      => 'test',
                'email'     => 'test@example.com',
                'password'  => 'password',
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test user store endpoint with authorized access.
     */
    public function test_store_authorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_ADMIN]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        Role::query()->create(['name' => Role::ROLE_USER]);
        $response = $this->postJson(
            '/api/user',
            [
                'name'      => 'test',
                'email'     => 'test@example.com',
                'password'  => 'password',
            ],
            ['Accept', 'application/json']
        );

        $response->assertStatus(201);
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
        $response->assertJson([
            'name'  => 'test',
            'email' => 'test@example.com',
        ]);
        $this->assertDatabaseHas(User::class, [
            'id'    => $response->json('id'),
            'name'  => 'test',
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Test user update endpoint with unauthenticated access.
     */
    public function test_update_unauthenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->putJson(
            '/api/user/' . $user->id,
            ['name' => 'Test Updated'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test user update endpoint with unauthorized access.
     */
    public function test_update_unauthorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);
        $target = User::factory()->create();

        $response = $this->putJson(
            '/api/user/' . $target->id,
            ['name' => 'Test Updated'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test user update endpoint with authorized access.
     */
    public function test_update_authorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_ADMIN]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);
        $target = User::factory()->create();

        $response = $this->putJson(
            '/api/user/' . $target->id,
            ['name' => 'Test Updated'],
            ['Accept', 'application/json']
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
        $response->assertJson([
            'name'  => 'Test Updated',
        ]);
        $this->assertDatabaseHas($target, [
            'name'  => 'Test Updated',
        ]);
    }

    /**
     * Test user delete endpoint with unauthenticated access.
     */
    public function test_delete_unauthenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->delete(
            '/api/user/' . $user->id,
            [],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test user delete endpoint with unauthorized access.
     */
    public function test_delete_unauthorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);
        $target = User::factory()->create();

        $response = $this->delete(
            '/api/user/' . $target->id,
            [],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test user delete endpoint with authorized access.
     */
    public function test_delete_authorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_ADMIN]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);
        $target = User::factory()->create();

        $response = $this->delete(
            '/api/user/' . $target->id,
            [],
            ['Accept', 'application/json']
        );

        $response->assertStatus(204);
        $response->assertNoContent();
        $this->assertSoftDeleted($target);
    }
}
