<?php

namespace Tests\Feature\Http;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * Test role index endpoint with unauthenticated access.
     */
    public function test_index_unauthenticated(): void
    {
        Role::query()->create(['name' => Role::ROLE_ADMIN]);

        $response = $this->get(
            '/api/role',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test role index endpoint with unauthorized access.
     */
    public function test_index_unauthorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/role',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test role index endpoint with authorized access.
     */
    public function test_index_authorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_ADMIN]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/role',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'description',
            ],
        ]);
    }

    /**
     * Test role show endpoint with unauthenticated access.
     */
    public function test_show_unauthenticated(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_ADMIN]);

        $response = $this->get(
            '/api/role/' . $role->id,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test role show endpoint with unauthorized access.
     */
    public function test_show_unauthorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/role/' . $role->id,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test role show endpoint with authorized access.
     */
    public function test_show_authorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_ADMIN]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/role/' . $role->id,
            ['Accept', 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'description',
        ]);
    }

    /**
     * Test role store endpoint with unauthenticated access.
     */
    public function test_store_unauthenticated(): void
    {
        $response = $this->postJson(
            '/api/role',
            ['name' => 'test'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test role store endpoint with unauthorized access.
     */
    public function test_store_unauthorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->postJson(
            '/api/role',
            ['name' => 'test'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test role store endpoint with authorized access.
     */
    public function test_store_authorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_ADMIN]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->postJson(
            '/api/role',
            ['name' => 'test'],
            ['Accept', 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test role update endpoint with unauthenticated access.
     */
    public function test_update_unauthenticated(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_USER]);

        $response = $this->putJson(
            '/api/role/' . $role->id,
            ['name' => 'test'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test role update endpoint with unauthorized access.
     */
    public function test_update_unauthorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->putJson(
            '/api/role/' . $role->id,
            ['name' => 'test'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test role update endpoint with authorized access.
     */
    public function test_update_authorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_ADMIN]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->putJson(
            '/api/role/' . $role->id,
            ['name' => 'test'],
            ['Accept', 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test role delete endpoint with unauthenticated access.
     */
    public function test_delete_unauthenticated(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_USER]);

        $response = $this->delete(
            '/api/role/' . $role->id,
            [],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test role delete endpoint with unauthorized access.
     */
    public function test_delete_unauthorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->delete(
            '/api/role/' . $role->id,
            [],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test role delete endpoint with authorized access.
     */
    public function test_delete_authorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_ADMIN]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->delete(
            '/api/role/' . $role->id,
            [],
            ['Accept', 'application/json']
        );

        $response->assertStatus(403);
    }
}
