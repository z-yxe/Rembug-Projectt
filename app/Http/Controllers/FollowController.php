<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Follow seorang pengguna.
     */
    public function store(User $user)
    {
        $currentUser = Auth::user();

        // Pengguna tidak bisa mengikuti dirinya sendiri
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        // Lakukan follow jika belum mengikuti
        if (!$currentUser->isFollowing($user)) {
            $currentUser->following()->attach($user->id);
            return back()->with('success', 'Successfully followed ' . $user->username);
        }

        return back()->with('info', 'You are already following ' . $user->username);
    }

    /**
     * Unfollow seorang pengguna.
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        // Lakukan unfollow jika sudah mengikuti
        if ($currentUser->isFollowing($user)) {
            $currentUser->following()->detach($user->id);
            return back()->with('success', 'Successfully unfollowed ' . $user->username);
        }

        return back()->with('info', 'You are not following ' . $user->username);
    }
}