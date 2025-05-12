@extends('layouts.app2')

@section('content')
<style>
    .hover-effect {
        transition: all 0.3s ease-in-out;
    }

    .hover-effect:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .product-img {
        max-height: 400px;
        object-fit: contain;
    }

    .img-thumbnail {
        cursor: pointer;
        transition: 0.3s;
    }

    .img-thumbnail:hover {
        transform: scale(1.1);
        border-color: #007bff;
    }

    .rating-stars {
        color: #ffc107;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #007bff;
    }

    .pagination .page-link {
        color: rgb(249, 22, 1) !important;
        /* Màu cam */
        /* border-color: #ff8800 !important; */
    }

    .pagination .page-item.active .page-link {
        background-color: rgb(249, 22, 1) !important;
        border-color: rgb(249, 22, 1) !important;
        color: white !important;
    }

    .btn-primary {
        background-color: rgb(249, 22, 1) !important;
        border-color: rgb(249, 22, 1) !important;
    }

    body {
        background-color: #f5f5f5;
    }
</style>
<br><br><br>

<body>
    <div class="container py-5">

        {{-- Flash messages --}}
        @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Product detail --}}
        <div class="row bg-white p-4 rounded-4 mb-5">
            <div class="col-md-5 border-end text-center">
                <img id="mainImage" src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid product-img mb-3 rounded">

                <div class="d-flex justify-content-center gap-2">
                    @foreach($images as $img)
                    <img src="{{ asset('images/' . $img->imagePath) }}"
                        alt="Ảnh phụ"
                        class="img-thumbnail sub-image"
                        style="width: 100px; height: auto; cursor: pointer;"
                        data-img="{{ asset('images/' . $img->imagePath) }}">
                    @endforeach
                </div>
            </div>

            <div class="col-md-7">
                <h2 class="fw-bold">{{ $product->name }}</h2>
                <p class="mb-2">⭐ Đánh giá trung bình: {{ number_format($product->averageRating(), 1) }}/5</p>
                <h4 class="fs-5 text-danger">
                    Giá: <span class="text-decoration-line-through text-muted">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                </h4>
                <br>
                <h6 class="text-success fw-semibold">KHO HÀNG : Còn {{ $product->quantity }} sản phẩm trong kho </h6>
                <form action="/carts/add" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="price" value="{{ $product->price }}">
                    <input type="hidden" name="total_price" value="{{ $product->price }}">
                    <input type="hidden" name="input_price" value="{{ $product->input_price }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Số lượng:</label>
                        <div class="input-group w-50">
                            <button type="button" class="btn btn-outline-secondary btn-decrease">−</button>
                            <input type="number" name="quantity" class="form-control text-center quantity-input" value="1" min="1" required>
                            <button type="button" class="btn btn-outline-secondary btn-increase">+</button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger btn-lg mt-2">
                        <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ
                    </button>
                </form>
            </div>
        </div>

        <ul class="nav nav-tabs mb-4" id="review-comment-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="reviews-tab" data-bs-toggle="tab" type="button">Đánh Giá</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="comments-tab" data-bs-toggle="tab" type="button">Bình Luận</button>
            </li>
        </ul>

        {{-- Reviews --}}
        <div id="review-section" class="bg-white p-4 rounded-4  mb-5">
            <h4 class="fw-bold mb-4">Các Bài Đánh Giá</h4>
            @forelse($product->reviews as $review)
            <div class="border-bottom pb-3 mb-3">
                <div class="d-flex justify-content-between">
                    <strong>{{ $review->user->name }}</strong>
                    <span class="rating-stars">{{ str_repeat('⭐', $review->rating) }}</span>
                </div>
                <p class="mb-0">{{ $review->comment }}</p>
            </div>
            @empty
            <p class="text-muted">Chưa có đánh giá nào.</p>
            @endforelse
        </div>

        {{-- Comment form and list --}}

        <div class="bg-white p-4 rounded-4 mb-5 sunken-box">
            <h5 class="fw-bold mb-4">Tất cả bình luận</h5>
            @php
            $comments = $product->comments()->latest()->get();
            @endphp

            @if($comments->isEmpty())
            <p class="text-muted">Chưa có bình luận nào cho sản phẩm này.</p>
            @else
            @foreach($comments as $comment)
            <div class="border-top pt-3 mt-3">
                <div class="d-flex justify-content-between">
                    <strong>{{ $comment->user->name }}</strong>
                    <span class="rating-stars">
                        {{ str_repeat('★', $comment->rating) }}{{ str_repeat('☆', 5 - $comment->rating) }}
                    </span>
                </div>
                <p>{{ $comment->content }}</p>
                <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
            </div>
            @endforeach
            @endif
        </div>

        @guest
        <div class="alert alert-info mt-4">
            Vui lòng đăng nhập để bình luận.
        </div>
        @endguest

        <div id="comment-section" class="d-none">
            @auth
            <div class="bg-white p-4 rounded-2 mb-3">
                <h5 class="fw-bold mb-3">Bình Luận</h5>
                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="mb-3">
                        <label class="form-label">Chọn số sao:</label>
                        <select name="rating" class="form-select" required>
                            <option value="5">⭐⭐⭐⭐⭐</option>
                            <option value="4">⭐⭐⭐⭐</option>
                            <option value="3">⭐⭐⭐</option>
                            <option value="2">⭐⭐</option>
                            <option value="1">⭐</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bình luận:</label>
                        <textarea name="content" rows="4" class="form-control" placeholder="Nhập nội dung bình luận..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Gửi bình luận</button>
                </form>
            </div>
            @endauth
        </div>

        {{-- Related products --}}
        <div class="bg-white p-4  rounded-4">
            <h4 class="fw-bold mb-4">Sản phẩm liên quan</h4>
            <div class="row">
                @php $hasRelated = false; @endphp
                @foreach ($products_categories as $related)
                @if ($related->category_id === $product->category_id && $related->id !== $product->id)
                @php $hasRelated = true; @endphp
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm hover-effect border-0">
                        <a href="/products/detail/{{ $related->id }}">
                            <img src="{{ asset('images/' . $related->image) }}" class="card-img-top rounded-top" alt="{{ $related->name }}">
                        </a>
                        <div class="card-body">
                            <h6 class="card-title fw-semibold">{{ $related->name }}</h6>
                            <p class="text-danger fw-semibold">{{ number_format($related->price, 0, ',', '.') }} VNĐ</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-warning">⭐ 5.0</div>
                                <small class="text-muted">Đánh Giá (24)</small>
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
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination">
                        {{-- Nút "Trước" --}}
                        @if ($products_categories->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">«</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $products_categories->previousPageUrl() }}" aria-label="Previous">
                                «
                            </a>
                        </li>
                        @endif

                        {{-- Các số trang --}}
                        @foreach ($products_categories->getUrlRange(1, $products_categories->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $products_categories->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                        @endforeach

                        {{-- Nút "Sau" --}}
                        @if ($products_categories->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $products_categories->nextPageUrl() }}" aria-label="Next">
                                »
                            </a>
                        </li>
                        @else
                        <li class="page-item disabled">
                            <span class="page-link">»</span>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const reviewsTab = document.getElementById('reviews-tab');
        const commentsTab = document.getElementById('comments-tab');
        const reviewSection = document.getElementById('review-section');
        const commentSection = document.getElementById('comment-section');

        reviewsTab.addEventListener('click', () => {
            reviewsTab.classList.add('active');
            commentsTab.classList.remove('active');
            reviewSection.classList.remove('d-none');
            commentSection.classList.add('d-none');
        });

        commentsTab.addEventListener('click', () => {
            commentsTab.classList.add('active');
            reviewsTab.classList.remove('active');
            commentSection.classList.remove('d-none');
            reviewSection.classList.add('d-none');
        });
    });
    document.querySelectorAll('.sub-image').forEach(img => {
        img.addEventListener('click', function() {
            const mainImage = document.getElementById('mainImage');
            mainImage.src = this.dataset.img;
        });
    });
</script>

@endsection
