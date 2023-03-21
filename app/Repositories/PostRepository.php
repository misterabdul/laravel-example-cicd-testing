<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\User;

class PostRepository
{
    /**
     * Create new post from given user & input.
     */
    public function create(User $user, array $input): Post
    {
        $newPost = new Post([
            'category_id'   => $input['category'],
            'slug'          => $input['slug'],
            'title'         => $input['title'],
            'content'       => $input['content'],
        ]);
        $user->posts()->save($newPost);

        return $newPost;
    }

    /**
     * Update post from given input.
     */
    public function update(Post $post, array $input): Post
    {
        $post->update([
            'category_id'   => $input['category'] ?? $post->category_id,
            'slug'          => $input['slug'] ?? $post->slug,
            'title'         => $input['title'] ?? $post->title,
            'content'       => $input['content'] ?? $post->content,
        ]);

        return $post;
    }

    /**
     * Soft delete the given post.
     */
    public function softDelete(Post $post): Post
    {
        $post->delete();

        return $post;
    }
}
