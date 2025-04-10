@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="my-4 text-center">TẤT CẢ DANH MỤC</h3>
    @if(Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
</div>

<div class="card ">
    <div class="card-header">
        <i class="fas fa-table me-1"></i> Danh Sách Danh Mục
    </div>
    <div class="card-body">
        <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="fas fa-plus"></i> Thêm Danh Mục
        </a><br>
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Tên Danh Mục</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>
                        <!-- Nút Edit -->
                        <button class="btn btn-warning btn-sm btn-edit"
                            data-id="{{ $category->id }}"
                            data-name="{{ $category->name }}"
                            data-parent-id="{{ $category->parent_id ?? '' }}"
                            data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                            Edit
                        </button>
                        <!-- Nút Delete -->
                        <form action="/categories/delete/{{ $category->id }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Form Create Category -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryLabel">Create New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="parentCategory" class="form-label">Parent Category</label>
                        <select class="form-select" id="parentCategory" name="parent_id">
                            <option value="">None</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Edit Category -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" id="editCategoryName" name="name" class="form-control" required>
                        <div id="error-name" class="text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="parentCategory" class="form-label">Parent Category</label>
                        <select class="form-select" id="parentCategoryedit" name="parent_id">
                            <option value="">None</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-submit">Update</button>
                    <button type="button" class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript xử lý cập nhật dữ liệu vào Modal Edit -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.body.addEventListener("click", function(event) {
            if (event.target.classList.contains("btn-edit")) {
                // Lấy dữ liệu từ nút Edit
                let categoryId = event.target.getAttribute("data-id");
                let categoryName = event.target.getAttribute("data-name");
                let categoryParentId = event.target.getAttribute("data-parent-id") || "";

                // Gán giá trị vào input Name
                document.getElementById("editCategoryName").value = categoryName;

                // Gán giá trị vào select Parent Category
                let parentCategorySelect = document.getElementById("parentCategoryedit");
                for (let option of parentCategorySelect.options) {
                    if (option.value === categoryParentId) {
                        option.selected = true;
                    } else {
                        option.selected = false;
                    }
                }

                // Cập nhật action của form
                document.getElementById("editCategoryForm").setAttribute("action", "/categories/update/" + categoryId);
            }
        });
    });
</script>

<style>
    .container {
        margin-top: 50px;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-submit {
        width: 100%;
        margin-top: 20px;
    }

    .custom-table {
        margin-top: 20px;
    }

    .table thead {
        background-color: #007bff;
        color: white;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
</style>
@endsection
