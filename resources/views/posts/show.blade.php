@extends('layouts.app')

@section('content')
<div class="container">
    <div class="post-show-card" style="background-color: var(--card-background); border-radius: var(--border-radius); box-shadow: var(--shadow-medium); overflow: hidden;">
        <div class="row g-0">
            <div class="col-lg-7 col-md-6 text-center" style="background-color: #fff; display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post image by {{ $post->user->username ?? 'user' }}" 
                     style="max-width: 100%; max-height: 85vh; height: auto; display: block; object-fit: contain;">
            </div>

            <div class="col-lg-5 col-md-6 d-flex flex-column" style="border-left: 1px solid var(--border-color);">
                <div class="p-3 flex-grow-1 d-flex flex-column">

                    <!-- User info and post options -->
                    <div class="d-flex align-items-center mb-3 pb-3" style="border-bottom: 1px solid var(--border-color);">
                        @if($post->user)
                            <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none d-flex align-items-center">
                                <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->username) . '&background=E8F0FE&color=007BFF&size=40&font-size=0.5&rounded=true' }}" 
                                     alt="{{ $post->user->username }}" class="me-3" 
                                     style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-color);">
                                <span class="username" style="font-weight: 600; color: var(--text-primary); font-size: 0.95rem;">{{ $post->user->username }}</span>
                            </a>
                            
                            @if(auth()->check() && auth()->id() === $post->user_id)
                            <div class="dropdown ms-auto">
                                <button class="btn btn-link p-0" type="button" id="postOptions" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--text-secondary);">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="postOptions">
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

                    <!-- Post caption and actions -->
                    <div class="caption-area mb-3 pb-3" style="font-size: 0.9rem; line-height: 1.6; border-bottom: 1px solid var(--border-color);">
                        @if($post->user)
                            <a href="{{ route('profile.show', $post->user->id) }}" class="username text-decoration-none me-1" style="font-weight: 600; color: var(--text-primary);">{{ $post->user->username }}</a>
                        @endif
                        <span style="color: var(--text-secondary); white-space: pre-wrap;">{{ $post->caption }}</span>
                    </div>
                    
                    <!-- Actions and info area -->
                    <div class="actions-info-area mb-2">
                        <div class="d-flex align-items-center mb-2">
                            @if($post->likes->where('user_id', auth()->id())->count() > 0)
                                <form action="{{ route('likes.destroy', $post->id) }}" method="POST" class="me-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0" style="font-size: 1.4rem; color: var(--bs-danger);">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('likes.store', $post->id) }}" method="POST" class="me-3">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0" style="font-size: 1.4rem; color: var(--text-secondary);">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                        @if ($post->likes->count() > 0)
                        <p class="mb-1" style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</p>
                        @endif
                        <p style="font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">{{ $post->created_at->format('F j, Y') }}</p>
                    </div>

                    <!-- Comments section -->
                    <div class="comments-section flex-grow-1 mb-3" style="max-height: 250px; overflow-y: auto; padding-right: 10px;">
                        @forelse($post->comments as $comment)
                        <div class="d-flex mb-2 align-items-start">
                            <div class="flex-grow-1">
                                <a href="{{ $comment->user ? route('profile.show', $comment->user->id) : '#' }}" class="username text-decoration-none me-1" style="font-weight: 600; color: var(--text-primary); font-size: 0.85rem;">{{ $comment->user ? $comment->user->username : 'Deleted User' }}</a>
                                <span style="color: var(--text-secondary); font-size: 0.85rem; white-space: pre-wrap;">{{ $comment->comment }}</span>
                            </div>
                            @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->id() === $post->user_id))
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="ms-2" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0 text-danger" style="font-size: 0.8rem;" title="Delete comment">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                        @empty
                        <p style="color: var(--text-secondary); font-size: 0.85rem; text-align: center; padding: 1rem 0;">No comments yet.</p>
                        @endforelse
                    </div>

                    <!-- Comment input form -->
                    <div class="mt-auto pt-3" style="border-top: 1px solid var(--border-color);">
                        <form action="{{ route('comments.store', $post->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="comment" class="form-control" placeholder="Add a comment..." required style="border-radius: 16px 0 0 16px; border: 1px solid var(--border-color); background-color: var(--background-color); color: var(--text-primary); font-size: 0.85rem; padding: 0.5rem 0.75rem; box-shadow: none;">
                                <button class="btn" type="submit" style="border-radius: 0 16px 16px 0; background-color: var(--primary-color); color: white; font-weight: 500; font-size: 0.85rem; border: 1px solid var(--primary-color); padding: 0.5rem 0.75rem;">
                                        Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb, 0, 123, 255), 0.25);
        background-color: var(--card-background); 
    }
</style>
@endsection
