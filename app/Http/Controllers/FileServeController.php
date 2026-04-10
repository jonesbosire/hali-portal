<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileServeController extends Controller
{
    /**
     * Serve a private uploaded file after verifying the user is authenticated.
     * Files are stored outside the web root — they cannot be accessed directly.
     */
    public function serve(Request $request, string $path)
    {
        // Must be authenticated
        if (!$request->user()) {
            abort(403);
        }

        // Prevent path traversal — normalize and reject anything suspicious
        $path = ltrim($path, '/');
        if (str_contains($path, '..') || str_contains($path, "\0")) {
            abort(400);
        }

        $disk = Storage::disk('uploads');

        if (!$disk->exists($path)) {
            abort(404);
        }

        $mimeType = $disk->mimeType($path);

        // Only serve known image types — never serve scripts or HTML
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $allowed, true)) {
            abort(403);
        }

        return response($disk->get($path), 200)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'private, max-age=3600')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('Content-Disposition', 'inline');
    }
}
