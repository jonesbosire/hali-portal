<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Opportunity;
use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $upcomingEvents = Event::published()
            ->upcoming()
            ->when(!$user->isAdmin(), fn($q) => $q->where(function ($q) {
                $q->where('is_members_only', false)->orWhere('is_members_only', true);
            }))
            ->with(['creator:id,name', 'registrations'])
            ->take(3)
            ->get();

        $latestPosts = Post::published()
            ->select(['id', 'title', 'excerpt', 'slug', 'cover_image', 'type', 'is_members_only', 'published_at', 'author_id', 'organization_id'])
            ->with(['author:id,name', 'organization:id,name'])
            ->whereIn('type', ['update', 'story', 'blog', 'bulletin'])
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

        $latestOpportunities = Opportunity::active()
            ->with('organization')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        // Profile completeness
        $completeness = $this->calcProfileCompleteness($user);

        // Admin stats
        $adminStats = null;
        if ($user->isAdmin()) {
            $adminStats = [
                'total_members' => \App\Models\User::whereIn('role', ['member', 'friend'])->count(),
                'pending_members' => \App\Models\User::where('status', 'pending')->count(),
                'upcoming_events' => Event::published()->upcoming()->count(),
                'active_opportunities' => Opportunity::active()->count(),
            ];
        }

        return view('dashboard.index', compact(
            'upcomingEvents', 'latestPosts', 'latestOpportunities', 'completeness', 'adminStats'
        ));
    }

    private function calcProfileCompleteness($user): array
    {
        $checks = [
            'name' => !empty($user->name),
            'avatar' => !empty($user->avatar),
            'bio' => !empty($user->bio),
            'title' => !empty($user->title),
            'email_verified' => $user->email_verified_at !== null,
            'organization' => $user->organizations()->exists(),
        ];

        $done = collect($checks)->filter()->count();
        $total = count($checks);

        return [
            'percent' => (int) round($done / $total * 100),
            'missing' => collect($checks)->reject(fn($v) => $v)->keys()->map(fn($k) => match($k) {
                'name' => 'Add your full name',
                'avatar' => 'Upload a profile photo',
                'bio' => 'Write a short bio',
                'title' => 'Add your job title',
                'email_verified' => 'Verify your email address',
                'organization' => 'Join your organization',
                default => $k
            })->take(2)->values()->all(),
        ];
    }
}
