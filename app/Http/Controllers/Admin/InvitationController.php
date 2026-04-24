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

    public function create(Request $request)
    {
        $role = $request->query('role', null);
        $courseId = $request->query('course_id', null);
        $courses = \App\Models\Course::all();
        return view('admin.invitations.create', compact('role', 'courseId', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:teacher,student',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $token = Str::random(32);

        $invitation = Invitation::create([
            'token' => $token,
            'role' => $validated['role'],
            'course_id' => $validated['course_id'] ?? null,
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(30), // 30 days validity
        ]);

        return redirect()->route('admin.invitations.show', $invitation)
            ->with('success', 'Invitation link created successfully!');
    }

    public function show(Invitation $invitation)
    {
        $invitePath = route('register.invitation', ['token' => $invitation->token], false);
        $inviteLink = request()->getSchemeAndHttpHost() . $invitePath;
        return view('admin.invitations.show', compact('invitation', 'inviteLink'));
    }

    public function destroy(Invitation $invitation)
    {
        $invitation->delete();
        return back()->with('success', 'Invitation deleted successfully!');
    }
}
