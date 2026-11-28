<?php

namespace App\Http\Controllers\konselor;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SessionController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'semua');
        $search = trim((string) $request->query('q'));

        $sessions = $request->user()->assignedSessions()
            ->with('user')
            ->withCount('messages')
            ->when(in_array($status, [
                CounselingSession::STATUS_AKTIF,
                CounselingSession::STATUS_MENUNGGU,
                CounselingSession::STATUS_SELESAI,
            ], true), fn ($query) => $query->where('status', $status))
            ->when($search !== '', fn ($query) => $query->where('topic', 'like', '%'.$search.'%'))
            ->latest()
            ->get();

        $selected = $sessions->firstWhere('id', (int) $request->query('session')) ?? $sessions->first();
        $selected?->load(['messages.sender']);

        return view('konselor.sesi', compact('sessions', 'selected', 'status', 'search'));
    }

    public function note(Request $request, CounselingSession $session): RedirectResponse
    {
        abort_unless($session->counselor_id === $request->user()->id, 403);

        $data = $request->validate([
            'counselor_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $session->update($data);

        return back()->with('status', 'Catatan sesi disimpan.');
    }

    public function availability(Request $request): RedirectResponse
    {
        $request->user()->update(['is_available' => $request->boolean('is_available')]);

        return back()->with('status', 'Ketersediaan diperbarui.');
    }
}
