<?php

namespace App\Http\Controllers;

use App\Models\DirectoryListing;
use App\Models\Organization;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Organization::active()
            ->with(['directoryListing', 'members'])
            ->orderBy('name');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($country = $request->get('country')) {
            $query->where('country', $country);
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        $organizations = $query->paginate(12)->withQueryString();

        $countries = Organization::active()->whereNotNull('country')
            ->distinct()->pluck('country')->sort()->values();

        return view('directory.index', compact('organizations', 'countries'));
    }

    public function show(string $slug)
    {
        $organization = Organization::where('slug', $slug)
            ->with(['members', 'directoryListing', 'opportunities' => fn($q) => $q->active()->take(3)])
            ->firstOrFail();

        $teamMembers = $organization->members()
            ->wherePivot('is_primary', false)
            ->orWherePivot('role', 'staff')
            ->take(10)
            ->get();

        return view('directory.show', compact('organization', 'teamMembers'));
    }
}
