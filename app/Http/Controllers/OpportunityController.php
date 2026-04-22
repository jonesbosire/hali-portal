<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Rules\SafeUrl;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    public function index(Request $request)
    {
        $query = Opportunity::active()
            ->with(['organization', 'postedBy'])
            ->orderByDesc('created_at');

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($location = $request->get('location')) {
            $query->where('location', 'like', "%{$location}%");
        }

        if (!auth()->user()->isMember() && !auth()->user()->isAdmin()) {
            $query->public();
        }

        $opportunities = $query->paginate(15)->withQueryString();

        return view('opportunities.index', compact('opportunities'));
    }

    public function show(Opportunity $opportunity)
    {
        abort_if($opportunity->status !== 'active', 404);

        if ($opportunity->is_members_only && !auth()->user()->isMember() && !auth()->user()->isAdmin()) {
            abort(403, 'This opportunity is for members only.');
        }

        return view('opportunities.show', compact('opportunity'));
    }

    public function create()
    {
        $organizations = auth()->user()->organizations;
        return view('opportunities.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        // Collect the IDs of organizations this user actually belongs to
        $userOrgIds = auth()->user()->organizations()->pluck('organizations.id')->toArray();

        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'type'            => 'required|in:job,fellowship,scholarship,internship,volunteer',
            'description'     => 'required|string',
            'requirements'    => 'nullable|string',
            'location'        => 'nullable|string|max:255',
            'salary_range'    => 'nullable|string|max:255',
            'application_url' => ['nullable', 'url', 'max:500', new SafeUrl()],
            'deadline_at'     => 'nullable|date|after:today',
            'is_members_only' => 'boolean',
            // Ensure the submitted org ID is one the user actually belongs to
            'organization_id' => ['nullable', 'in:' . implode(',', $userOrgIds)],
        ]);

        $validated['posted_by'] = auth()->id();
        $validated['status'] = 'active';

        $opportunity = Opportunity::create($validated);

        return redirect()->route('opportunities.show', $opportunity)
            ->with('success', 'Opportunity posted successfully!');
    }
}
