<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostWithContentResource;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    protected PostRepository $postRepository;

    /**
     * Create a new class instance
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->authorizeResource(Post::class, 'post');
        $this->postRepository = $postRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $posts = Post::with(['category', 'user'])
            ->paginate();

        return new JsonResponse(new PostCollection($posts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request): JsonResponse
    {
        /** @var \App\Models\Post */
        $newPost = DB::transaction(function () use ($request) {
            $newPost = $this->postRepository
                ->create($request->user(), $request->validated());

            return $newPost;
        });
        $newPost->load(['category', 'user']);

        return new JsonResponse(new PostWithContentResource($newPost), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): JsonResponse
    {
        $post->load(['category', 'user']);

        return new JsonResponse(new PostWithContentResource($post));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post): JsonResponse
    {
        /** @var \App\Models\Post */
        $updatedPost = DB::transaction(function () use ($post, $request) {
            $updatedPost = $this->postRepository
                ->update($post, $request->validated());

            return $updatedPost;
        });
        $updatedPost->load(['category', 'user']);

        return new JsonResponse(new PostWithContentResource($updatedPost));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        DB::transaction(function () use ($post) {
            $deletedPost = $this->postRepository
                ->softDelete($post);

            return $deletedPost;
        });

        return new JsonResponse(null, 204);
    }
}
