<?php

namespace Tests\Feature\Http;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PostPublishTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * Test publish post endpoint with unauthenticated access.
     */
    public function test_unauthenticated(): void
    {
        $post = Post::factory()->create();

        $response = $this->get(
            'api/post/' . $post->id . '/publish',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test publish post endpoint with unauthorized access.
     */
    public function test_unauthorized(): void
    {
        $post = Post::factory()->create();
        $role = Role::query()->create(['name' => Role::ROLE_WRITER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Passport::actingAs($user);

        $response = $this->get(
            'api/post/' . $post->id . '/publish',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(403);
    }

    /**
     * Test publish post endpoint with authorized access.
     */
    public function test_authorized(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_WRITER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        $post = Post::factory(null, ['user_id' => $user->id])->create();
        Passport::actingAs($user);

        $response = $this->get(
            'api/post/' . $post->id . '/publish',
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
        $this->assertTrue($response->json('publishedAt') !== null);
        $this->assertDatabaseHas($post, ['published_at' => $response->json('publishedAt')]);
    }
}
