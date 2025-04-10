@extends('layouts.app2')
@section('content')
<br><br><br><br><br>
<body class="bg-light">
    <div class="container">
        <div class="row bg-white shadow-sm p-4">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-md-5 text-center">
                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                <div class="d-flex justify-content-center gap-2 mt-2">
                    <img src="{{ asset('images/' . $product->image) }}" alt="Hình nhỏ" class="img-thumbnail" width="75">
                    <img src="{{ asset('images/' . $product->image) }}" alt="Hình nhỏ" class="img-thumbnail" width="75">
                    <img src="{{ asset('images/' . $product->image) }}" alt="Hình nhỏ" class="img-thumbnail" width="75">
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-7">
                <h1 class="h4 fw-bold">{{ $product->name }}</h1>
                <p class="text-danger fs-5 fw-semibold">
                    Giá: <span class="text-muted text-decoration-line-through">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                </p>
                <p class="text-success fw-bold">✅ Kho hàng: CÒN HÀNG</p>

                <form action="/carts/add" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="price" value="{{ $product->price }}">
                    <input type="hidden" name="total_price" value="{{ $product->price }}"> {{-- default quantity 1 --}}
                    <div class="mb-3">
                        <label class="fw-semibold">Số lượng:</label>
                        <input type="number" name="quantity" class="form-control w-25" value="1" min="1" required>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary">THÊM VÀO GIỎ</button>
                    </div>
                </form>

                <div class="mt-4 p-3 bg-light rounded">
                    <h5 class="fw-semibold">🎁 TẶNG TẤT MIỄN PHÍ</h5>
                    <img src="/mnt/data/image.png" alt="Tất tặng kèm" class="img-fluid" width="120">
                </div>
            </div>
        </div>
    </div>
</body>
@endsection
