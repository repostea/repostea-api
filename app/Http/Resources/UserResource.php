<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'username' => $this->resource->username,
            'avatar' => $this->resource->avatar ? url('storage/'.$this->resource->avatar) : null,
            'karma' => $this->resource->karma,
            'level' => $this->resource->level,
            'bio' => $this->resource->bio,
            'created_at' => $this->resource->created_at,
            'links_count' => $this->resource->links_count ?? $this->resource->links()->count(),
            'comments_count' => $this->resource->comments_count ?? $this->resource->comments()->count(),
        ];
    }
}
