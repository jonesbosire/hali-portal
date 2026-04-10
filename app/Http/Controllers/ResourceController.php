<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Resource::with('uploader')
            ->orderByDesc('created_at');

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!auth()->user()->isMember() && !auth()->user()->isAdmin()) {
            $query->where('is_members_only', false);
        }

        $resources = $query->paginate(16)->withQueryString();

        return view('resources.index', compact('resources'));
    }

    public function download(Resource $resource)
    {
        if ($resource->is_members_only && !auth()->user()->isMember() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $resource->incrementDownloads();

        if ($resource->external_url) {
            return redirect($resource->external_url);
        }

        if ($resource->file_path && Storage::disk('public')->exists($resource->file_path)) {
            return Storage::disk('public')->download($resource->file_path, $resource->title);
        }

        abort(404, 'Resource file not found.');
    }
}
