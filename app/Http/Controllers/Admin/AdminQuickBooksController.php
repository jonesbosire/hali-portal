<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\QuickBooksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AdminQuickBooksController extends Controller
{
    public function __construct(private QuickBooksService $qb) {}

    public function index()
    {
        return view('admin.quickbooks.index', [
            'connected' => $this->qb->isConnected(),
        ]);
    }

    public function authorize()
    {
        $state = Str::random(32);
        Cache::put('qb_oauth_state', $state, now()->addMinutes(15));

        return redirect($this->qb->getAuthorizationUrl($state));
    }

    public function callback(Request $request)
    {
        if ($request->query('error')) {
            return redirect()->route('admin.quickbooks.index')
                ->with('error', 'QuickBooks authorization was denied: ' . $request->query('error_description', 'Unknown error'));
        }

        $state = $request->query('state');
        if ($state !== Cache::get('qb_oauth_state')) {
            return redirect()->route('admin.quickbooks.index')
                ->with('error', 'OAuth state mismatch — please try again.');
        }

        Cache::forget('qb_oauth_state');

        try {
            $this->qb->exchangeCodeForTokens(
                code:    $request->query('code'),
                realmId: $request->query('realmId'),
            );

            activity()->causedBy(auth()->user())->log('QuickBooks connected');

            return redirect()->route('admin.quickbooks.index')
                ->with('success', 'QuickBooks connected successfully.');

        } catch (\Exception $e) {
            report($e);
            return redirect()->route('admin.quickbooks.index')
                ->with('error', 'Failed to connect QuickBooks: ' . $e->getMessage());
        }
    }

    public function disconnect()
    {
        Cache::forget('qb_tokens');
        Cache::forget('qb_realm_id');

        activity()->causedBy(auth()->user())->log('QuickBooks disconnected');

        return redirect()->route('admin.quickbooks.index')
            ->with('success', 'QuickBooks disconnected.');
    }
}
