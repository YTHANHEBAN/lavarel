@extends('layouts.app2')

@section('content')
<div class="container mt-4">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <div class="mb-4">
        <form action="/" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Nhập tên sản phẩm..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary w-50">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </form>
    </div>

    <div class="row">
    @if($products->count() > 0)
        @foreach ($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $product->name }}</h5>
                    <p class="card-text text-truncate" style="max-width: 100%;">{{ $product->description }}</p>
                    <p class="text-success font-weight-bold">{{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                    <p class="text-info">Số lượng: {{ $product->quantity }}</p>
                    <a href="/products/{{ $product->id }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Xem chi tiết
                    </a>
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

<!-- Hiển thị nút chuyển trang -->
<!-- Hiển thị nút chuyển trang với Bootstrap -->
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
@endsection
