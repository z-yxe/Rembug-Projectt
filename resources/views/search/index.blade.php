@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <h3 class="mb-3">Pencarian</h3>
            <form action="{{ route('search.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Cari postingan, pengguna..." value="{{ $query ?? old('q') }}" aria-label="Search query">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>

    @if(isset($query) && $query)
    {{-- Bagian Hasil Pengguna --}}
    @if(isset($userResults) && $userResults->count() > 0)
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <h4>Pengguna Ditemukan:</h4>
            <div class="list-group">
                @foreach($userResults as $user)
                <a href="{{ route('profile.show', $user->id) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://via.placeholder.com/50' }}" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" alt="{{ $user->username }}">
                    <div>
                        <strong class="mb-0">{{ $user->username }}</strong>
                        @if($user->name)<p class="mb-0 text-muted small">{{ $user->name }}</p>@endif
                    </div>
                </a>
                @endforeach
            </div>
            @if ($userResults->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{-- Menambahkan parameter query 'q' ke link paginasi --}}
                {{ $userResults->appends(['q' => $query])->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
    @elseif(isset($userResults)) {{-- Query dibuat, tapi tidak ada hasil pengguna --}}
    {{-- Bisa ditambahkan pesan jika tidak ada pengguna yang ditemukan, atau biarkan kosong jika bagian post lebih utama --}}
    {{-- <div class="row justify-content-center mb-4"><div class="col-md-8"><p class="text-muted">Tidak ada pengguna yang cocok dengan "{{ $query }}".</p>
</div>
</div> --}}
@endif

{{-- Bagian Hasil Postingan --}}
@if(isset($postResults) && $postResults->count() > 0)
<div class="row justify-content-center">
    <div class="col-md-8">
        <h4>Postingan Ditemukan:</h4>
        <hr class="my-3">
        @foreach($postResults as $post)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white py-2 d-flex align-items-center">
                @if($post->user)
                <a href="{{ route('profile.show', $post->user->id) }}" class="text-dark text-decoration-none d-flex align-items-center">
                    <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : 'https://via.placeholder.com/32' }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;" alt="{{ $post->user->username }}">
                    <strong>{{ $post->user->username }}</strong>
                </a>
                @else
                <img src="https://via.placeholder.com/32" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                <span class="text-muted">Pengguna Dihapus</span>
                @endif
            </div>
            @if($post->image_path)
            <a href="{{ route('posts.show', $post->id) }}">
                <img src="{{ asset('storage/' . $post->image_path) }}" class="card-img-top" style="max-height: 400px; object-fit: cover; border-radius:0;" alt="Post image">
            </a>
            @endif
            <div class="card-body">
                <p class="card-text">{{ Str::limit($post->caption, 150) }}</p>
                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-outline-primary">Lihat Postingan</a>
            </div>
            <div class="card-footer bg-white text-muted small">
                {{ $post->created_at->diffForHumans() }}
            </div>
        </div>
        @endforeach

        @if ($postResults->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{-- Menambahkan parameter query 'q' ke link paginasi --}}
            {{ $postResults->appends(['q' => $query])->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@elseif(isset($postResults)) {{-- Query dibuat, tapi tidak ada hasil postingan --}}
{{-- <div class="row justify-content-center"><div class="col-md-8"><p class="text-muted">Tidak ada postingan yang cocok dengan "{{ $query }}".</p>
</div>
</div> --}}
@endif

{{-- Pesan jika tidak ada hasil sama sekali setelah pencarian --}}
@if(isset($query) && $query && (!isset($userResults) || $userResults->isEmpty()) && (!isset($postResults) || $postResults->isEmpty()))
<div class="row justify-content-center">
    <div class="col-md-8 text-center py-5">
        <i class="fas fa-search fa-4x text-muted mb-3"></i>
        <h4>Tidak Ada Hasil</h4>
        <p class="text-muted">Kami tidak dapat menemukan apapun untuk "{{ $query }}". Coba kata kunci pencarian yang berbeda.</p>
    </div>
</div>
@endif

@elseif(isset($query) && !$query && request()->has('q')) {{-- Pencarian kosong dikirim --}}
<div class="row justify-content-center">
    <div class="col-md-8">
        <p class="text-muted">Silakan masukkan kata kunci untuk mencari postingan atau pengguna.</p>
    </div>
</div>
@endif
</div>
@endsection