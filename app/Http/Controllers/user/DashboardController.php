<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\CounselingSession;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $sessions = $user->counselingSessions()
            ->with('counselor')
            ->withCount('messages')
            ->latest()
            ->get();

        $posts = $user->posts()
            ->withCount(['likes', 'comments'])
            ->latest()
            ->take(3)
            ->get();

        $latestPosts = Post::query()
            ->where('is_hidden', false)
            ->with(['comments' => fn ($query) => $query->where('is_hidden', false)->latest()])
            ->withCount([
                'likes',
                'comments' => fn ($query) => $query->where('is_hidden', false),
            ])
            ->withExists([
                'likes as liked' => fn ($likes) => $likes->where('user_id', $user->id),
            ])
            ->latest()
            ->take(2)
            ->get();

        $counselors = $sessions->pluck('counselor')->filter()->unique('id')->values();

        $stats = [
            'cerita' => $user->posts()->count(),
            'komentar' => $user->comments()->count(),
            'dukungan' => $user->posts()->withCount('likes')->get()->sum('likes_count'),
        ];

        $platformStats = [
            ['value' => Post::query()->where('is_hidden', false)->count(), 'label' => 'cerita dibagikan'],
            ['value' => User::query()->where('role', User::ROLE_KONSELOR)->count(), 'label' => 'konselor terdaftar'],
            ['value' => CounselingSession::query()->count(), 'label' => 'sesi konseling'],
        ];

        return view('dasbor.pengguna', [
            'user' => $user,
            'sessions' => $sessions,
            'posts' => $posts,
            'latestPosts' => $latestPosts,
            'counselors' => $counselors,
            'stats' => $stats,
            'platformStats' => $platformStats,
            'interaksi' => array_sum($stats),
        ]);
    }
}
