@extends('layouts.app')

@section('content')
<div class="create-post-form-container">
    <div class="card-style-theme" style="background: var(--card-background); border: 1px solid var(--border-color); border-radius: var(--border-radius); padding: 2rem; box-shadow: var(--shadow-medium);">
        <h2 class="mb-4 pb-3" style="font-weight: 700; color: var(--text-primary); font-size: 1.5rem; /* Reduced from 1.75rem */ display: flex; align-items: center; letter-spacing: -0.02em; border-bottom: 1px solid var(--border-color);">
            <i class="far fa-plus-square me-3" style="color: var(--primary-color); font-size: 1.25em;"></i>
            Create New Post
        </h2>

        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Caption input -->
            <div class="mb-4 pt-2">
                <label for="caption" class="form-label" style="font-weight: 500; color: var(--text-primary); font-size: 0.9rem; margin-bottom: 0.5rem;">Caption</label>
                <textarea id="caption" class="form-control @error('caption') is-invalid @enderror" name="caption" rows="4" style="border-radius: 8px; border-color: var(--border-color); background-color: var(--card-background);color: var(--text-primary); padding: 0.75rem 1rem;box-shadow: var(--shadow-light);"placeholder="Write a caption for your post...">{{ old('caption') }}</textarea>
                @error('caption')
                    <span class="invalid-feedback" role="alert" style="font-size: 0.85rem;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Image upload section -->
            <div class="mb-4">
                <label class="form-label" style="font-weight: 500; color: var(--text-primary); font-size: 0.9rem; margin-bottom: 0.5rem;">Image</label>
                <input type="file" class="form-control visually-hidden @error('image') is-invalid @enderror" id="image" name="image"accept="image/jpeg,image/png,image/gif"onchange="document.getElementById('fileNameDisplay').textContent = this.files.length > 0 ? this.files[0].name : 'No file chosen';">       
                <label for="image" id="image-upload-visual-cue" class="mt-1 p-4 text-center d-block" style="border: 2px dashed var(--border-color); border-radius: var(--border-radius); background-color: var(--hover-color); transition: background-color 0.2s ease; cursor: pointer;">
                    <i class="fas fa-cloud-upload-alt fa-3x mb-2" style="color: var(--text-secondary);"></i>
                    <p style="color: var(--text-primary); font-size: 1rem; margin-bottom: 0.25rem; font-weight: 500;">
                        Click to select an image
                    </p>
                    <span id="fileNameDisplay" class="text-muted" style="font-size: 0.8rem; display: inline-block; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: middle;">No file chosen</span>
                    <p class="text-muted mt-1" style="font-size: 0.75rem; margin-bottom:0;">Recommended: JPG, PNG, GIF</p>
                </label>
                @error('image')
                    <span class="invalid-feedback d-block" role="alert" style="font-size: 0.85rem;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Submit button -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5 pt-3" style="border-top: 1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary-themed px-4 py-2" style="background-color: var(--primary-color); color: white;  font-weight: 600;  font-size: 0.95rem; border-radius: 8px;  border: none; transition: all 0.2s ease; box-shadow: var(--shadow-light);">
                    <i class="fas fa-paper-plane me-2"></i>Share Post
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .btn-primary-themed:hover {
        background-color: #0056b3 !important;/
        transform: translateY(-1px);
        box-shadow: var(--shadow-medium) !important;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb, 0, 123, 255), 0.25);
        background-color: var(--card-background); 
    }
    
    #image-upload-visual-cue:hover {
        background-color: #e9ecef !important;
        border-color: var(--primary-color);
    }
</style>
@endsection
