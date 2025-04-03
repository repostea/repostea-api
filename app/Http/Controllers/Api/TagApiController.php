<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchTagRequest;
use App\Http\Resources\LinkCollection;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagApiController extends Controller
{
    public function index(Request $request): TagCollection
    {
        $sort = $request->query('sort', 'links_count');
        $direction = strtolower($request->query('direction', 'desc'));
        $perPage = (int) $request->query('per_page', '50');

        $validSorts = ['links_count', 'name', 'created_at'];

        if (! in_array($sort, $validSorts, true)) {
            $sort = 'links_count';
        }

        $tags = Tag::withCount('links')
            ->orderBy($sort, $direction)
            ->paginate($perPage);

        return new TagCollection($tags);
    }

    public function show(Request $request, Tag $tag): JsonResponse
    {
        $status = $request->query('status', 'published');
        $sort = $request->query('sort', $status === 'published' ? 'promoted_at' : 'created_at');
        $direction = strtolower($request->query('direction', 'desc'));
        $perPage = (int) $request->query('per_page', config('app.repostea.items_per_page', 15));

        $validStatuses = ['published', 'pending', 'all'];
        $validSorts = ['promoted_at', 'created_at', 'karma', 'votes', 'comments_count'];

        if (! in_array($status, $validStatuses, true)) {
            $status = 'published';
        }

        if (! in_array($sort, $validSorts, true)) {
            $sort = $status === 'published' ? 'promoted_at' : 'created_at';
        }

        $query = $tag->links()
            ->with(['user', 'tags'])
            ->withCount('comments');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $links = $query->orderBy($sort, $direction)
            ->paginate($perPage);

        return response()->json([
            'data' => [
                'tag' => new TagResource($tag),
                'links' => new LinkCollection($links),
            ],
        ]);
    }

    public function search(SearchTagRequest $request): TagCollection
    {
        $tags = Tag::where('name', 'like', '%'.$request->input('q').'%')
            ->withCount('links')
            ->orderBy('links_count', 'desc')
            ->limit(10)
            ->get();

        return new TagCollection($tags);
    }
}
