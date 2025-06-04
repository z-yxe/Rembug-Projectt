@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    @forelse($posts as $post)
    <div class="col-lg-10 col-xl-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex align-items-center py-2">
                @if($post->user)
                <a href="{{ route('profile.show', $post->user->id) }}" class="text-dark text-decoration-none d-flex align-items-center">
                    <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : 'https://via.placeholder.com/32' }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                    <strong class="fs-6">{{ $post->user->username }}</strong>
                </a>
                @if(auth()->id() === $post->user_id)
                <div class="dropdown ms-auto">
                    <button class="btn btn-link text-dark p-0" type="button" id="postOptions{{$post->id}}" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="postOptions{{$post->id}}">
                        <li><a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}">Edit</a></li>
                        <li>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endif
                @else
                <img src="https://via.placeholder.com/32" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                <span class="text-muted">Deleted User</span>
                @endif
            </div>
            <a href="{{ route('posts.show', $post->id) }}">
                <img src="{{ asset('storage/' . $post->image_path) }}" class="card-img-top" style="max-height: 70vh; object-fit: cover; border-radius: 0;">
            </a>
            <div class="card-body py-2">
                <div class="d-flex mb-2 align-items-center">
                    @if($post->likes->where('user_id', auth()->id())->count() > 0)
                    <form action="{{ route('likes.destroy', $post->id) }}" method="POST" class="me-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link p-0 text-danger fs-4 animate-like">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
                    @else
                    <form action="{{ route('likes.store', $post->id) }}" method="POST" class="me-3">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 text-dark fs-4 animate-like">
                            <i class="far fa-heart"></i>
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('posts.show', $post->id) }}#comments" class="btn btn-link p-0 text-dark fs-4 me-3">
                        <i class="far fa-comment"></i>
                    </a>
                </div>
                @if ($post->likes->count() > 0)
                <p class="mb-1 fw-bold small">{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</p>
                @endif

                @if($post->user)
                <p class="card-text mb-1 small"><a href="{{ route('profile.show', $post->user->id) }}" class="text-dark text-decoration-none fw-bold">{{ $post->user->username }}</a> {{ Str::limit($post->caption, 100) }} @if(strlen($post->caption) > 100) <a href="{{ route('posts.show', $post->id) }}" class="text-muted">more</a> @endif</p>
                @else
                <p class="card-text mb-1 small">{{ Str::limit($post->caption, 100) }} @if(strlen($post->caption) > 100) <a href="{{ route('posts.show', $post->id) }}" class="text-muted">more</a> @endif</p>
                @endif

                @if ($post->comments->count() > 2)
                <a href="{{ route('posts.show', $post->id) }}#comments" class="text-muted d-block mb-1 small">
                    View all {{ $post->comments->count() }} comments
                </a>
                @elseif($post->comments->count() > 0)
                @foreach($post->comments->take(2) as $comment)
                <p class="card-text mb-0 small"><a href="{{ $comment->user ? route('profile.show', $comment->user->id) : '#' }}" class="text-dark text-decoration-none fw-bold">{{ $comment->user ? $comment->user->username : 'Deleted User' }}</a> {{ Str::limit($comment->comment, 50) }}</p>
                @endforeach
                @if($post->comments->count() > 2)
                <a href="{{ route('posts.show', $post->id) }}#comments" class="text-muted d-block mb-1 small">View all comments</a>
                @endif
                @endif

                <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mt-2">
                    @csrf
                    <div class="input-group input-group-sm">
                        <input type="text" name="comment" class="form-control border-0 bg-light" placeholder="Add a comment..." required>
                        <button class="btn btn-link text-primary text-decoration-none fw-bold" type="submit">Post</button>
                    </div>
                </form>
                <p class="text-muted small mt-2 mb-0" style="font-size: 0.7em;">{{ $post->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="fas fa-camera fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">No Posts Yet</h4>
        <p class="text-muted">Follow people to see their posts or create your own!</p>
        <div class="mt-4">
            <a href="{{ route('posts.create') }}" class="btn btn-primary"><i class="fas fa-plus-square me-2"></i>Create New Post</a>
        </div>
    </div>
    @endforelse
</div>

@if($posts->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $posts->links() }}
</div>
@endif
@endsection