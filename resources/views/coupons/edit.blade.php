@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h3>Chỉnh sửa mã giảm giá</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="code" class="form-label">Mã giảm giá</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Loại giảm giá</label>
            <select name="type" class="form-control" required>
                <option value="percent" {{ $coupon->type == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>Giá cố định (VNĐ)</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="value" class="form-label">Giá trị giảm</label>
            <input type="number" name="value" class="form-control" value="{{ old('value', $coupon->value) }}" required>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Ngày bắt đầu</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($coupon->start_date)->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Ngày kết thúc</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($coupon->end_date)->format('Y-m-d')) }}">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('coupons.list') }}" class="btn btn-secondary">Trở lại</a>
    </form>
</div>
@endsection
