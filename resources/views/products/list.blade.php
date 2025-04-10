@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">QUẢN LÝ SẢN PHẨM</h2>
        <a href="/create" class="btn btn-success"><i class="fas fa-plus"></i> Thêm sản phẩm</a>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Form tìm kiếm -->
    <div class="mb-4">
        <form action="{{ route('products.list') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Nhập tên sản phẩm..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary w-50">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </form>
    </div>
</div>

<main>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Danh sách sản phẩm
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên</th>
                        <th>Mô tả</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Hình ảnh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($products) > 0)
                    @foreach ($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td class="text-truncate" style="max-width: 150px;">{{ $product->description }}</td>
                        <td class="text-success font-weight-bold">{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                        <td class="text-info font-weight-bold">{{ $product->quantity }}</td>
                        <td>
                        <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="rounded" width="50" height="50">

                        </td>
                        <td>
                            <a href="/products/{{ $product->id }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            <a href="/products/edit/{{ $product->id }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="/products/delete/{{ $product->id }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không có sản phẩm nào.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
