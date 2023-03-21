<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentRepository
{
    /**
     * Create new comment from given user & input.
     */
    public function create(User $user, array $input, ?Post $post = null): Comment
    {
        $newComment = new Comment([
            'post_id'   => $post?->id ?? $input['post'],
            'content'   => $input['content'],
        ]);
        $user->comments()->save($newComment);

        return $newComment;
    }

    /**
     * Update comment from given input.
     */
    public function update(Comment $comment, array $input): Comment
    {
        $comment->update([
            'content' => $input['content'] ?? $comment->content,
        ]);

        return $comment;
    }

    /**
     * Soft delete the given comment.
     */
    public function softDelete(Comment $comment): Comment
    {
        $comment->delete();

        return $comment;
    }
}
