@extends('layouts.app2')
@section('content')
<style>
    .product-item {
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .product-item:hover {
        transform: scale(1.05);
    }

    /* .page-link {
        color: rgb(249, 22, 1);
        text-decoration: none;
    } */

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
</style>
<!-- Banner Starts Here -->
<div class="banner header-text">
    <div class="owl-banner owl-carousel">
        <div class="banner-item-01">
            <div class="text-content">
                <h4>Ưu Đãi</h4>
                <h2>Hàng Mới Được Ưu Đãi</h2>
            </div>
        </div>
        <div class="banner-item-02">
            <div class="text-content">
                <h4>Ưu Đãi Chớp Nhoáng</h4>
                <h2>Nhận Sản Phẩm Tốt Nhất Của Bạn</h2>
            </div>
        </div>
        <div class="banner-item-03">
            <div class="text-content">
                <h4>Ưu Đãi Cuối</h4>
                <h2>Nhận Ưu Đãi Vào Phút Chói</h2>
            </div>
        </div>
    </div>
</div>
<!-- Banner Ends Here -->
<div class="container my-4">
    <div class="latest-products">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-4">
                        <form action="/" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Nhập tên sản phẩm..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary  w-50">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </form>
                    </div>
                    <div class="section-heading">
                        <h2>DANH SÁCH SẢN PHẨM</h2>
                        <form method="GET" action="{{ route('user_products.list') }}">
                            <div class="input-group" style="max-width: 300px;">
                                <label class="input-group-text bg-danger text-white" for="rating_range">
                                    <i class="fa fa-star"></i>
                                </label>
                                <select name="rating_range" id="rating_range" class="form-select">
                                    <option value="">Chọn mức sao</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ $currentRatingRange == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                        {!! str_repeat('<span style="color:gold;">★</span>', $i) !!}
                                        </option>
                                        @endfor
                                </select>
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fa fa-filter me-1"></i> Lọc
                                </button>
                            </div>
                        </form>
                        <a href="/user_products">Xem Tất Cả Sản Phẩm <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
                @if($products->count() > 0)
                @foreach ($products as $product)
                <div class="col-md-4">
                    <div class="product-item">
                        <a href="/products/detail/{{ $product->id}}"><img src="{{ asset('images/' . $product->image) }}" alt=""></a>
                        <div class="down-content">
                            <a href="#">
                                <h4>{{ $product->name }}</h4>
                            </a>
                            <h6>{{ number_format($product->price, 0, ',', '.') }} VNĐ</h6>
                            <p>{{ $product->description }}</p>
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
                @else
                <div class="col-12 text-center text-muted">
                    <p>Không có sản phẩm nào.</p>
                </div>
                @endif
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
                        <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                            «
                        </a>
                    </li>
                    @endif

                    {{-- Các số trang --}}
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endforeach

                    {{-- Nút "Sau" --}}
                    @if ($products->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">
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


    <div class="latest-products">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>TOP 6 BÁN CHẠY</h2>
                    </div>
                </div>
                @if($products_top->count() > 0)
                @foreach ($products_top as $product)
                <div class="col-md-4">
                    <div class="product-item">
                        <a href="/products/detail/{{ $product->id}}"><img src="{{ asset('images/' . $product->image) }}" alt=""></a>
                        <div class="down-content">
                            <a href="#">
                                <h4>{{ $product->name }}</h4>
                            </a>
                            <h6>{{ number_format($product->price, 0, ',', '.') }} VNĐ</h6>
                            <p>{{ $product->description }}</p>
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
                @else
                <div class="col-12 text-center text-muted">
                    <p>Không có sản phẩm nào.</p>
                </div>
                @endif
            </div>
        </div>

    </div>

    <div class="best-features">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>Giới Thiệu Về SHOP YTHANH</h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="left-content">
                        <h4>Tìm kiếm những sản phẩm tốt nhất?</h4>
                        <p><a rel="nofollow" href="https://templatemo.com/tm-546-sixteen-clothing" target="_parent">Mẫu này</a> được sử dụng miễn phí cho các trang web doanh nghiệp của bạn. Tuy nhiên, bạn không có quyền phân phối lại tệp ZIP có thể tải xuống trên bất kỳ trang web bộ sưu tập mẫu nào. <a rel="nofollow" href="https://templatemo.com/contact">Liên hệ với chúng tôi</a> Để biết thêm thông tin.</p>
                        <ul class="featured-list">
                            <li><a href="#">Văn bản mẫu không có ý nghĩa cụ thể</a></li>
                            <li><a href="#">Chuyên về lĩnh vực quảng cáo cao cấp</a></li>
                            <li><a href="#">Một cơ thể không có điều gì gây khó chịu</a></li>
                            <li><a href="#">Cơ thể, tất cả những điều đau đớn</a></li>
                            <li><a href="#">Không với điều gì có thể được thừa nhận</a></li>

                        </ul>
                        <a href="about.html" class="filled-button">Xem Nhiều Hơn</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="right-image">
                        <img src="assets/images/feature-image.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="call-to-action">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="inner-content">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>Creative &amp; Unique <em>Sixteen</em> Products</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque corporis amet elite author nulla.</p>
                        </div>
                        <div class="col-md-4">
                            <a href="#" class="filled-button">Purchase Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection