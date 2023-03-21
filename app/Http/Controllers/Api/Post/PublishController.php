<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PublishController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Post $post): JsonResponse
    {
        Gate::authorize('update', $post);

        /** @var \App\Models\Post */
        $publishedPost = DB::transaction(function () use ($post) {
            $post->published_at = Carbon::now();
            $post->save();

            return $post;
        });
        $post->load(['category', 'user']);

        return new JsonResponse(new PostResource($publishedPost));
    }
}
