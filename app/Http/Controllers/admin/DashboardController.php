<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\Message;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $days = collect(range(6, 0))->map(fn (int $daysAgo) => now()->subDays($daysAgo)->startOfDay());

        return view('admin.dasbor', [
            'counts' => [
                'pengguna' => User::query()->count(),
                'sesi_aktif' => CounselingSession::query()->where('status', CounselingSession::STATUS_AKTIF)->count(),
                'cerita' => Post::query()->count(),
                'konselor' => User::query()->where('role', User::ROLE_KONSELOR)->count(),
                'laporan_baru' => Report::query()->where('status', Report::STATUS_BARU)->count(),
                'terverifikasi' => User::query()->whereNotNull('face_registered_at')->count(),
                'belum_verifikasi' => User::query()->whereNull('face_registered_at')->count(),
            ],
            'statusSesi' => [
                'aktif' => CounselingSession::query()->where('status', CounselingSession::STATUS_AKTIF)->count(),
                'selesai' => CounselingSession::query()->where('status', CounselingSession::STATUS_SELESAI)->count(),
                'menunggu' => CounselingSession::query()->where('status', CounselingSession::STATUS_MENUNGGU)->count(),
            ],
            'reports' => Report::query()->with('reportable')->where('status', Report::STATUS_BARU)->latest()->take(2)->get(),
            'aktivitas' => $this->aktivitas(),
            'chartLabels' => $days->map(fn ($day) => $day->translatedFormat('D'))->all(),
            'chartCerita' => $days->map(fn ($day) => Post::query()->whereBetween('created_at', [$day, $day->copy()->endOfDay()])->count())->all(),
            'chartPesan' => $days->map(fn ($day) => Message::query()->whereBetween('created_at', [$day, $day->copy()->endOfDay()])->count())->all(),
        ]);
    }

    private function aktivitas(): array
    {
        return collect()
            ->merge(CounselingSession::query()->latest()->take(4)->get()->map(fn ($session) => [
                'text' => 'Sesi #'.$session->id.' '.$session->status.' · '.$session->topic,
                'time' => $session->created_at,
                'icon' => 'chat',
            ]))
            ->merge(Post::query()->latest()->take(4)->get()->map(fn ($post) => [
                'text' => 'Cerita baru #'.$post->id.' dipublikasikan',
                'time' => $post->created_at,
                'icon' => 'bubble',
            ]))
            ->merge(Report::query()->latest()->take(4)->get()->map(fn ($report) => [
                'text' => 'Laporan '.$report->reason.' · '.$report->status,
                'time' => $report->created_at,
                'icon' => 'flag',
            ]))
            ->sortByDesc('time')
            ->take(4)
            ->values()
            ->all();
    }
}
