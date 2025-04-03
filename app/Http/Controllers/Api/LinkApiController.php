<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LinkIndexRequest;
use App\Http\Requests\LinkPendingRequest;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UserVotedRequest;
use App\Http\Requests\VoteLinkRequest;
use App\Http\Resources\LinkCollection;
use App\Http\Resources\LinkResource;
use App\Models\Link;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LinkApiController extends Controller
{
    public function index(LinkIndexRequest $request): LinkCollection
    {
        $sort = $request->validated('sort', 'promoted_at');
        $direction = $request->validated('direction', 'desc');
        $perPage = $request->validated('per_page', config('app.repostea.items_per_page'));
        $interval = $request->validated('interval');

        $validSorts = ['promoted_at', 'votes', 'karma'];

        if (! in_array($sort, $validSorts, true)) {
            $sort = 'votes';
        }

        $query = Link::published()
            ->with(['user', 'tags'])
            ->withCount('comments');

        if (in_array($sort, ['votes', 'karma'], true)) {
            $from = now()->subMinutes($interval)->startOfDay();
            $query->where('promoted_at', '>=', $from);
        }

        $links = $query->orderBy($sort, $direction)->paginate($perPage);

        return new LinkCollection($links);
    }

    public function pending(LinkPendingRequest $request): LinkCollection
    {
        $sort = $request->validated('sort', 'created_at');
        $direction = $request->validated('direction', 'desc');
        $perPage = $request->validated('per_page', config('app.repostea.items_per_page', 15));

        $validSorts = ['created_at', 'votes', 'karma'];

        if (! in_array($sort, $validSorts, true)) {
            $sort = 'created_at';
        }

        $links = Link::pending()
            ->with(['user', 'tags'])
            ->withCount('comments')
            ->orderBy($sort, $direction)
            ->paginate($perPage);

        return new LinkCollection($links);
    }

    public function store(StoreLinkRequest $request): JsonResponse
    {
        $link = new Link;
        $link->title = $request->validated('title');
        $link->url = $request->validated('url');
        $link->content = $request->validated('content');
        $link->description = $request->validated('description');
        $link->nsfw = $request->validated('nsfw', false);
        $link->karma = config('app.repostea.initial_karma');
        $link->user_id = Auth::id();
        $link->status = 'pending';

        $link->save();

        $link->tags()->attach($request->validated('tags'));

        $vote = new Vote;
        $vote->user_id = Auth::id();
        $vote->link_id = $link->id;
        $vote->value = 1;
        $vote->karma_value = Auth::user()?->karma / 6;
        $vote->save();

        $link->votes = 1;
        $link->karma = $vote->karma_value;
        $link->save();

        return response()->json([
            'data' => new LinkResource($link),
            'message' => trans('app.link_submit_success'),
        ], Response::HTTP_CREATED);
    }

    public function show(Link $link): LinkResource
    {

        $link->load(['user', 'tags', 'comments.user', 'comments.replies.user']);
        $link->increment('clicks');

        return new LinkResource($link);
    }

    public function vote(VoteLinkRequest $request, $linkId): JsonResponse
    {

        $link = Link::findOrFail($linkId);
        $existingVote = $link->votesList()->where('user_id', Auth::id())->exists();
        if ($existingVote) {
            return response()->json(['message' => trans('app.already_voted')], 400);
        }

        $value = $request->validated('value');
        if ($value === -1 && Auth::user()?->karma < config('app.repostea.min_karma_for_negative_vote')) {
            return response()->json(['error' => trans('app.not_enough_karma')], 403);
        }

        $user = Auth::user();
        $karmaValue = $value * ($user?->karma / 6);

        $vote = $link->votesList()->create([
            'user_id' => $user?->id,
            'value' => $value,
            'karma_value' => $karmaValue,
        ]);

        $link->votes = $link->votesList()->sum('value');
        $link->karma = $link->votesList()->sum('karma_value');
        $link->save();

        return response()->json([
            'data' => [
                'votes' => $link->votes,
                'karma' => $link->karma,
            ],
            'message' => trans('app.vote_registered'),
        ]);
    }

    public function userVoted(UserVotedRequest $request): LinkCollection
    {
        $perPage = $request->validated('per_page', config('app.repostea.items_per_page', 15));

        $links = Link::whereHas('votesList', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['user', 'tags'])
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return new LinkCollection($links);
    }
}
