@extends('layouts.app2')

@section('content')
<br><br><br><br><br>
<div class="container d-flex justify-content-center align-items-center">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%; border-radius: 12px;">
        <h3 class="text-center mb-4">Đăng Nhập</h3>
        <form method="POST" action="/login">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Nhập email của bạn" >
                @error('email')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" >
                @error('password')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input">
                    <label class="form-check-label">Ghi nhớ đăng nhập</label>
                </div>
                <a href="/forgot-password" class="text-decoration-none">Quên mật khẩu?</a>
            </div>
            <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
        </form>
        <hr class="my-4">
        <a href="{{ route('login.google') }}" class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
            <i class="fab fa-google me-2"></i> Đăng nhập với Google
        </a>
        <p class="text-center mt-3">Bạn chưa có tài khoản? <a href="/register" class="text-decoration-none">Đăng ký ngay</a></p>
    </div>
</div>
@endsection
