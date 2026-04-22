<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Invitation;
use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Cache stats for 5 minutes — these are aggregates that don't need to be real-time
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_members'       => User::whereIn('role', ['member', 'friend'])->where('status', 'active')->count(),
                'pending_members'     => User::where('status', 'pending')->count(),
                'total_organizations' => Organization::active()->count(),
                'upcoming_events'     => Event::published()->upcoming()->count(),
                'active_opportunities'=> Opportunity::active()->count(),
                'published_posts'     => Post::published()->count(),
                'pending_invitations' => Invitation::whereNull('accepted_at')->where('expires_at', '>', now())->count(),
            ];
        });

        $recentMembers = User::where('status', '!=', 'archived')
            ->with('organizations:id,name')
            ->orderByDesc('created_at')
            ->take(5)
            ->get(['id', 'name', 'email', 'role', 'status', 'avatar', 'created_at']);

        $upcomingEvents = Event::published()
            ->upcoming()
            ->withCount('registrations')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentMembers', 'upcomingEvents'));
    }
}
