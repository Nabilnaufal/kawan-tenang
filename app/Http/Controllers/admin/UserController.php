<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $role = $request->query('role', 'semua');
        $search = trim((string) $request->query('q'));

        $users = User::query()
            ->when(in_array($role, User::ROLES, true), fn ($query) => $query->where('role', $role))
            ->when($search !== '', fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%')
                ->orWhere('nim', 'like', '%'.$search.'%')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.pengguna', compact('users', 'role', 'search'));
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is($request->user()), 403, 'Tidak bisa menonaktifkan akun sendiri.');

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('status', $user->is_active ? 'Akun pengguna diaktifkan.' : 'Akun pengguna dinonaktifkan.');
    }
}
