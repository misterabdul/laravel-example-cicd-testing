<?php

namespace Tests\Feature\Http;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PostMineTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * Test post of mine endpoint with unauthenticated access.
     */
    public function test_unauthenticated(): void
    {
        $response = $this->get(
            'api/post/mine',
            ['Accept' => 'application/json']
        );

        $response->assertStatus(401);
    }

    /**
     * Test post of mine endpoint with authenticated access.
     */
    public function test_authenticated(): void
    {
        $role = Role::query()->create(['name' => Role::ROLE_WRITER]);
        $user = User::factory()->create();
        $user->roles()->sync($role);
        Post::factory(50, ['user_id' => $user->id])->create();
        Passport::actingAs($user);

        $response = $this->get(
            'api/post/mine',
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
}
