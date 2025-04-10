@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h3>Thêm mã giảm giá mới</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('coupons.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="code" class="form-label">Mã giảm giá</label>
            <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Loại giảm giá</label>
            <select name="type" class="form-control" required>
                <option value="percent">Phần trăm (%)</option>
                <option value="fixed">Giá cố định (VNĐ)</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="value" class="form-label">Giá trị giảm</label>
            <input type="number" name="value" class="form-control" value="{{ old('value') }}" required>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Ngày bắt đầu</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Ngày kết thúc</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
        </div>

        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('coupons.list') }}" class="btn btn-secondary">Trở lại</a>
    </form>
</div>
@endsection
