<?php

namespace Tests\Feature\Http;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PostPublicTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * Test public post index endpoint with unauthenticated access.
     */
    public function test_index_unauthenticated(): void
    {
        Post::factory(50, ['published_at' => $this->faker->dateTime()])->create();

        $response = $this->get(
            '/api/post/public',
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
     * Test public post index endpoint with authenticated access.
     */
    public function test_index_authenticated(): void
    {
        Post::factory(50, ['published_at' => $this->faker->dateTime()])->create();
        $role = Role::query()->create(['name' => Role::ROLE_USER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/post/public',
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
     * Test public post show endpoint with unauthenticated access.
     */
    public function test_show_unauthenticated(): void
    {
        $post = Post::factory(null, ['published_at' => $this->faker->dateTime()])->create();

        $response = $this->get(
            '/api/post/public/' . $post->id,
            ['Accept' => 'application/json']
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
    }

    /**
     * Test public post show endpoint with authenticated access.
     */
    public function test_show_authenticated(): void
    {
        $post = Post::factory(null, ['published_at' => $this->faker->dateTime()])->create();
        $role = Role::query()->create(['name' => Role::ROLE_USER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            '/api/post/' . $post->id,
            ['Accept' => 'application/json']
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
    }
}
