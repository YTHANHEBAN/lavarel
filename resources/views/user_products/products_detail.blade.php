@extends('layouts.app2')
@section('content')
<br><br>
<style>
    .hover-effect {
        transition: all 0.3s ease-in-out;
    }

    .hover-effect:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        z-index: 2;
    }
</style>
<div class="container py-5">
    @if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Thông tin sản phẩm -->
    <div class="row bg-white shadow-sm p-4 rounded-4 mb-5">
        <!-- Hình ảnh sản phẩm -->
        <div class="col-md-5 border-end text-center">
            <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded mb-3">
            <div class="d-flex justify-content-center gap-2">
                @for ($i = 0; $i < 3; $i++)
                    <img src="{{ asset('images/' . $product->image) }}" alt="Hình nhỏ" class="img-thumbnail rounded" width="70">
                    @endfor
            </div>
        </div>

        <!-- Chi tiết sản phẩm -->
        <div class="col-md-7">
            <h2 class="fw-bold">{{ $product->name }}</h2>
            <p class="fs-5 text-danger mb-1">
                Giá: <span class="text-decoration-line-through text-muted">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
            </p>
            <p class="mb-1">⭐ Đánh giá trung bình: {{ number_format($product->averageRating(), 1) }}/5</p>
            <p class="text-success fw-semibold">✅ Kho hàng: CÒN HÀNG</p>

            <form action="/carts/add" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="price" value="{{ $product->price }}">
                <input type="hidden" name="total_price" value="{{ $product->price }}">
                <input type="hidden" name="input_price" value="{{ $product->input_price }}">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Số lượng:</label>
                    <div class="input-group w-25">
                        <button type="button" class="btn btn-outline-secondary btn-decrease">−</button>
                        <input type="number" name="quantity" class="form-control text-center quantity-input" value="1" min="1" required>
                        <button type="button" class="btn btn-outline-secondary btn-increase">+</button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg px-4">
                    <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                </button>
            </form>
        </div>
    </div>

    <!-- Đánh giá -->
    <div class="bg-white p-4 rounded-4 shadow-sm mb-5">
        <h4 class="fw-bold mb-4">Đánh giá sản phẩm</h4>
        @forelse($product->reviews as $review)
        <div class="border-bottom pb-3 mb-3">
            <div class="d-flex justify-content-between">
                <strong>{{ $review->user->name }}</strong>
                <span class="text-warning">{{ str_repeat('⭐', $review->rating) }}</span>
            </div>
            <p class="mb-0">{{ $review->comment }}</p>
        </div>
        @empty
        <p class="text-muted">Chưa có đánh giá nào.</p>
        @endforelse
    </div>

    <!-- Sản phẩm liên quan -->
    <div class="bg-white p-4 rounded-4 shadow-sm">
        <h4 class="fw-bold mb-4">Sản phẩm liên quan</h4>
        <div class="row">
            @php $hasRelated = false; @endphp

            @foreach ($products_categories as $related)
            @if ($related->category_id === $product->category_id && $related->id !== $product->id)
            @php $hasRelated = true; @endphp
            <div class="col-md-3 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 hover-effect">
                    <a href="/products/detail/{{ $related->id }}">
                        <img src="{{ asset('images/' . $related->image) }}" class="card-img-top rounded-top" alt="{{ $related->name }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $related->name }}</h5>
                        <p class="text-danger fw-semibold">{{ number_format($related->price, 0, ',', '.') }} VNĐ</p>
                        <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($related->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-warning">{{ str_repeat('⭐', 5) }}</div>
                            <small class="text-muted">Reviews (24)</small>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach

            @if (!$hasRelated)
            <div class="col-12 text-center text-muted">
                <p>Không có sản phẩm liên quan.</p>
            </div>
            @endif
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const decreaseBtns = document.querySelectorAll('.btn-decrease');
        const increaseBtns = document.querySelectorAll('.btn-increase');

        decreaseBtns.forEach(button => {
            button.addEventListener('click', function () {
                const input = this.nextElementSibling;
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                }
            });
        });

        increaseBtns.forEach(button => {
            button.addEventListener('click', function () {
                const input = this.previousElementSibling;
                let value = parseInt(input.value);
                input.value = value + 1;
            });
        });
    });
</script>

@endsection
