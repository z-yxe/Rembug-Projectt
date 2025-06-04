@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Tampilkan pesan success/error/info dari session --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-3 text-center mb-3 mb-md-0">
            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://via.placeholder.com/150' }}"
                class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">
        </div>
        <div class="col-md-9">
            <div class="d-flex align-items-center mb-3">
                <h1 class="me-3 mb-0">{{ $user->username }}</h1>
                @auth
                @if(Auth::id() === $user->id)
                <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-outline-secondary btn-sm">Edit Profile</a>
                @else
                @if(Auth::user()->isFollowing($user))
                <form action="{{ route('follow.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Unfollow</button>
                </form>
                @else
                <form action="{{ route('follow.store', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">Follow</button>
                </form>
                @endif
                @endif
                @endauth
            </div>
            <div class="d-flex mb-3">
                <div class="me-4"><strong>{{ $user->posts->count() }}</strong> {{ Str::plural('post', $user->posts->count()) }}</div>
                <div class="me-4"><strong>{{ $user->followers->count() }}</strong> followers</div>
                <div><strong>{{ $user->following->count() }}</strong> following</div>
            </div>
            <div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                <p>{{ $user->bio ?: 'No bio yet.' }}</p>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <h4 class="mb-3 text-center">Posts</h4>
    <div class="row mt-1">
        @forelse($user->posts as $post)
        <div class="col-md-4 col-sm-6 mb-4">
            <a href="{{ route('posts.show', $post->id) }}">
                <img src="{{ asset('storage/' . $post->image_path) }}" class="img-fluid rounded shadow-sm" style="aspect-ratio: 1/1; object-fit: cover;">
            </a>
        </div>
        @empty
        <div class="col-12 text-center">
            <p class="text-muted">This user has no posts yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection