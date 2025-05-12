@extends('layouts.app2')

@section('content')
<br><br><br><br>

<style>
    * {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .order-table th {
        background-color: #f8f9fa;
        color: #343a40;
        font-weight: 600;
    }

    .order-table td,
    .order-table th {
        vertical-align: middle;
    }

    .order-status {
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
    }

    .status-processing {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-delivered {
        background-color: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }

    .section-title {
        border-left: 4px solid #007bff;
        padding-left: 10px;
        margin-bottom: 1.5rem;
    }

    .btn-detail {
        background-color: #0d6efd;
        color: white;
    }

    .btn-detail:hover {
        background-color: #0b5ed7;
    }
</style>

<div class="container mt-5 mb-5">
    <div class="section-title">
        <h4 class="fw-bold">🧾 Lịch sử đơn hàng</h4>
    </div>

    @if($orders->count())
    <form method="GET" action="{{ route('orders.index') }}" class="mb-4">
        <div class="row justify-content-end">
            <div class="col-md-4 col-sm-6">
                <div class="input-group shadow-sm">
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="Chờ Xác Nhận" {{ request('status') == 'Chờ Xác Nhận' ? 'selected' : '' }}>Chờ Xác Nhận</option>
                        <option value="Đã Xác Nhận" {{ request('status') == 'Đã Xác Nhận' ? 'selected' : '' }}>Đã Xác Nhận</option>
                        <option value="Đang Giao" {{ request('status') == 'Đang Giao' ? 'selected' : '' }}>Đang Giao</option>
                        <option value="Đã Hoàn Thành" {{ request('status') == 'Đã Hoàn Thành' ? 'selected' : '' }}>Đã Hoàn Thành</option>
                        <option value="Đã Hủy" {{ request('status') == 'Đã Hủy' ? 'selected' : '' }}>Đã Hủy</option>
                    </select>
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-funnel"></i> Lọc
                    </button>
                </div>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-hover order-table shadow-sm rounded">
            <thead class="table-light">
                <tr class="text-center">
                    <th>Tên Người Nhận</th>
                    <th>Ngày Đặt</th>
                    <th>Trạng Thái</th>
                    <th>Tổng Tiền</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="text-center">
                    <td>{{ Auth::user()->name }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>
                        @php
                        $statusClass = match($order->status) {
                        'Đã giao' => 'status-delivered',
                        'Đang xử lý' => 'status-processing',
                        'Đã hủy' => 'status-cancelled',
                        default => 'bg-light text-dark'
                        };
                        @endphp
                        <span class="order-status {{ $statusClass }}">{{ $order->status }}</span>
                    </td>
                    <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-detail">
                            <i class="bi bi-eye"></i> Chi tiết
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-warning">
        Bạn chưa có đơn hàng nào.
    </div>
    @endif
</div>
@endsection
