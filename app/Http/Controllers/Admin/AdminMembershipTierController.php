<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminMembershipTierController extends Controller
{
    public function index()
    {
        $tiers = MembershipPlan::orderBy('display_order')->orderBy('created_at')->get();
        return view('admin.tiers.index', compact('tiers'));
    }

    public function create()
    {
        return view('admin.tiers.form', ['tier' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100|unique:membership_plans,name',
            'tier_type'     => 'required|in:member,friend',
            'description'   => 'nullable|string|max:500',
            'price_usd'     => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:annual,monthly,one_time',
            'features'      => 'nullable|array',
            'features.*'    => 'string|max:200',
            'max_users'     => 'nullable|integer|min:1',
            'is_active'     => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $data['slug']     = Str::slug($data['name']);
        $data['features'] = array_values(array_filter($data['features'] ?? []));

        $tier = MembershipPlan::create($data);

        activity()->causedBy(auth()->user())
            ->log("Created membership tier: {$tier->name}");

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'tier' => $tier]);
        }

        return redirect()->route('admin.tiers.index')->with('success', "Tier \"{$tier->name}\" created.");
    }

    public function edit(MembershipPlan $tier)
    {
        return view('admin.tiers.form', compact('tier'));
    }

    public function update(Request $request, MembershipPlan $tier)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100|unique:membership_plans,name,' . $tier->id,
            'tier_type'     => 'required|in:member,friend',
            'description'   => 'nullable|string|max:500',
            'price_usd'     => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:annual,monthly,one_time',
            'features'      => 'nullable|array',
            'features.*'    => 'string|max:200',
            'max_users'     => 'nullable|integer|min:1',
            'is_active'     => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $data['slug']     = Str::slug($data['name']);
        $data['features'] = array_values(array_filter($data['features'] ?? []));

        $tier->update($data);

        activity()->causedBy(auth()->user())
            ->log("Updated membership tier: {$tier->name}");

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'tier' => $tier->fresh()]);
        }

        return redirect()->route('admin.tiers.index')->with('success', "Tier \"{$tier->name}\" updated.");
    }

    public function toggleActive(MembershipPlan $tier)
    {
        $tier->update(['is_active' => ! $tier->is_active]);

        activity()->causedBy(auth()->user())
            ->log(($tier->is_active ? 'Activated' : 'Deactivated') . " membership tier: {$tier->name}");

        return response()->json(['is_active' => $tier->is_active]);
    }

    public function destroy(MembershipPlan $tier)
    {
        if ($tier->members()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a tier that has members assigned to it. Deactivate it instead.',
            ], 422);
        }

        $name = $tier->name;
        $tier->delete();

        activity()->causedBy(auth()->user())
            ->log("Deleted membership tier: {$name}");

        return response()->json(['success' => true, 'message' => "Tier \"{$name}\" deleted."]);
    }
}
