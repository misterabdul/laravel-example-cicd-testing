<?php

namespace App\Http\Controllers\Api\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Comment repository
     */
    protected CommentRepository $commentRepository;

    /**
     * Create new instance.
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->authorizeResource(Comment::class, 'comment');
        $this->commentRepository = $commentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $comments = Comment::query()
            ->paginate();

        return new JsonResponse(new CommentCollection($comments));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request): JsonResponse
    {
        /** @var \App\Models\Comment */
        $newComment = DB::transaction(function () use ($request) {
            $newComment = $this->commentRepository
                ->create($request->user(), $request->validated());

            return $newComment;
        });
        $newComment->load(['post', 'user']);

        return new JsonResponse(new CommentResource($newComment), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment): JsonResponse
    {
        $comment->load(['post', 'user']);

        return new JsonResponse(new CommentResource($comment));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentUpdateRequest $request, Comment $comment): JsonResponse
    {
        /** @var \App\Models\Comment */
        $updatedComment = DB::transaction(function () use ($comment, $request) {
            $updatedComment = $this->commentRepository
                ->update($comment, $request->validated());

            return $updatedComment;
        });
        $updatedComment->load(['post', 'user']);

        return new JsonResponse(new CommentResource($updatedComment));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        DB::transaction(function () use ($comment) {
            $deletedComment = $this->commentRepository
                ->softDelete($comment);

            return $deletedComment;
        });

        return new JsonResponse(null, 204);
    }
}
