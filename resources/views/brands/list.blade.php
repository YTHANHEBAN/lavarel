@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">QUẢN LÝ THƯƠNG HIỆU</h2>
        <!-- Nút mở Modal Thêm Mới -->
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createBrandModal">
            <i class="fas fa-plus"></i> Thêm Mới
        </button>
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
        <form action="" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Nhập tên thương hiệu..." value="{{ request('search') }}">
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
            Danh sách Thương Hiệu
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($brands) > 0)
                    @foreach ($brands as $index => $brand)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $brand->name }}</td>
                        <td>
                            <a href="/brands/edit/{{ $brand->id }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form action="/brands/delete/{{ $brand->id }}" method="POST" class="d-inline">
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
                        <td colspan="7" class="text-center text-muted">Không có thương hiệu nào.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal Thêm Mới -->
<div class="modal fade" id="createBrandModal" tabindex="-1" aria-labelledby="createBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createBrandModalLabel">Thêm Thương Hiệu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('brands.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên thương hiệu</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-body">
                    <label>Danh Mục</label>
                    <select class="form-select" id="sel1" name="category_id">
                        <option value="">Chọn Danh Mục</option>
                        @foreach ( $categories as $category )
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Thêm Mới</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Chỉnh Sửa -->
<!-- Script để cập nhật dữ liệu vào modal edit -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editBrandBtns = document.querySelectorAll(".editBrandBtn");
        editBrandBtns.forEach(button => {
            button.addEventListener("click", function() {
                const brandId = this.getAttribute("data-id");
                const brandName = this.getAttribute("data-name");

                document.getElementById("editBrandId").value = brandId;
                document.getElementById("editBrandName").value = brandName;

                document.getElementById("editBrandForm").action = "/brands/update/" + brandId;
            });
        });
    });
</script>

@endsection
