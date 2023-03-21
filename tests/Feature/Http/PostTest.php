<?php

namespace Tests\Feature\Http;

use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * Test post index endpoint with unauthenticated access.
     */
    public function test_index_unauthenticated(): void
    {
        Post::factory()->create();

        $response = $this->get(
            '/api/post',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test post index endpoint with authenticated access.
     */
    public function test_index_authenticated(): void
    {
        Post::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_USER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/post',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test post index endpoint with authorized access.
     */
    public function test_index_authorized(): void
    {
        Post::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/post',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'slug',
                'title',
                'category' => [
                    'id',
                    'slug',
                    'name',
                    'description',
                    'createdAt',
                    'updatedAt',
                ],
                'user' => [
                    'id',
                    'email',
                    'name',
                    'createdAt',
                    'updatedAt',
                ],
                'publishedAt',
                'createdAt',
                'updatedAt',
            ],
        ]);
    }

    /**
     * Test post show endpoint with unauthenticated access.
     */
    public function test_show_unauthenticated(): void
    {
        $post = Post::factory()->create();

        $response = $this->get(
            '/api/post/' . $post->id,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test post show endpoint with authenticated access.
     */
    public function test_show_authenticated(): void
    {
        $post = Post::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_USER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/post/' . $post->id,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test post store endpoint with unauthenticated access.
     */
    public function test_store_unauthenticated(): void
    {
        $category = Category::factory()->create();

        $response = $this->postJson(
            '/api/post',
            [
                'category'  => $category->id,
                'slug'      => 'test',
                'title'     => 'Test Post',
                'content'   => 'Lorem ipsum dolor sit amet.',
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test post store endpoint with unauthorized access.
     */
    public function test_store_unauthorized(): void
    {
        $category = Category::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_USER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->postJson(
            '/api/post',
            [
                'category'  => $category->id,
                'slug'      => 'test',
                'title'     => 'Test Post',
                'content'   => 'Lorem ipsum dolor sit amet.',
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test post store endpoint with authorized access.
     */
    public function test_store_authorized(): void
    {
        $category = Category::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_WRITER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->postJson(
            '/api/post',
            [
                'category'  => $category->id,
                'slug'      => 'test',
                'title'     => 'Test Post',
                'content'   => 'Lorem ipsum dolor sit amet.',
            ],
            ['Accept', 'application/json']
        );

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'slug',
            'title',
            'category' => [
                'id',
                'slug',
                'name',
                'description',
                'createdAt',
                'updatedAt',
            ],
            'user' => [
                'id',
                'email',
                'name',
                'createdAt',
                'updatedAt',
            ],
            'publishedAt',
            'createdAt',
            'updatedAt',
        ]);
        $response->assertJson([
            'slug'      => 'test',
            'title'     => 'Test Post',
            'content'   => 'Lorem ipsum dolor sit amet.',
            'category'  => [
                'id'    => $category->id,
            ],
        ]);
        $this->assertDatabaseHas(Post::class, [
            'id'            => $response->json('id'),
            'slug'          => 'test',
            'title'         => 'Test Post',
            'content'       => 'Lorem ipsum dolor sit amet.',
            'category_id'   => $category->id,
        ]);
    }

    /**
     * Test post update endpoint with unauthenticated access.
     */
    public function test_update_unauthenticated(): void
    {
        $post = Post::factory()->create();

        $response = $this->putJson(
            '/api/post/' . $post->id,
            ['title' => 'Test Updated'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test post update endpoint with unauthorized access.
     */
    public function test_update_unauthorized(): void
    {
        $post = Post::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_USER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->putJson(
            '/api/post/' . $post->id,
            ['name' => 'Test Updated'],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test post update endpoint with authorized access.
     */
    public function test_update_authorized(): void
    {
        $post = Post::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->putJson(
            '/api/post/' . $post->id,
            ['title' => 'Test Updated'],
            ['Accept', 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'slug',
            'title',
            'category' => [
                'id',
                'slug',
                'name',
                'description',
                'createdAt',
                'updatedAt',
            ],
            'user' => [
                'id',
                'email',
                'name',
                'createdAt',
                'updatedAt',
            ],
            'publishedAt',
            'createdAt',
            'updatedAt',
        ]);
        $response->assertJson([
            'title' => 'Test Updated',
        ]);
        $this->assertDatabaseHas($post, [
            'title' => 'Test Updated',
        ]);
    }

    /**
     * Test post delete endpoint with unauthenticated access.
     */
    public function test_delete_unauthenticated(): void
    {
        $post = Post::factory()->create();

        $response = $this->delete(
            '/api/post/' . $post->id,
            [],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test post delete endpoint with unauthorized access.
     */
    public function test_delete_unauthorized(): void
    {
        $post = Post::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_USER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->delete(
            '/api/post/' . $post->id,
            [],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test post delete endpoint with authorized access.
     */
    public function test_delete_authorized(): void
    {
        $post = Post::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_EDITOR]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->delete(
            '/api/post/' . $post->id,
            [],
            ['Accept', 'application/json']
        );

        $response->assertStatus(204);
        $response->assertNoContent();
        $this->assertSoftDeleted($post);
    }
}
