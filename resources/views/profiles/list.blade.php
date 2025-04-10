@extends('layouts.app2')

@section('content')
<br><br><br><br>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            @include('layouts.sidebar')
        </div>
        <div class="col-md-9 profile-container">
            <h4>Hồ Sơ Của Tôi</h4>
            <p>Quản lý thông tin hồ sơ để bảo mật tài khoản</p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tên Đăng Nhập (Email)</label>
                    <input type="text" class="form-control" value="{{ $user->email }}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tên</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
                <button type="submit" class="btn btn-danger">Lưu</button>
            </form>
        </div>
    </div>
</div>
@endsection
