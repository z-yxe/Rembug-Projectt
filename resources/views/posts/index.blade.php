@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    @forelse($posts as $post)
    <div class="col-lg-10 col-xl-8 mb-4">
        <div class="post-card" style="background: var(--card-background); border: 1px solid var(--border-color); border-radius: var(--border-radius); box-shadow: var(--shadow-medium); overflow: hidden;">
            
            <!-- Card Header: User Info -->
            <div class="d-flex align-items-center p-3" style="border-bottom: 1px solid var(--border-color);">
                @if($post->user)
                    <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none d-flex align-items-center">
                        <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->username) . '&background=E8F0FE&color=007BFF&size=40&font-size=0.5&rounded=true' }}" alt="{{ $post->user->username }}" class="me-3" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-color);">
                        <span class="username" style="font-weight: 600; color: var(--text-primary); font-size: 0.95rem;">{{ $post->user->username }}</span>
                    </a>
                    @if(auth()->id() === $post->user_id)
                    <div class="dropdown ms-auto">
                        <button class="btn btn-link p-0" type="button" id="postOptions{{$post->id}}" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--text-secondary);">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="postOptions{{$post->id}}">
                            <li><a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}"><i class="fas fa-edit me-2" style="width:16px;"></i>Edit</a></li>
                            <li>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash-alt me-2" style="width:16px;"></i>Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endif
                @else
                    <img src="https://ui-avatars.com/api/?name=D U&background=E8F0FE&color=007BFF&size=40&font-size=0.5&rounded=true" alt="Deleted User" class="me-3" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-color);">
                    <span style="color: var(--text-secondary); font-style: italic;">Deleted User</span>
                @endif
            </div>
            <a href="{{ route('posts.show', $post->id) }}">
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post image by {{ $post->user->username ?? 'user' }}" style="width: 100%; max-height: 60vh; object-fit: cover; display: block;">
            </a>

            <!-- Card Body: Likes, Comments, and Caption -->
            <div class="p-3">
                <div class="d-flex mb-2 align-items-center">
                    @if($post->likes->where('user_id', auth()->id())->count() > 0)
                    <form action="{{ route('likes.destroy', $post->id) }}" method="POST" class="me-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link p-0 animate-like" style="font-size: 1.4rem; color: var(--bs-danger);">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
                    @else
                    <form action="{{ route('likes.store', $post->id) }}" method="POST" class="me-3">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 animate-like" style="font-size: 1.4rem; color: var(--text-secondary);">
                            <i class="far fa-heart"></i>
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('posts.show', $post->id) }}#comments" class="btn btn-link p-0 me-3" style="font-size: 1.4rem; color: var(--text-secondary);">
                        <i class="far fa-comment"></i>
                    </a>
                </div>

                @if ($post->likes->count() > 0)
                <p class="mb-1" style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</p>
                @endif

                @if($post->caption)
                <p class="mb-1" style="font-size: 0.9rem; line-height: 1.5;">
                    @if($post->user)
                    <a href="{{ route('profile.show', $post->user->id) }}" class="username" style="font-weight: 600; color: var(--text-primary); text-decoration: none;">{{ $post->user->username }}</a>
                    @endif
                    <span style="color: var(--text-secondary); white-space: pre-wrap;">{{ Str::limit($post->caption, 150) }}</span>
                    @if(strlen($post->caption) > 150)
                    <a href="{{ route('posts.show', $post->id) }}" style="color: var(--text-secondary); text-decoration: none; font-weight: 500;">more</a>
                    @endif
                </p>
                @endif

                @if ($post->comments->count() > 0)
                    @if ($post->comments->count() > 2)
                    <a href="{{ route('posts.show', $post->id) }}#comments" class="d-block mb-1" style="font-size: 0.85rem; color: var(--text-secondary); text-decoration: none;">
                        View all {{ $post->comments->count() }} comments
                    </a>
                    @endif
                    @foreach($post->comments->take(2) as $comment)
                    <p class="mb-0" style="font-size: 0.85rem; line-height: 1.4;">
                        <a href="{{ $comment->user ? route('profile.show', $comment->user->id) : '#' }}" class="username" style="font-weight: 600; color: var(--text-primary); text-decoration: none;">{{ $comment->user ? $comment->user->username : 'Deleted User' }}</a>
                        <span style="color: var(--text-secondary);">{{ Str::limit($comment->comment, 70) }}</span>
                    </p>
                    @endforeach
                @endif

                <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="comment" class="form-control" placeholder="Add a comment..." required style="border-radius: 16px 0 0 16px; border: 1px solid var(--border-color); background-color: var(--background-color); color: var(--text-primary); font-size: 0.85rem; padding: 0.5rem 0.75rem; box-shadow: none;">
                        <button class="btn" type="submit" style="border-radius: 0 16px 16px 0; background-color: var(--primary-color); color: white; font-weight: 500; font-size: 0.85rem; border: 1px solid var(--primary-color); padding: 0.5rem 0.75rem;">Post</button>
                    </div>
                </form>
                <p class="mt-2 mb-0" style="font-size: 0.8rem; color: var(--text-secondary);">{{ $post->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    <!-- Jika tidak ada post -->
    @empty
    <div class="col-lg-10 col-xl-8 mb-4">
        <div class="text-center py-5 my-4" style="background: var(--card-background); border-radius: var(--border-radius); box-shadow: var(--shadow-light);">
            <i class="fas fa-stream fa-3x mb-3" style="color: var(--text-secondary);"></i>
            <h4 style="color: var(--text-primary); font-weight: 600;">No Posts Yet</h4>
            <p style="color: var(--text-secondary);">Follow people or create your own masterpiece!</p>
            <div class="mt-4">
                <a href="{{ route('posts.create') }}" class="btn btn-primary-themed px-4 py-2" style="background-color: var(--primary-color); color: white; font-weight: 600; font-size: 0.95rem; border-radius: 8px; border: none; transition: all 0.2s ease; box-shadow: var(--shadow-light);">
                    <i class="fas fa-plus-square me-2"></i>Create New Post
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($posts->hasPages())
<div class="d-flex justify-content-center mt-4 mb-3">
    {{ $posts->links() }}
</div>
@endif

<style>
    .btn-primary-themed:hover {
        background-color: #0056b3 !important;
        transform: translateY(-1px);
        box-shadow: var(--shadow-medium) !important;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb, 0, 123, 255), 0.25);
        background-color: var(--card-background);
    }

    .post-card .animate-like:hover i {
        transform: scale(1.15);
        transition: transform 0.1s ease-in-out;
    }
    .post-card .animate-like:active i {
        transform: scale(0.9);
    }

    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        border-radius: 8px;
        box-shadow: var(--shadow-light);
    }
    .pagination .page-link {
        color: var(--primary-color);
        border-radius: 8px;
        margin: 0 0.25rem;
        border: 1px solid var(--border-color);
        padding: 0.5rem 0.85rem;
        transition: all 0.2s ease;
    }
    .pagination .page-link:hover {
        background-color: var(--hover-color);
        border-color: var(--primary-color);
        color: var(--text-primary);
    }
    .pagination .page-item.disabled .page-link {
        color: var(--text-secondary);
        background-color: transparent;
        border-color: var(--border-color);
    }
</style>
@endsection
