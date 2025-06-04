<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function searchUsers(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return view('search.results', ['users' => collect()]); // Return empty collection if no query
        }

        // Search for users where username or name matches the query
        // Using 'ilike' for case-insensitive search if your database supports it (e.g., PostgreSQL)
        // For MySQL, 'like' is case-insensitive by default with CI collations, or use LOWER()
        // MongoDB's default string comparison is case-sensitive. We'll use a regex for case-insensitivity.

        $users = User::where('username', 'regexp', "/.*{$query}.*/i")
                     ->orWhere('name', 'regexp', "/.*{$query}.*/i")
                     ->get();

        return view('search.results', compact('users', 'query'));
    }
}