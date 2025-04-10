@extends('layouts.app2')

@section('content')
<br><br><br><br>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
        @include('layouts.sidebar')
        </div>
        <div class="col-md-9 profile-container">
            <div style="max-width: 500px;">
                <h4 class="mb-4">🔐 Đổi mật khẩu</h4>

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="form-control" required>
                        @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="form-control" required>
                        @error('new_password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-danger w-100">Cập nhật mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
