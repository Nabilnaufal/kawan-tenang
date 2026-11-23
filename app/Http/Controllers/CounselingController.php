<?php

namespace App\Http\Controllers;

use App\Models\CounselingSession;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CounselingController extends Controller
{
    public function index(Request $request): View
    {
        $sessions = CounselingSession::query()
            ->where(fn ($query) => $query->where('user_id', $request->user()->id)->orWhere('counselor_id', $request->user()->id))
            ->with(['user', 'counselor'])
            ->withCount('messages')
            ->latest()
            ->get();

        return view('konseling.index', compact('sessions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'topic' => ['required', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $counselor = User::query()->where('role', 'konselor')->oldest('id')->first();

        $session = $request->user()->counselingSessions()->create([
            ...$data,
            'counselor_id' => $counselor?->id,
            'status' => $counselor ? 'aktif' : 'menunggu',
        ]);

        return redirect()->route('konseling.show', $session)->with('status', 'Pengajuan sesi berhasil dikirim.');
    }

    public function show(Request $request, CounselingSession $session): View
    {
        $this->ensureParticipant($request, $session);
        $session->load(['user', 'counselor', 'messages.sender']);

        if ($session->messages->isNotEmpty()) {
            $session->messages()->whereNull('read_at')->where('sender_id', '!=', $request->user()->id)->update(['read_at' => now()]);
        }

        $role = $request->routeIs('konselor.*') ? 'konselor' : 'pengguna';
        $back = $role === 'konselor' ? route('konselor.sesi') : route('konseling.index');

        return view('konseling.show', compact('session', 'role', 'back'));
    }

    public function message(Request $request, CounselingSession $session): RedirectResponse
    {
        $this->ensureParticipant($request, $session);

        abort_if($session->status === 'selesai', 422, 'Sesi ini sudah selesai.');

        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);
        $session->messages()->create(['sender_id' => $request->user()->id, 'body' => $data['body']]);

        return back();
    }

    public function close(Request $request, CounselingSession $session): RedirectResponse
    {
        $this->ensureParticipant($request, $session);
        $session->update(['status' => 'selesai', 'closed_at' => now()]);

        return back()->with('status', 'Sesi konseling ditandai selesai.');
    }

    private function ensureParticipant(Request $request, CounselingSession $session): void
    {
        abort_unless($session->user_id === $request->user()->id || $session->counselor_id === $request->user()->id, 403);
    }
}
