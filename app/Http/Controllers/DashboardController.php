<?php

namespace App\Http\Controllers;

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

        $counselors = $sessions->pluck('counselor')->filter()->unique('id')->values();

        $stats = [
            'cerita' => $user->posts()->count(),
            'komentar' => $user->comments()->count(),
            'dukungan' => $user->posts()->withCount('likes')->get()->sum('likes_count'),
        ];

        return view('dasbor.pengguna', [
            'user' => $user,
            'sessions' => $sessions,
            'posts' => $posts,
            'counselors' => $counselors,
            'stats' => $stats,
            'interaksi' => array_sum($stats),
        ]);
    }
}
