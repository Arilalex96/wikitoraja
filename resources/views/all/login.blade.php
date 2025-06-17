@php
    $pagename = 'login';
    $page_title = $pagename;
@endphp

@extends('layouts.default')
@section('content')
    <!-- Login Form -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Masuk ke WikiToraja</h4>
                    </div>
                    <div class="card-body">
                        <form id="loginForm" action="{{ route('login.backend') }}" method="post">
                            @csrf
                            @if (session('registration_success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('registration_success') }}
                                </div>
                            @endif

                            {{-- <div class="alert alert-info" role="alert">
                                    <strong>Akun Demo:</strong><br>
                                    Admin: admin@wikitoraja.com / password<br>
                                    Kontributor: contributor@wikitoraja.com / password<br>
                                    Validator: validator@wikitoraja.com / password
                                </div> --}}

                            <div class="mb-3">
                                <label for="role" class="form-label">Masuk sebagai</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option style="display: none;" value="">Pilih peran</option>
                                    <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                                    <option value="validator" @selected(old('role') == 'validator')>Validator</option>
                                    <option value="contributor" @selected(old('role') == 'contributor') @selected(session('registration_success'))>
                                        Kontributor</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}"
                                    required placeholder="Masukkan email Anda">

                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <input type="password" class="form-control" id="password" name="password" required
                                    placeholder="Masukkan kata sandi Anda">
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Masuk</button>
                                <a href="{{ route('contributor.register.view') }}"
                                    class="btn btn-outline-secondary">Daftar sebagai Kontributor</a>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Kembali ke Beranda</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
