<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ?User $user = null): JsonResponse
    {
        if ($user !== null)
            return new JsonResponse(new UserResource($user));

        $users = User::query()
            ->paginate();

        return new JsonResponse(new UserCollection($users));
    }
}
