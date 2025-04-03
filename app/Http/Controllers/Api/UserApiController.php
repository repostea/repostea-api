<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\LinkCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\StatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    public function show(string $username): JsonResponse
    {
        $user = User::where('username', $username)
            ->withCount(['links', 'comments'])
            ->firstOrFail();

        $statsService = app(StatsService::class);
        $stats = $statsService->getUserStats($user);

        return response()->json([
            'data' => [
                'user' => new UserResource($user),
                'stats' => $stats,
            ],
        ]);
    }

    public function links(Request $request, string $username): LinkCollection
    {
        $user = User::where('username', $username)->firstOrFail();

        $status = $request->query('status', 'published');
        $sort = $request->query('sort', 'created_at');
        $direction = strtolower($request->query('direction', 'desc'));
        $perPage = (int) $request->query('per_page', config('app.repostea.items_per_page', '15'));

        $validStatuses = ['published', 'pending', 'all'];

        if (! in_array($status, $validStatuses, true)) {
            $status = 'published';
        }

        $query = $user->links()
            ->with('tags')
            ->withCount('comments');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $links = $query->orderBy($sort, $direction)
            ->paginate($perPage);

        return new LinkCollection($links);
    }

    public function comments(Request $request, string $username): CommentCollection
    {
        $user = User::where('username', $username)->firstOrFail();

        $perPage = (int) $request->query('per_page', config('app.repostea.items_per_page', '15'));
        $sort = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');

        $comments = $user->comments()
            ->with('link')
            ->orderBy($sort, $direction)
            ->paginate($perPage);

        return new CommentCollection($comments);
    }
}
