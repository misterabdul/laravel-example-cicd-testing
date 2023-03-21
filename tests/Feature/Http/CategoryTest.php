<?php

namespace Tests\Feature\Http;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * Test category index endpoint with unauthenticated access.
     */
    public function test_index_unauthenticated(): void
    {
        Category::factory()->create();

        $response = $this->get(
            '/api/category',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test category index endpoint with authenticated access.
     */
    public function test_index_authenticated(): void
    {
        Category::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_USER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/category',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'slug',
                'name',
                'description',
                'createdAt',
                'updatedAt',
            ],
        ]);
    }

    /**
     * Test category show endpoint with unauthenticated access.
     */
    public function test_show_unauthenticated(): void
    {
        $category = Category::factory()->create();

        $response = $this->get(
            '/api/category/' . $category->id,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test category show endpoint with authenticated access.
     */
    public function test_show_authenticated(): void
    {
        $category = Category::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_USER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/category/' . $category->id,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'slug',
            'name',
            'description',
            'createdAt',
            'updatedAt',
        ]);
    }

    /**
     * Test category store endpoint with unauthenticated access.
     */
    public function test_store_unauthenticated(): void
    {
        $response = $this->postJson(
            '/api/category',
            [
                'slug'          => 'test',
                'name'          => 'Test Category',
                'description'   => 'Lorem ipsum dolor sit amet.',
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test category store endpoint with unauthorized access.
     */
    public function test_store_unauthorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_WRITER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->postJson(
            '/api/category',
            [
                'slug'          => 'test',
                'name'          => 'Test Category',
                'description'   => 'Lorem ipsum dolor sit amet.',
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test category store endpoint with authorized access.
     */
    public function test_store_authorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->postJson(
            '/api/category',
            [
                'slug'          => 'test',
                'name'          => 'Test Category',
                'description'   => 'Lorem ipsum dolor sit amet.',
            ],
            ['Accept', 'application/json']
        );

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'slug',
            'name',
            'description',
            'createdAt',
            'updatedAt',
        ]);
        $response->assertJson([
            'slug'          => 'test',
            'name'          => 'Test Category',
            'description'   => 'Lorem ipsum dolor sit amet.',
        ]);
        $this->assertDatabaseHas(Category::class, [
            'id'            => $response->json('id'),
            'slug'          => 'test',
            'name'          => 'Test Category',
            'description'   => 'Lorem ipsum dolor sit amet.',
        ]);
    }

    /**
     * Test category update endpoint with unauthenticated access.
     */
    public function test_update_unauthenticated(): void
    {
        $category = Category::factory()->create();

        $response = $this->putJson(
            '/api/category/' . $category->id,
            ['name' => 'Test Updated'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test category update endpoint with unauthorized access.
     */
    public function test_update_unauthorized(): void
    {
        $category = Category::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_USER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->putJson(
            '/api/category/' . $category->id,
            ['name' => 'Test Updated'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test category update endpoint with authorized access.
     */
    public function test_update_authorized(): void
    {
        $category = Category::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->putJson(
            '/api/category/' . $category->id,
            ['name' => 'Test Updated'],
            ['Accept', 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'slug',
            'name',
            'description',
            'createdAt',
            'updatedAt',
        ]);
        $response->assertJson([
            'name'  => 'Test Updated',
        ]);
        $this->assertDatabaseHas($category, [
            'name'  => 'Test Updated',
        ]);
    }

    /**
     * Test category delete endpoint with unauthenticated access.
     */
    public function test_delete_unauthenticated(): void
    {
        $category = Category::factory()->create();

        $response = $this->delete(
            '/api/category/' . $category->id,
            [],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test category delete endpoint with unauthorized access.
     */
    public function test_delete_unauthorized(): void
    {
        $category = Category::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_WRITER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->delete(
            '/api/category/' . $category->id,
            [],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test category delete endpoint with authorized access.
     */
    public function test_delete_authorized(): void
    {
        $category = Category::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->delete(
            '/api/category/' . $category->id,
            [],
            ['Accept', 'application/json']
        );

        $response->assertStatus(204);
        $response->assertNoContent();
        $this->assertSoftDeleted($category);
    }
}
