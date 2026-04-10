<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class AdminOrganizationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255|unique:organizations,name',
            'country' => 'nullable|string|max:100',
            'type'    => 'nullable|in:member,friend',
        ]);

        $validated['status'] = 'active';

        $org = Organization::create($validated);

        return response()->json([
            'id'   => $org->id,
            'name' => $org->name,
        ], 201);
    }
}
