<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BubbleController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'semua');

        $posts = Post::query()
            ->where('is_hidden', false)
            ->when($filter === 'dukungan', fn ($query) => $query->where('tag', 'Perlu dukungan'))
            ->with(['comments' => fn ($query) => $query->where('is_hidden', false)->latest()])
            ->withCount([
                'likes',
                'comments' => fn ($query) => $query->where('is_hidden', false),
            ])
            ->when(auth()->check(), fn ($query) => $query->withExists([
                'likes as liked' => fn ($likes) => $likes->where('user_id', auth()->id()),
            ]))
            ->when($filter === 'populer', fn ($query) => $query->orderByDesc('likes_count'), fn ($query) => $query->latest())
            ->paginate(10)
            ->withQueryString();

        return view('bubble', compact('posts', 'filter'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:500'],
            'tag' => ['nullable', 'in:Perlu dukungan'],
        ]);

        $request->user()->posts()->create($data);

        return back()->with('status', 'Ceritamu sudah tayang sebagai Anonim.');
    }

    public function komentar(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:300'],
        ]);

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        return back()->with('status', 'Komentar terkirim sebagai Anonim.');
    }

    public function suka(Request $request, Post $post): JsonResponse|RedirectResponse
    {
        $hasil = $post->likes()->toggle($request->user()->id);
        $liked = count($hasil['attached']) > 0;

        if ($request->wantsJson()) {
            return response()->json(['liked' => $liked, 'likes' => $post->likes()->count()]);
        }

        return back();
    }

    public function lapor(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'reason' => ['required', 'in:Ujaran kebencian,Pelecehan atau intimidasi,Membahayakan diri sendiri,Spam atau penipuan'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $post->reports()->create([
            'reporter_id' => $request->user()->id,
            'reason' => $data['reason'],
            'note' => $data['note'] ?? null,
        ]);

        return back()->with('status', 'Laporanmu sudah masuk antrean moderasi.');
    }
}
