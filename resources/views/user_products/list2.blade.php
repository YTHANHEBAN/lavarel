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
                <h4>Best Offer</h4>
                <h2>New Arrivals On Sale</h2>
            </div>
        </div>
        <div class="banner-item-02">
            <div class="text-content">
                <h4>Flash Deals</h4>
                <h2>Get your best products</h2>
            </div>
        </div>
        <div class="banner-item-03">
            <div class="text-content">
                <h4>Last Minute</h4>
                <h2>Grab last minute deals</h2>
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
                        <a href="products.html">Xem Tất Cả Sản Phẩm <i class="fa fa-angle-right"></i></a>
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
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                            <span>Reviews (24)</span>
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

    <div class="best-features">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>About Sixteen Clothing</h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="left-content">
                        <h4>Looking for the best products?</h4>
                        <p><a rel="nofollow" href="https://templatemo.com/tm-546-sixteen-clothing" target="_parent">This template</a> is free to use for your business websites. However, you have no permission to redistribute the downloadable ZIP file on any template collection website. <a rel="nofollow" href="https://templatemo.com/contact">Contact us</a> for more info.</p>
                        <ul class="featured-list">
                            <li><a href="#">Lorem ipsum dolor sit amet</a></li>
                            <li><a href="#">Consectetur an adipisicing elit</a></li>
                            <li><a href="#">It aquecorporis nulla aspernatur</a></li>
                            <li><a href="#">Corporis, omnis doloremque</a></li>
                            <li><a href="#">Non cum id reprehenderit</a></li>
                        </ul>
                        <a href="about.html" class="filled-button">Read More</a>
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
