@extends('layouts.app2')

@section('content')
<br><br><br><br><br>
<div class="container">
    <h2>Quên Mật Khẩu</h2>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="/forgot-password">
        @csrf
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Gửi Liên Kết Đặt Lại Mật Khẩu</button>
    </form>
</div>
@endsection
