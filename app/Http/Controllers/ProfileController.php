<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(User $user)
    {
        return view('profiles.index', compact('user'));
    }

    // fungsi edit profile
    public function edit(User $user)
    {
        if (auth()->id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('profiles.edit', compact('user'));
    }

    // fungsi update profile
    public function update(Request $request, User $user)
    {
        if (auth()->id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name' => 'required',
            'username' => 'required',
            'bio' => 'nullable',
            'profile_image' => 'image|nullable|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $imagePath = $request->file('profile_image')->store('profile', 'public');
            $data['profile_image'] = $imagePath;
        }

        $user->update($data);

        return redirect("/profile/{$user->id}");
    }
}
