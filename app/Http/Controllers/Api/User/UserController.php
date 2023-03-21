<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * @var \App\Repositories\UserRepository
     */
    protected $userRepository;

    /**
     * @param  \App\Repositories\UserRepository  $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->authorizeResource(User::class, 'user');
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $users = User::query()
            ->paginate();

        return new JsonResponse(new UserCollection($users));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $newUser = DB::transaction(function () use ($request) {
            $newUser = $this->userRepository
                ->create($request->validated());

            return $newUser;
        });
        $newUser->load(['roles']);

        return new JsonResponse(new UserResource($newUser), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return new JsonResponse(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $updatedUser = DB::transaction(function () use ($user, $request) {
            $updatedUser = $this->userRepository
                ->update($user, $request->validated());

            return $updatedUser;
        });

        return new JsonResponse(new UserResource($updatedUser));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        DB::transaction(function () use ($user) {
            $deletedUser = $this->userRepository
                ->softDelete($user);

            return $deletedUser;
        });

        return new JsonResponse(null, 204);
    }
}
