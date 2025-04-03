<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'url' => $this->resource->url,
            'type' => $this->resource->type,
            'source' => $this->resource->source,
            'original_external_url' => $this->resource->original_external_url,
            'content' => $this->resource->content,
            'description' => $this->resource->description,
            'status' => $this->resource->status,
            'votes' => $this->resource->votes,
            'karma' => $this->resource->karma,
            'clicks' => $this->resource->clicks,
            'nsfw' => $this->resource->nsfw,
            'image' => $this->resource->image ? url('storage/'.$this->resource->image) : null,
            'created_at' => $this->resource->created_at,
            'promoted_at' => $this->resource->promoted_at,
            'federated' => $this->resource->federated,
            'user' => new UserResource($this->whenLoaded('user')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'comments_count' => $this->resource->comments_count ?? $this->resource->comments->count(),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
