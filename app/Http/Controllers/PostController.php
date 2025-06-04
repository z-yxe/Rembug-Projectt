<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'caption' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('uploads', 'public');

        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image_path' => $imagePath,
        ]);

        return redirect('/profile/' . auth()->user()->id);
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        // Check if the authenticated user is the same as the post user
        if (auth()->id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        // Check if the authenticated user is the same as the post user
        if (auth()->id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'caption' => 'required',
        ]);

        $post->update($data);

        return redirect('/posts/' . $post->id);
    }

    public function destroy(Post $post)
    {
        // Check if the authenticated user is the same as the post user
        if (auth()->id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the image file
        Storage::disk('public')->delete($post->image_path);

        // Delete the post
        $post->delete();

        return redirect('/profile/' . auth()->user()->id);
    }

    // *** TAMBAHKAN METODE INI UNTUK PENCARIAN ***
    public function search(Request $request)
    {
        $query = $request->input('q');
        $postResults = null;
        $userResults = null;

        if ($query) {
            // Mencari postingan berdasarkan caption
            $postResults = Post::where('caption', 'LIKE', "%{$query}%")
                ->with('user') // Muat relasi user untuk postingan
                ->latest() // Urutkan dari yang terbaru
                ->paginate(10, ['*'], 'posts_page'); // 10 hasil per halaman, nama halaman 'posts_page'

            // Mencari pengguna berdasarkan username atau nama
            $userResults = User::where('username', 'LIKE', "%{$query}%")
                ->orWhere('name', 'LIKE', "%{$query}%")
                ->paginate(10, ['*'], 'users_page'); // 10 hasil per halaman, nama halaman 'users_page'
        }

        return view('search.index', compact('query', 'postResults', 'userResults'));
    }
}
