<?php

namespace App\Http\Controllers;

use App\Models\CounselingSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CounselorController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();

        $sessions = $user->assignedSessions()
            ->with('user')
            ->withCount([
                'messages',
                'messages as unread_count' => fn ($query) => $query->whereNull('read_at')->where('sender_id', '!=', $user->id),
            ])
            ->latest()
            ->get();

        $days = collect(range(6, 0))->map(fn (int $daysAgo) => now()->subDays($daysAgo)->startOfDay());

        return view('konselor.dasbor', [
            'user' => $user,
            'sessions' => $sessions,
            'counts' => [
                'aktif' => $sessions->where('status', 'aktif')->count(),
                'menunggu' => $sessions->where('status', 'menunggu')->count(),
                'selesai' => $sessions->where('status', 'selesai')->count(),
            ],
            'unreadSessions' => $sessions->where('unread_count', '>', 0)->take(3),
            'notes' => $sessions->whereNotNull('counselor_note')->take(3),
            'chartLabels' => $days->map(fn ($day) => $day->translatedFormat('D'))->all(),
            'chartBaru' => $days->map(fn ($day) => $sessions->whereBetween('created_at', [$day, $day->copy()->endOfDay()])->count())->all(),
            'chartSelesai' => $days->map(fn ($day) => $sessions->whereNotNull('closed_at')->whereBetween('closed_at', [$day, $day->copy()->endOfDay()])->count())->all(),
        ]);
    }

    public function sessions(Request $request): View
    {
        $status = $request->query('status', 'semua');
        $search = trim((string) $request->query('q'));

        $sessions = $request->user()->assignedSessions()
            ->with('user')
            ->withCount('messages')
            ->when(in_array($status, ['aktif', 'menunggu', 'selesai'], true), fn ($query) => $query->where('status', $status))
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
