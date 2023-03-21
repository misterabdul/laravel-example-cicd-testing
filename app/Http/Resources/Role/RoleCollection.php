<?php

namespace App\Http\Resources\Role;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = RoleResource::class;

    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): Arrayable
    {
        return $this->collection;
    }
}
