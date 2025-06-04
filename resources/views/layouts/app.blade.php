<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Instacamp') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body,
        #app {
            overflow-x: hidden;
            /* Mencegah scroll horizontal */
        }

        .main-content-area {
            height: calc(100vh - 56px);
            /* Tinggi penuh dikurangi navbar */
        }

        .sticky-sidebar {
            position: sticky;
            top: 56px;
            /* Tinggi navbar */
            height: calc(100vh - 56px);
            /* Tinggi penuh dikurangi navbar */
            overflow-y: auto;
        }

        @media (max-width: 767.98px) {

            /* md breakpoint */
            .main-content-area>.row>main {
                height: auto;
                /* Biarkan konten menentukan tinggi di mobile */
            }
        }
    </style>
</head>

<body>
    <div id="app" class="d-flex flex-column vh-100">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" style="height: 56px;">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Rembug
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item d-md-none">
                            <button class="nav-link" type="button" data-bs-toggle="offcanvas" data-bs-target="#leftSidebarOffcanvas" aria-controls="leftSidebarOffcanvas">
                                <i class="fas fa-bars"></i> Menu
                            </button>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item d-md-none">
                            <a class="nav-link" href="{{ route('posts.create') }}" title="New Post">
                                <i class="fas fa-plus-square fs-5"></i>
                            </a>
                        </li>
                        <li class="nav-item d-md-none">
                            <button class="nav-link" type="button" data-bs-toggle="offcanvas" data-bs-target="#rightSidebarOffcanvas" aria-controls="rightSidebarOffcanvas" title="Suggestions">
                                <i class="fas fa-users fs-5"></i>
                            </button>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                @if (Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="{{ Auth::user()->username }}" class="rounded-circle me-1" style="width: 24px; height: 24px; object-fit: cover;">
                                @else
                                <i class="fas fa-user-circle fs-5 me-1"></i>
                                @endif
                                {{ Auth::user()->username ?? Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item d-md-none" href="{{ route('profile.show', Auth::user()->id) }}">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="leftSidebarOffcanvas" aria-labelledby="leftSidebarOffcanvasLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="leftSidebarOffcanvasLabel">REMBUG</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="nav flex-column">
                    @auth
                    <li class="nav-item mb-2"><a class="nav-link text-dark fs-5" href="{{ route('posts.index') }}"><i class="fas fa-home fa-fw me-3"></i>Home</a></li>
                    <li class="nav-item mb-2"><a class="nav-link text-dark fs-5" href="{{ route('search.index') }}"><i class="fas fa-search fa-fw me-3"></i>Search</a></li>
                    <li class="nav-item mb-2"><a class="nav-link text-dark fs-5" href="{{ route('posts.create') }}"><i class="far fa-plus-square fa-fw me-3"></i>New Post</a></li>
                    <li class="nav-item mb-2"><a class="nav-link text-dark fs-5" href="{{ route('profile.show', Auth::user()->id) }}"><i class="far fa-user-circle fa-fw me-3"></i>Profile</a></li>
                    @else
                    <li class="nav-item mb-2"><a class="nav-link text-dark fs-5" href="{{ route('login') }}"><i class="fas fa-sign-in-alt fa-fw me-3"></i>Login</a></li>
                    <li class="nav-item mb-2"><a class="nav-link text-dark fs-5" href="{{ route('register') }}"><i class="fas fa-user-plus fa-fw me-3"></i>Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>

        <div class="offcanvas offcanvas-end d-md-none" tabindex="-1" id="rightSidebarOffcanvas" aria-labelledby="rightSidebarOffcanvasLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="rightSidebarOffcanvasLabel">Suggestions</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                @auth
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('profile.show', Auth::user()->id) }}">
                        <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://via.placeholder.com/56' }}" class="rounded-circle me-3" style="width: 56px; height: 56px; object-fit: cover;">
                    </a>
                    <div>
                        <a href="{{ route('profile.show', Auth::user()->id) }}" class="text-dark text-decoration-none"><strong>{{ Auth::user()->username }}</strong></a><br>
                        <small class="text-muted">{{ Auth::user()->name }}</small>
                    </div>
                </div>
                <hr>
                @endauth
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="text-muted">Suggestions For You</h6>
                    <a href="#" class="text-dark small text-decoration-none fw-bold">See All</a>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <img src="https://via.placeholder.com/40" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                    <div><a href="#" class="text-dark text-decoration-none"><strong>some_user1</strong></a><br><small class="text-muted">Followed by X</small></div>
                    <a href="#" class="ms-auto btn btn-sm btn-link text-primary text-decoration-none fw-bold">Follow</a>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <img src="https://via.placeholder.com/40" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                    <div><a href="#" class="text-dark text-decoration-none"><strong>another_user</strong></a><br><small class="text-muted">New to REMBUG</small></div>
                    <a href="#" class="ms-auto btn btn-sm btn-link text-primary text-decoration-none fw-bold">Follow</a>
                </div>
            </div>
        </div>

        <div class="container-fluid flex-grow-1 main-content-area">
            <div class="row h-100">
                <aside class="col-md-3 col-lg-2 p-3 border-end bg-white d-none d-md-flex flex-column sticky-sidebar">
                    <ul class="nav flex-column mb-auto mt-3">
                        @auth
                        <li class="nav-item mb-2"><a class="nav-link text-dark fs-5 py-2" href="{{ route('posts.index') }}"><i class="fas fa-home fa-fw me-3"></i>Home</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-dark fs-5 py-2" href="{{ route('search.index') }}"><i class="fas fa-search fa-fw me-3"></i>Search</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-dark fs-5 py-2" href="{{ route('posts.create') }}"><i class="far fa-plus-square fa-fw me-3"></i>New Post</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-dark fs-5 py-2" href="{{ route('profile.show', Auth::user()->id) }}"><i class="far fa-user-circle fa-fw me-3"></i>Profile</a></li>
                        @else
                        <li class="nav-item mb-2"><a class="nav-link text-dark fs-5 py-2" href="{{ route('login') }}"><i class="fas fa-sign-in-alt fa-fw me-3"></i>Login</a></li>
                        <li class="nav-item mb-2"><a class="nav-link text-dark fs-5 py-2" href="{{ route('register') }}"><i class="fas fa-user-plus fa-fw me-3"></i>Register</a></li>
                        @endauth
                    </ul>
                    @auth
                    <ul class="nav flex-column mt-auto mb-3">
                        <li class="nav-item">
                            <a class="nav-link text-dark fs-5" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();">
                                <i class="fas fa-sign-out-alt fa-fw me-3"></i>Logout
                            </a>
                            <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </li>
                    </ul>
                    @endauth
                </aside>

                <main class="col-12 col-md-6 col-lg-7 py-4 overflow-auto" style="height: calc(100vh - 56px);">
                    <div class="container-fluid px-md-2 px-lg-3"> @yield('content')
                    </div>
                </main>

                <aside class="col-md-3 col-lg-3 py-4 px-lg-4 border-start bg-white d-none d-md-block sticky-sidebar">
                    @auth
                    {{-- Mini profil pengguna yang sedang login --}}
                    <div class="d-flex align-items-center my-3">
                        <a href="{{ route('profile.show', Auth::user()->id) }}">
                            <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://via.placeholder.com/56' }}" class="rounded-circle me-3" style="width: 56px; height: 56px; object-fit: cover;" alt="{{ Auth::user()->username }}">
                        </a>
                        <div>
                            <a href="{{ route('profile.show', Auth::user()->id) }}" class="text-dark text-decoration-none fw-bold">{{ Auth::user()->username }}</a><br>
                            <small class="text-muted">{{ Auth::user()->name }}</small>
                        </div>
                    </div>
                    @endauth

                    <div class="d-flex justify-content-between align-items-center my-3">
                        <h6 class="text-muted fw-bold">Suggestions For You</h6>
                        {{-- Tautan "See All" bisa diarahkan ke halaman rekomendasi khusus jika ada --}}
                        {{-- <a href="#" class="text-dark small text-decoration-none fw-bold">See All</a> --}}
                    </div>

                    {{-- Loop untuk menampilkan pengguna yang direkomendasikan --}}
                    @if(isset($recommendedUsers) && $recommendedUsers->count() > 0)
                    @foreach($recommendedUsers as $recUser)
                    {{-- Pastikan tidak menampilkan tombol follow untuk diri sendiri --}}
                    @if(Auth::id() !== $recUser->id)
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('profile.show', $recUser->id) }}">
                            <img src="{{ $recUser->profile_image ? asset('storage/' . $recUser->profile_image) : 'https://via.placeholder.com/32' }}" class="rounded-circle me-3" style="width: 32px; height: 32px; object-fit: cover;" alt="{{ $recUser->username }}">
                        </a>
                        <div class="flex-grow-1">
                            <a href="{{ route('profile.show', $recUser->id) }}" class="text-dark text-decoration-none fw-bold small">{{ Str::limit($recUser->username, 15) }}</a><br>
                            <small class="text-muted" style="font-size: 0.75em;">{{ Str::limit($recUser->name ?: 'Suggested for you', 20) }}</small>
                        </div>
                        <div class="ms-2"> {{-- Menggunakan ms-2 agar tidak terlalu rapat --}}
                            @if(Auth::user()->isFollowing($recUser))
                            <form action="{{ route('follow.destroy', $recUser->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-secondary fw-bold small py-0 px-1" style="font-size: 0.75rem;">Unfollow</button>
                            </form>
                            @else
                            <form action="{{ route('follow.store', $recUser->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-primary text-decoration-none fw-bold small py-0 px-1" style="font-size: 0.75rem;">Follow</button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @else
                    <p class="small text-muted">No new suggestions right now.</p>
                    @endif

                    <footer class="mt-4 small text-muted pt-4">
                        <ul class="list-inline" style="font-size: 0.75em;">
                            <li class="list-inline-item"><a href="#" class="text-muted text-decoration-none">About</a></li>
                            {{-- ... item footer lainnya ... --}}
                            <li class="list-inline-item"><a href="#" class="text-muted text-decoration-none">Terms</a></li>
                        </ul>
                        <p style="font-size: 0.75em;">&copy; {{ date('Y') }} REMBUG</p>
                    </footer>
                </aside>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>