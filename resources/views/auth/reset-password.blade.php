@extends('layouts.app2')

@section('content')
<br><br><br><br><br>
<div class="container">
    <h2>Đặt Lại Mật Khẩu</h2>
    <!-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif -->
    <form method="POST" action="/reset-password">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" >
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label>Mật khẩu mới</label>
            <input type="password" name="password" class="form-control" >
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label>Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Đặt Lại Mật Khẩu</button>
    </form>
</div>
@endsection
