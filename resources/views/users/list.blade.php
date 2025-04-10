@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">QUẢN LÝ NGƯỜI DÙNG</h2>
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
        <form action="/users" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Nhập tên người dùng..." value="{{ request('search') }}">
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
            Danh sách người dùng
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Quyền</th>
                        <th>Ngày Tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if($users->count() > 0)
                    @foreach ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td class="text-truncate" style="max-width: 200px;">{{ $user->email }}</td>
                        <td class="text-info font-weight-bold">
                            {{ ucfirst($user->role) }}
                            <button class="btn btn-sm btn-outline-secondary ms-2 btn-edit-role"
                                data-id="{{ $user->id }}" data-role="{{ $user->role }}" data-bs-toggle="modal" data-bs-target="#editRoleModal">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if ($user->role !== 'admin')
                            <form action="/users/delete/{{ $user->id }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6" class="text-center text-muted">Không có người dùng nào.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal Edit Role -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleLabel">Cập nhật quyền</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Chọn quyền:</label>
                        <select name="role" id="editRoleSelect" class="form-select">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                            <option value="editor">Editor</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Cập Nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript xử lý Modal -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".btn-edit-role").forEach(button => {
            button.addEventListener("click", function() {
                let userId = this.getAttribute("data-id");
                let userRole = this.getAttribute("data-role");

                document.getElementById("editRoleSelect").value = userRole;
                document.getElementById("editRoleForm").setAttribute("action", "/users/update/" + userId);
            });
        });
    });
</script>

@endsection