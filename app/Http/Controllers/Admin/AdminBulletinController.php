<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MemberBulletinMail;
use App\Models\MemberBulletin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminBulletinController extends Controller
{
    public function index()
    {
        $bulletins = MemberBulletin::with('createdBy')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.bulletins.index', compact('bulletins'));
    }

    public function create()
    {
        return view('admin.bulletins.form', ['bulletin' => new MemberBulletin()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $validated['created_by'] = auth()->id();
        MemberBulletin::create($validated);

        return redirect()->route('admin.bulletins.index')->with('success', 'Bulletin created.');
    }

    public function edit(MemberBulletin $bulletin)
    {
        return view('admin.bulletins.form', compact('bulletin'));
    }

    public function update(Request $request, MemberBulletin $bulletin)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $bulletin->update($validated);
        return back()->with('success', 'Bulletin updated.');
    }

    public function destroy(MemberBulletin $bulletin)
    {
        $bulletin->delete();
        return back()->with('success', 'Bulletin deleted.');
    }

    public function show(MemberBulletin $bulletin)
    {
        return view('admin.bulletins.show', compact('bulletin'));
    }

    public function send(MemberBulletin $bulletin)
    {
        $members = User::where('status', 'active')
            ->whereIn('role', ['member', 'secretariat', 'super_admin'])
            ->get();

        $count = 0;
        foreach ($members as $member) {
            try {
                Mail::to($member->email)->queue(new MemberBulletinMail($bulletin, $member));
                $count++;
            } catch (\Exception $e) {
                report($e);
            }
        }

        $bulletin->update([
            'status' => 'sent',
            'sent_at' => now(),
            'recipient_count' => $count,
        ]);

        return back()->with('success', "Bulletin sent to {$count} members.");
    }
}
