@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary">Chi Tiết Sản Phẩm</h2>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $product->name }}</h4>
            <p class="card-text"><strong>Mô tả:</strong> {{ $product->description }}</p>
            <p class="card-text"><strong>Giá:</strong> <span class="text-success">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span></p>
            <p class="card-text"><strong>Số lượng:</strong> {{ $product->quantity }}</p>
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-width: 300px;">
            <br><br>
            <a href="/products" class="btn btn-secondary">Quay lại</a>
            <a href="/products/edit/{{ $product->id }}" class="btn btn-warning">Chỉnh sửa</a>
        </div>
    </div>
</div>
@endsection
