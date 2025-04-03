<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LinkCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'current_page' => $this->resource->currentPage(),
                'from' => $this->resource->firstItem(),
                'last_page' => $this->resource->lastPage(),
                'path' => $this->resource->path(),
                'per_page' => $this->resource->perPage(),
                'to' => $this->resource->lastItem(),
                'total' => $this->resource->total(),
            ],
            'links' => [
                'self' => url()->current(),
            ],
        ];
    }
}
