@extends('layouts.app')
@section('title', 'Sesi Konseling')
@section('role', 'konselor')
@section('content')
    @php($tones = ['aktif' => 'success', 'menunggu' => 'warning', 'selesai' => 'neutral'])

    <div class="mb-5">
        <h2 class="text-xl font-semibold tracking-tight sm:text-2xl">Sesi saya</h2>
        <p class="mt-1 text-sm text-muted-foreground">Hanya sesi yang ditugaskan kepadamu yang tampil di sini.</p>
    </div>

    <div class="grid gap-4 xl:grid-cols-[360px_1fr]">
        <div class="min-w-0 space-y-2.5">
            <form method="GET" action="{{ route('konselor.sesi') }}" class="flex items-center gap-2 rounded-xl bg-input px-3 py-2 text-xs text-muted-foreground neu-inset">
                <x-icon name="search" :size="15" />
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari topik sesi" aria-label="Cari sesi" class="min-w-0 flex-1 bg-transparent text-foreground placeholder:text-muted-foreground focus:outline-none">
            </form>

            <div class="flex flex-wrap gap-2 pb-1" aria-label="Filter sesi">
                @foreach (['semua' => 'Semua', 'aktif' => 'Aktif', 'menunggu' => 'Menunggu', 'selesai' => 'Selesai'] as $value => $label)
                    <a
                        href="{{ route('konselor.sesi', array_filter(['status' => $value, 'q' => $search])) }}"
                        @class([
                            'shrink-0 rounded-full px-3.5 py-1.5 text-xs font-semibold transition',
                            'bg-primary text-primary-foreground' => $status === $value,
                            'bg-card text-muted-foreground hover:text-foreground' => $status !== $value,
                        ])
                        @if ($status === $value) aria-current="page" @endif
                    >{{ $label }}</a>
                @endforeach
            </div>

            @forelse ($sessions as $sesi)
                <a
                    href="{{ route('konselor.sesi', array_filter(['session' => $sesi->id, 'status' => $status, 'q' => $search])) }}"
                    @class([
                        'flex items-start gap-3 rounded-[1.4rem] bg-card p-3.5 neu-sm transition hover:bg-muted',
                        'ring-1 ring-primary/25' => $selected?->is($sesi),
                    ])
                >
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-secondary text-secondary-foreground">
                        <x-icon name="user" :size="17" />
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="flex items-center gap-2">
                            <span class="truncate text-sm font-semibold">Anonim #{{ $sesi->user_id }}</span>
                            <span class="ml-auto shrink-0 text-xs text-muted-foreground">{{ $sesi->created_at->diffForHumans() }}</span>
                        </span>
                        <span class="mt-0.5 block truncate text-xs text-muted-foreground">{{ $sesi->topic }} · {{ $sesi->messages_count }} pesan</span>
                        <span class="mt-1.5 flex items-center gap-2">
                            <x-status :tone="$tones[$sesi->status] ?? 'neutral'">{{ ucfirst($sesi->status) }}</x-status>
                        </span>
                    </span>
                </a>
            @empty
                <div class="rounded-[1.4rem] bg-card p-6 text-center neu-sm">
                    <p class="text-sm font-semibold">Belum ada sesi</p>
                    <p class="mt-1 text-xs text-muted-foreground">Sesi yang ditugaskan akan tampil di sini.</p>
                </div>
            @endforelse
        </div>

        <div class="hidden min-w-0 xl:block">
            @if ($selected)
                <x-chat-panel
                    :session="$selected"
                    name="Anonim #{{ $selected->user_id }}"
                    role="Topik: {{ $selected->topic }}"
                    :state="ucfirst($selected->status)"
                    :anonymous="true"
                />
            @else
                <div class="flex h-full min-h-[420px] items-center justify-center rounded-[1.4rem] bg-card p-6 text-center neu-sm">
                    <p class="text-xs text-muted-foreground">Pilih sesi untuk melihat percakapan.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
