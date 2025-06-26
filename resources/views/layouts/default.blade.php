<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ ucwords($page_title) }} - {{ config('app.name') }}</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        @vite('resources/css/app.css')
        <!-- Font Awesome -->
        @yield('page-css')
    <body>
        <!-- Responsive navbar-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand wikitoraja-name" href="{{ route('home') }}">{{ config('app.name') }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link @if($pagename=='home') active @endif" href="{{ route('home') }}">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link @if($pagename=='about') active @endif" href="{{ route('about') }}">Tentang</a></li>
                        <li class="nav-item"><a class="nav-link @if($pagename=='contact') active @endif" href="{{ route('contact') }}">Kontak</a></li>
                        @if(auth()->check())
                            @if(auth()->user()?->hasRole('contributor') || auth()->user()->hasRole('validator'))
                                <li class="nav-item"><a class="nav-link @if($pagename=='manage article') active @endif" href="{{ route('article.index.view') }}">Kelola Artikel</a></li>
                            @endif
                            @if(auth()->user()?->hasRole('admin'))
                                <li class="nav-item"><a class="nav-link @if($pagename=='manage contributor') active @endif" href="{{ route('contributor.index.view') }}">Kelola Kontributor</a></li>
                                <li class="nav-item"><a class="nav-link @if($pagename=='manage validator') active @endif" href="{{ route('validator.index.view') }}">Kelola Validator</a></li>
                            @endif

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Profil
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile') }}">Lihat Profil</a></li>
                                    <li><a class="dropdown-item" href="" id="logoutLink">Keluar</a></li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout.backend') }}" method="POST">
                                    @csrf
                                </form>
                            </li>
                        @endif
                        @if(!auth()->check())
                            <li class="nav-item"><a class="nav-link @if($pagename=='login') active @endif" href="{{ route('login') }}">Masuk</a></li>
                            <li class="nav-item"><a class="nav-link @if($pagename=='register') active @endif" href="{{ route('contributor.register.view') }}">Daftar</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')

        <!-- Footer-->
        <footer class="py-5 bg-dark text-center text-white">
            <div class="container">
                <p class="m-0">Hak Cipta &copy; {{ config('app.name') }} 2025</p>
            </div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            $('#logoutLink').click(function(e){
                e.preventDefault();
                $('#logout-form').submit();
            });
        </script>
        @yield('page-js')
    </body>
</html>
