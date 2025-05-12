@extends('layouts.app2')
@section('content')
<style>
    .filters ul {
        list-style: none;
        padding: 0;
        display: flex;
        gap: 15px;
    }

    .filters ul li a {
        text-decoration: none;
        color: black;
        /* mặc định màu đen */
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 5px;
        transition: 0.3s;
    }

    .filters ul li a:hover {
        color: rgb(249, 22, 1);
        /* khi hover đổi màu */
    }

    .filters ul li.active a {
        color: white !important;
        background-color: rgb(249, 22, 1);
        /* màu cam khi active */
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

    .product-item {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }

    .product-item:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        background-color: #fef7f5;
    }
</style>
<!-- ***** Preloader Start ***** -->
<div class="container">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-8">

        </div>
    </div>
</div>

<div class="page-heading products-heading header-text">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-content">
                    <h4>new arrivals</h4>
                    <h2>sixteen products</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="products">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="filters">
                    <ul>
                        @foreach ($categories as $category)
                        <li class="{{ request('category_id') == $category->id ? 'active' : '' }}">
                            <a href="{{ route('user_products', ['category_id' => $category->id]) }}">{{ $category->name }}</a>
                        </li>
                        @endforeach

                    </ul>
                </div>
                <form method="GET" action="{{ route('user_products') }}" class="mb-4">
                    <div class="row align-items-end">
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                        <div class="col-md-3">
                            <label>Giá từ</label>
                            <input type="number" name="price_min" class="form-control" placeholder="VNĐ" value="{{ request('price_min') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Giá đến</label>
                            <input type="number" name="price_max" class="form-control" placeholder="VNĐ" value="{{ request('price_max') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Lọc</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <div class="filters-content">
                    <div class="row grid">
                        @foreach ($products as $product)
                        <div class="col-lg-4 col-md-4 all des">
                            <div class="product-item">
                                <a href="/products/detail/{{ $product->id}}"><img src="{{ asset('images/' . $product->image) }}" alt=""></a>
                                <div class="down-content">
                                    <a href="#">
                                        <h4>{{ $product->name }}</h4>
                                    </a>
                                    <h6>{{ $product->price }} VNĐ</h6>
                                    <p>{{ $product->description }}.</p>
                                    <ul class="stars">
                                        @php
                                        // Giả sử $product->average_rating là số sao trung bình của sản phẩm
                                        $averageRating = $product->average_rating ?: 0; // Nếu không có đánh giá, đặt thành 0
                                        @endphp

                                        @for ($i = 1; $i <= 5; $i++)
                                            <li>
                                            <i class="fa fa-star {{ $i <= $averageRating ? 'text-danger' : 'text-muted' }}"></i>
                                            </li>
                                            @endfor
                                    </ul>
                                    <span>Đánh Giá ({{ $product->review_count }})</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination">
                        {{-- Nút "Trước" --}}
                        @if ($products->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">«</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->appends(request()->query())->previousPageUrl() }}" aria-label="Previous">
                                «
                            </a>
                        </li>
                        @endif

                        {{-- Các số trang --}}
                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url . '&' . http_build_query(request()->except('page')) }}">{{ $page }}</a>
                        </li>
                        @endforeach

                        {{-- Nút "Sau" --}}
                        @if ($products->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->appends(request()->query())->nextPageUrl() }}" aria-label="Next">
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
</div>
@endsection