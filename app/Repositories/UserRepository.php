<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * Create new user from given input.
     */
    public function create(array $input): User
    {
        $newUser = User::create([
            'email'     => $input['email'],
            'name'      => $input['name'],
            'password'  => Hash::make($input['password']),
        ]);
        $newUser->roles()->sync(Role::where('name', '=', Role::ROLE_USER)->firstOrFail());

        return $newUser;
    }

    /**
     * Update given user from given input.
     */
    public function update(User $user, array $input): User
    {
        $password = $user->password;
        if (isset($input['password']) && $input['password'] !== null)
            $password = Hash::make($input['password']);
        $user->update([
            'email'     => $input['email'] ?? $user->email,
            'name'      => $input['name'] ?? $user->name,
            'password'  => $password,
        ]);

        return $user;
    }

    /**
     * Soft delete the given user.
     */
    public function softDelete(User $user): User
    {
        $user->delete();

        return $user;
    }
}
