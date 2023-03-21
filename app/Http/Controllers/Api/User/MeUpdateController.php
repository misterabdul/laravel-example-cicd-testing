<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\MeUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MeUpdateController extends Controller
{
    /**
     * @var \App\Repositories\UserRepository
     */
    protected $userRepository;

    /**
     * @param  \App\Repositories\UserRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(MeUpdateRequest $request): JsonResponse
    {
        /** @var \App\Models\User */
        $me = $request->user();

        $updatedMe = DB::transaction(function () use ($me, $request) {
            $updatedMe = $this->userRepository
                ->update($me, $request->validated());

            return $updatedMe;
        });

        return new JsonResponse(new UserResource($updatedMe));
    }
}
