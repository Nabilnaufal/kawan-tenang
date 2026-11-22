<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
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

    public function pengguna(): View
    {
        $users = User::query()->latest()->paginate(20);

        return view('admin.pengguna', compact('users'));
    }
}
