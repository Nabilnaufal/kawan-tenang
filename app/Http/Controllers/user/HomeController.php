<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->where('is_hidden', false)
            ->with(['comments' => fn ($query) => $query->where('is_hidden', false)->latest()])
            ->withCount([
                'likes',
                'comments' => fn ($query) => $query->where('is_hidden', false),
            ])
            ->when(auth()->check(), fn ($query) => $query->withExists([
                'likes as liked' => fn ($likes) => $likes->where('user_id', auth()->id()),
            ]))
            ->latest()
            ->take(2)
            ->get();

        $counselors = User::query()
            ->where('role', User::ROLE_KONSELOR)
            ->orderBy('name')
            ->take(3)
            ->get();

        $stats = [
            ['value' => Post::query()->where('is_hidden', false)->count(), 'label' => 'cerita dibagikan'],
            ['value' => User::query()->where('role', User::ROLE_KONSELOR)->count(), 'label' => 'konselor terdaftar'],
            ['value' => CounselingSession::query()->count(), 'label' => 'sesi konseling'],
        ];

        return view('home', compact('posts', 'counselors', 'stats'));
    }
}
