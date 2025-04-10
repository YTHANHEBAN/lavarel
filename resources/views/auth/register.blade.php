@extends('layouts.app2')

@section('content')
<br><br><br><br><br>
<div class="d-flex justify-content-center align-items-center">
    <div class="card shadow-lg p-4" style="width: 400px;">
        <h3 class="text-center mb-3">Đăng Ký</h3>
        <form method="POST" action="/register">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tên</label>
                <input type="text" name="name" class="form-control" placeholder="Nhập tên của bạn">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Nhập email">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Nhập lại mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Xác nhận mật khẩu">
            </div>
            <button type="submit" class="btn btn-primary w-100">Đăng Ký</button>
        </form>
        <p class="mt-3 text-center">
            Bạn đã có tài khoản? <a href="/login" class="text-decoration-none">Đăng nhập</a>
        </p>
    </div>
</div>
@endsection
