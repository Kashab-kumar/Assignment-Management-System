<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function index()
    {
        $invitations = Invitation::with('inviter')->latest()->paginate(20);
        return view('admin.invitations.index', compact('invitations'));
    }

    public function create()
    {
        return view('admin.invitations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:teacher,student',
        ]);

        $token = Str::random(32);

        $invitation = Invitation::create([
            'token' => $token,
            'role' => $validated['role'],
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(30), // 30 days validity
        ]);

        return redirect()->route('admin.invitations.show', $invitation)
            ->with('success', 'Invitation link created successfully!');
    }

    public function show(Invitation $invitation)
    {
        $inviteLink = route('register.invitation', $invitation->token);
        return view('admin.invitations.show', compact('invitation', 'inviteLink'));
    }

    public function destroy(Invitation $invitation)
    {
        $invitation->delete();
        return back()->with('success', 'Invitation deleted successfully!');
    }
}
