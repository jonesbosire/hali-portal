<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use App\Models\Organization;
use App\Rules\SafeUrl;
use Illuminate\Http\Request;

class AdminOpportunityController extends Controller
{
    public function index()
    {
        $opportunities = Opportunity::withTrashed()
            ->with(['organization', 'postedBy'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.opportunities.index', compact('opportunities'));
    }

    public function create()
    {
        $organizations = Organization::active()->orderBy('name')->get();
        return view('admin.opportunities.form', ['opportunity' => new Opportunity(), 'organizations' => $organizations]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:job,fellowship,scholarship,internship,volunteer',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:255',
            'application_url' => ['nullable', 'url', 'max:500', new SafeUrl()],
            'deadline_at' => 'nullable|date',
            'is_members_only' => 'boolean',
            'organization_id' => 'nullable|exists:organizations,id',
            'status' => 'required|in:active,expired,filled',
        ]);

        $validated['posted_by'] = auth()->id();
        Opportunity::create($validated);

        return redirect()->route('admin.opportunities.index')->with('success', 'Opportunity created.');
    }

    public function edit(Opportunity $opportunity)
    {
        $organizations = Organization::active()->orderBy('name')->get();
        return view('admin.opportunities.form', compact('opportunity', 'organizations'));
    }

    public function update(Request $request, Opportunity $opportunity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:job,fellowship,scholarship,internship,volunteer',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:255',
            'application_url' => ['nullable', 'url', 'max:500', new SafeUrl()],
            'deadline_at' => 'nullable|date',
            'is_members_only' => 'boolean',
            'organization_id' => 'nullable|exists:organizations,id',
            'status' => 'required|in:active,expired,filled',
        ]);

        $opportunity->update($validated);
        return back()->with('success', 'Opportunity updated.');
    }

    public function destroy(Opportunity $opportunity)
    {
        $opportunity->delete();
        return back()->with('success', 'Opportunity deleted.');
    }

    public function show(Opportunity $opportunity)
    {
        return redirect()->route('opportunities.show', $opportunity);
    }
}
