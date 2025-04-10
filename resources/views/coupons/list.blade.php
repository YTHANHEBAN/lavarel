@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h3>Danh sách mã giảm giá</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('coupons.create') }}" class="btn btn-success mb-3">+ Thêm mã giảm giá</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mã</th>
                <th>Loại</th>
                <th>Giá trị</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->id }}</td>
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->type == 'percent' ? 'Phần trăm' : 'Cố định' }}</td>
                    <td>
                        {{ $coupon->type == 'percent' ? $coupon->value . '%' : number_format($coupon->value) . '₫' }}
                    </td>
                    <td>{{ $coupon->start_date ?? '-' }}</td>
                    <td>{{ $coupon->end_date ?? '-' }}</td>
                    <td>
                        <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-primary btn-sm">Sửa</a>

                        <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-danger btn-sm">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Không có mã giảm giá nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $coupons->links() }}
</div>
@endsection
