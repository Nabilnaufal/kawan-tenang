<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CounselingSession;
use App\Models\Message;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $days = collect(range(6, 0))->map(fn (int $daysAgo) => now()->subDays($daysAgo)->startOfDay());

        return view('admin.dasbor', [
            'counts' => [
                'pengguna' => User::query()->count(),
                'sesi_aktif' => CounselingSession::query()->where('status', 'aktif')->count(),
                'cerita' => Post::query()->count(),
                'konselor' => User::query()->where('role', 'konselor')->count(),
                'laporan_baru' => Report::query()->where('status', 'baru')->count(),
                'terverifikasi' => User::query()->whereNotNull('face_registered_at')->count(),
                'belum_verifikasi' => User::query()->whereNull('face_registered_at')->count(),
            ],
            'statusSesi' => [
                'aktif' => CounselingSession::query()->where('status', 'aktif')->count(),
                'selesai' => CounselingSession::query()->where('status', 'selesai')->count(),
                'menunggu' => CounselingSession::query()->where('status', 'menunggu')->count(),
            ],
            'reports' => Report::query()->with('reportable')->where('status', 'baru')->latest()->take(2)->get(),
            'aktivitas' => $this->aktivitas(),
            'chartLabels' => $days->map(fn ($day) => $day->translatedFormat('D'))->all(),
            'chartCerita' => $days->map(fn ($day) => Post::query()->whereBetween('created_at', [$day, $day->copy()->endOfDay()])->count())->all(),
            'chartPesan' => $days->map(fn ($day) => Message::query()->whereBetween('created_at', [$day, $day->copy()->endOfDay()])->count())->all(),
        ]);
    }

    public function laporan(): View
    {
        $reports = Report::query()->with(['reporter', 'reportable'])->latest()->paginate(15);

        return view('admin.laporan', compact('reports'));
    }

    public function updateLaporan(Request $request, Report $report): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:baru,ditinjau,ditindaklanjuti,ditolak'],
            'action' => ['nullable', 'in:sembunyikan'],
        ]);

        $report->update(['status' => $data['status']]);

        if (($data['action'] ?? null) === 'sembunyikan' && ($report->reportable instanceof Post || $report->reportable instanceof Comment)) {
            $report->reportable->update(['is_hidden' => true]);
        }

        return back()->with('status', 'Laporan berhasil diperbarui.');
    }

    public function pengguna(Request $request): View
    {
        $role = $request->query('role', 'semua');
        $search = trim((string) $request->query('q'));

        $users = User::query()
            ->when(in_array($role, ['pengguna', 'konselor', 'admin'], true), fn ($query) => $query->where('role', $role))
            ->when($search !== '', fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%')
                ->orWhere('nim', 'like', '%'.$search.'%')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.pengguna', compact('users', 'role', 'search'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is($request->user()), 403, 'Tidak bisa mengubah peran sendiri.');

        $data = $request->validate([
            'role' => ['required', 'in:pengguna,konselor,admin'],
        ]);

        $user->update($data);

        return back()->with('status', 'Peran pengguna diperbarui.');
    }

    public function verifikasi(): View
    {
        $users = User::query()->orderByDesc('face_registered_at')->paginate(20);

        return view('admin.verifikasi', [
            'users' => $users,
            'counts' => [
                'terdaftar' => User::query()->whereNotNull('face_registered_at')->count(),
                'belum' => User::query()->whereNull('face_registered_at')->count(),
            ],
        ]);
    }

    public function resetWajah(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is($request->user()), 403, 'Tidak bisa menghapus verifikasi sendiri.');

        $user->update(['face_descriptor' => null, 'face_registered_at' => null]);

        return back()->with('status', 'Data wajah pengguna dihapus.');
    }

    /** Latest platform activity derived from stored records. */
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
