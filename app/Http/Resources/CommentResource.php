<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'content' => $this->resource->content,
            'votes' => $this->resource->votes,
            'karma' => $this->resource->karma,
            'created_at' => $this->resource->created_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'link_id' => $this->resource->link_id,
            'parent_id' => $this->resource->parent_id,
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
        ];
    }
}
