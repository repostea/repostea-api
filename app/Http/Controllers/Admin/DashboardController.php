<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use stdClass;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('last_active_at', '>=', Carbon::now()->subDays(7))->count(),
            'total_links' => Link::count(),
            'published_links' => Link::where('status', 'published')->count(),
            'pending_links' => Link::where('status', 'pending')->count(),
            'total_comments' => Comment::count(),
            'total_votes' => Vote::count(),
            'total_tags' => Tag::count(),
        ];

        $recentLinks = Link::with('user', 'tags')
            ->latest()
            ->limit(5)
            ->get();

        $recentComments = Comment::with('user', 'link')
            ->latest()
            ->limit(5)
            ->get();

        $newUsers = User::latest()
            ->limit(5)
            ->get();

        $lastWeekStats = $this->getLastWeekStats();

        $topUsers = User::orderBy('karma', 'desc')
            ->limit(5)
            ->get();

        $popularLinks = Link::orderBy('votes', 'desc')
            ->where('status', 'published')
            ->limit(5)
            ->get();

        $popularTags = Tag::withCount('links')
            ->orderBy('links_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentLinks',
            'recentComments',
            'newUsers',
            'lastWeekStats',
            'topUsers',
            'popularLinks',
            'popularTags'
        ));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function getLastWeekStats(): array
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $dates = [];
        $currentDate = clone $startDate;

        while ($currentDate <= $endDate) {
            $dates[$currentDate->format('Y-m-d')] = [
                'date' => $currentDate->format('d/m'),
                'users' => 0,
                'links' => 0,
                'comments' => 0,
            ];
            $currentDate->addDay();
        }

        /** @var array<stdClass> $newUsers */
        $newUsers = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->toArray();

        foreach ($newUsers as $stat) {
            if (isset($dates[$stat->date])) {
                $dates[$stat->date]['users'] = $stat->count;
            }
        }

        /** @var array<stdClass> $newLinks */
        $newLinks = Link::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->toArray();

        foreach ($newLinks as $stat) {
            if (isset($dates[$stat->date])) {
                $dates[$stat->date]['links'] = $stat->count;
            }
        }

        /** @var array<stdClass> $newComments */
        $newComments = Comment::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->toArray();

        foreach ($newComments as $stat) {
            if (isset($dates[$stat->date])) {
                $dates[$stat->date]['comments'] = $stat->count;
            }
        }

        return array_values($dates);
    }
}
