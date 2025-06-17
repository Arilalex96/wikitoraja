@php
    $pagename = 'register contributor';
    $page_title = $pagename;
@endphp

@extends('layouts.default')
@section('content')
        <!-- Register Form -->
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white text-center">
                            <h4 class="mb-0">Daftar sebagai Kontributor</h4>
                        </div>
                        <div class="card-body">
                            <form id="registerForm" action="{{ route('contributor.register.backend') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="name" class="form-control" id="name" name="name" value="{{ old('name') }}"
                                        required placeholder="Masukkan nama Anda">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Alamat Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
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
                                @if (session('system_error'))
                                    <div class="alert alert-danger">
                                        {{ session('system_error') }}
                                    </div>
                                @endif
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Daftar</button>
                                    <span>Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></span> 
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">Kembali ke Beranda</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection