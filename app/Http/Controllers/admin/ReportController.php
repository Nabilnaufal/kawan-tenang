<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $reports = Report::query()->with(['reporter', 'reportable'])->latest()->paginate(15);

        return view('admin.laporan', compact('reports'));
    }

    public function update(Request $request, Report $report): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([
                Report::STATUS_BARU,
                Report::STATUS_DITINJAU,
                Report::STATUS_DITINDAKLANJUTI,
                Report::STATUS_DITOLAK,
            ])],
            'action' => ['nullable', 'in:sembunyikan'],
        ]);

        $report->update(['status' => $data['status']]);

        if (($data['action'] ?? null) === 'sembunyikan' && ($report->reportable instanceof Post || $report->reportable instanceof Comment)) {
            $report->reportable->update(['is_hidden' => true]);
        }

        return back()->with('status', 'Laporan berhasil diperbarui.');
    }
}
