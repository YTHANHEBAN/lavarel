@extends('layouts.app2')

@section('content')
<br><br><br><br>
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    .shop-name {
        color: #ee4d2d;
    }

    .product-thumb {
        width: 80px;
        height: auto;
    }

    .price-original {
        text-decoration: line-through;
        color: gray;
        font-size: 14px;
    }

    .price-sale {
        color: #ee4d2d;
        font-weight: bold;
        font-size: 16px;
    }

    .quantity-control input {
        width: 50px;
        text-align: center;
    }

    .btn-outline-danger {
        font-size: 14px;
    }

    .flex-grow-1 {
        margin-left: 20px;
        margin-bottom: 20px;
    }

    .ms-3 {
        margin-left: 200px;
    }

    .danger-1 {
        margin-left: 200px;
    }
</style>

<body class="bg-light">
    <div class="container mt-5">
        @if($cartItems->count() > 0)
        @if(session('message'))
        <div class="alert alert-success text-center">{{ session('message') }}</div>
        @endif
        <!-- Shop -->
        <div class="border bg-white p-3 mb-3">
            <div class="d-flex align-items-center mb-3">
                <h2 class="shop-name">🛒 Giỏ Hàng Của Bạn</h2>
            </div>

            <!-- Product Item -->
            @foreach($cartItems as $item)
            <div class="d-flex align-items-start border-top pt-3">
                <img src="{{ asset('images/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail" width="100">
                <div class="flex-grow-1">
                    <p class="mb-1">{{ $item->product->name }}</p>
                    <div class="d-flex gap-3 align-items-center mb-2">
                        <span class="price-sale">{{ number_format($item->price) }} VNĐ</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 quantity-control">
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex justify-content-center gap-2">
                            @csrf
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm" style="width: 70px;">
                            <button type="submit" class="btn btn-sm btn-outline-primary">Cập nhật</button>
                        </form>
                        <span class="ms-3">Tổng Tiền : {{ number_format($item->price * $item->quantity) }} VNĐ</span>
                        <form action="/carts/delete/{{ $item->id }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger danger-1">
                                <i class="bi bi-trash3-fill"></i> Xóa
                            </button>
                        </form>

                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Tổng cộng -->
        <div class="border bg-white p-3 d-flex justify-content-between align-items-center">
            <div class="form-check">
                <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        🗑️ Xóa toàn bộ
                    </button>
                </form>
            </div>
            <div class="text-end">
                <div>Tổng cộng :<span class="text-danger fw-bold fs-5"> {{ number_format($total) }} VNĐ</span></div>
            </div>
            <a href="{{ route('cart.checkout') }}" class="btn btn-danger px-4 py-2">Mua Hàng</a>
        </div>
        @else
        <div class="alert alert-info text-center">Giỏ hàng của bạn đang trống.</div>
        @endif
    </div>
</body>
@endsection
