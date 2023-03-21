<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    /**
     * @param  array  $input
     * @return \App\Models\Role
     */
    public function getOrCreate($input): Role
    {
        $role = Role::query()->firstOrCreate(
            ['name'          => $input['name']],
            ['description'   => $input['description'] ?? null]
        );

        return $role;
    }
}
