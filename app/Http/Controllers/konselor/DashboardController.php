<?php

namespace App\Http\Controllers\konselor;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
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
                'aktif' => $sessions->where('status', CounselingSession::STATUS_AKTIF)->count(),
                'menunggu' => $sessions->where('status', CounselingSession::STATUS_MENUNGGU)->count(),
                'selesai' => $sessions->where('status', CounselingSession::STATUS_SELESAI)->count(),
            ],
            'unreadSessions' => $sessions->where('unread_count', '>', 0)->take(3),
            'notes' => $sessions->whereNotNull('counselor_note')->take(3),
            'chartLabels' => $days->map(fn ($day) => $day->translatedFormat('D'))->all(),
            'chartBaru' => $days->map(fn ($day) => $sessions->whereBetween('created_at', [$day, $day->copy()->endOfDay()])->count())->all(),
            'chartSelesai' => $days->map(fn ($day) => $sessions->whereNotNull('closed_at')->whereBetween('closed_at', [$day, $day->copy()->endOfDay()])->count())->all(),
        ]);
    }
}
