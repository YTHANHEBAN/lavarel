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

    .order-table td, .order-table th {
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
    <div class="table-responsive">
        <table class="table table-bordered table-hover order-table shadow-sm rounded">
            <thead class="table-light">
                <tr class="text-center">
                    <th>Tên Người Nhận</th>
                    <th>Địa Chỉ Nhận Hàng</th>
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
                    <td class="location" data-province="{{ $order->province }}" data-district="{{ $order->district }}" data-ward="{{ $order->ward }}">
                        {{ $order->province }} / {{ $order->district }} / {{ $order->ward }}
                    </td>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const locationTds = document.querySelectorAll('.location');

        locationTds.forEach(td => {
            const provinceId = td.dataset.province;
            const districtId = td.dataset.district;
            const wardCode = td.dataset.ward;

            let provinceName = '';
            let districtName = '';
            let wardName = '';

            fetch('/address/provinces')
                .then(res => res.json())
                .then(data => {
                    const province = data.data.find(p => p.ProvinceID == provinceId);
                    provinceName = province ? province.ProvinceName : 'Không rõ tỉnh';
                    return fetch(`/address/districts/${provinceId}`);
                })
                .then(res => res.json())
                .then(data => {
                    const district = data.data.find(d => d.DistrictID == districtId);
                    districtName = district ? district.DistrictName : 'Không rõ huyện';
                    return fetch(`/address/wards/${districtId}`);
                })
                .then(res => res.json())
                .then(data => {
                    const ward = data.data.find(w => w.WardCode == wardCode);
                    wardName = ward ? ward.WardName : 'Không rõ xã';
                    td.textContent = `${provinceName} / ${districtName} / ${wardName}`;
                })
                .catch(error => {
                    console.error('Lỗi khi load địa chỉ:', error);
                    td.textContent = 'Không thể hiển thị địa chỉ';
                });
        });
    });
</script>
@endsection
