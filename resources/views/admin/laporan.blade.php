@extends('layouts.app')
@section('title', 'Laporan & Moderasi')
@section('role', 'admin')
@section('content')
    <div class="mb-5"><h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Laporan & moderasi</h2><p class="mt-1 text-sm text-muted-foreground">Tinjau laporan komunitas. Identitas pelapor tetap anonim.</p></div>

    @if (session('status'))<p class="mb-4 rounded-xl bg-success/10 p-3 text-xs font-medium text-success">{{ session('status') }}</p>@endif

    <section class="rounded-[1.4rem] bg-card p-4 neu-sm sm:p-5">
        <div class="flex items-center justify-between gap-2"><h3 class="text-sm font-semibold">Antrean laporan</h3><span class="text-xs text-muted-foreground">{{ $reports->total() }} laporan</span></div>
        <div class="mt-4 space-y-3">
            @forelse ($reports as $report)
                <article class="rounded-xl bg-muted/50 p-4">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div><div class="flex items-center gap-2"><x-status :tone="$report->status === 'baru' ? 'warning' : 'info'">{{ ucfirst($report->status) }}</x-status><span class="text-xs text-muted-foreground">{{ $report->created_at->diffForHumans() }}</span></div><h4 class="mt-2 text-sm font-semibold">{{ $report->reason }}</h4><p class="mt-1 text-xs text-muted-foreground">{{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }}</p>@if($report->note)<p class="mt-2 text-xs">{{ $report->note }}</p>@endif</div>
                        <form method="POST" action="{{ route('admin.laporan.update', $report) }}" class="flex flex-wrap items-center gap-2">@csrf @method('PATCH')<select name="status" class="rounded-xl bg-input px-3 py-2 text-xs neu-inset"><option value="baru" @selected($report->status === 'baru')>Baru</option><option value="ditinjau" @selected($report->status === 'ditinjau')>Ditinjau</option><option value="ditindaklanjuti" @selected($report->status === 'ditindaklanjuti')>Ditindaklanjuti</option><option value="ditolak" @selected($report->status === 'ditolak')>Ditolak</option></select><label class="flex items-center gap-1 text-xs"><input type="checkbox" name="action" value="sembunyikan"> Sembunyikan</label><button class="btn-lime px-3 py-2 text-xs">Simpan</button></form>
                    </div>
                </article>
            @empty
                <div class="rounded-xl bg-muted/50 p-6 text-center"><p class="text-sm font-semibold">Belum ada laporan</p><p class="mt-1 text-xs text-muted-foreground">Antrean moderasi masih kosong.</p></div>
            @endforelse
        </div>
        <div class="mt-4">{{ $reports->links() }}</div>
    </section>
@endsection
