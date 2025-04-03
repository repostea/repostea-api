<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentIndexRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UserCommentsRequest;
use App\Http\Requests\VoteCommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Link;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CommentApiController extends Controller
{
    public function index(CommentIndexRequest $request, $linkId): CommentCollection
    {
        $sort = $request->validated('sort', 'karma');
        $direction = $request->validated('direction', 'desc');

        $validSorts = ['karma', 'created_at'];

        if (! in_array($sort, $validSorts, true)) {
            $sort = 'karma';
        }

        $link = Link::findOrFail($linkId);
        $comments = $link->comments()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderBy($sort, $direction)
            ->get();

        return new CommentCollection($comments);
    }

    public function store(StoreCommentRequest $request, $linkId): JsonResponse
    {
        if (! $this->hasEnoughKarma()) {
            return $this->notEnoughKarmaResponse();
        }

        $link = Link::findOrFail($linkId);
        $comment = $this->buildCommentFromRequest($request, $link);

        $parentId = $request->validated('parent_id');
        if ($parentId !== null) {
            if (! $this->isValidParentComment($parentId, $link->id)) {
                return $this->invalidParentResponse();
            }

            $comment->parent_id = $parentId;
        }

        $comment->save();

        return response()->json([
            'data' => new CommentResource($comment),
            'message' => trans('app.comment_submitted'),
        ], Response::HTTP_CREATED);
    }

    private function hasEnoughKarma(): bool
    {
        return Auth::user()->karma >= config('app.repostea.min_karma_for_comment');
    }

    private function notEnoughKarmaResponse(): JsonResponse
    {
        return response()->json([
            'error' => trans('app.comments.not_enough_karma'),
        ], Response::HTTP_FORBIDDEN);
    }

    private function buildCommentFromRequest(StoreCommentRequest $request, Link $link): Comment
    {
        return new Comment([
            'content' => $request->validated('content'),
            'user_id' => Auth::id(),
            'link_id' => $link->id,
        ]);
    }

    private function isValidParentComment(int|string $parentId, int $linkId): bool
    {
        return Comment::where('id', $parentId)
            ->where('link_id', $linkId)
            ->exists();
    }

    private function invalidParentResponse(): JsonResponse
    {
        return response()->json([
            'message' => trans('app.invalid_parent_comment'),
        ], Response::HTTP_BAD_REQUEST);
    }

    public function vote(VoteCommentRequest $request, $commentId): JsonResponse
    {
        if ($request->validated('value') === -1 && Auth::user()?->karma < config('app.repostea.min_karma_for_negative_vote')) {
            return response()->json([
                'message' => trans('app.comments.not_enough_karma'),
            ], Response::HTTP_FORBIDDEN);
        }

        $user = Auth::user();
        $karmaChange = $request->validated('value') * ($user->karma / 6);

        $comment = Comment::findOrfail($commentId);
        $comment->votes += $request->validated('value');
        $comment->karma += $karmaChange;
        $comment->save();

        return response()->json([
            'data' => [
                'votes' => $comment->votes,
                'karma' => $comment->karma,
            ],
            'message' => trans('app.vote_registered'),
        ]);
    }

    public function show($commentId): CommentResource
    {
        $comment = Comment::findOrFail($commentId);
        $comment->load(['user', 'replies.user', 'link']);

        return new CommentResource($comment);
    }

    public function userComments(UserCommentsRequest $request)
    {
        $perPage = $request->validated('per_page', config('app.repostea.items_per_page', 15));

        $comments = Auth::user()->comments()
            ->with(['link', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return CommentResource::collection($comments);
    }
}
