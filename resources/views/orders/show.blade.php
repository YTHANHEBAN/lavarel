@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">Chi Tiết Đơn Hàng</h4>

            <div class="mb-4">
                <h5 class="fw-semibold mb-3">Địa Chỉ Nhận Hàng</h5>
                <div class="bg-light p-3 rounded-3">
                    <p class="mb-1 fw-bold">{{ Auth::user()->name }}</p>
                    <p class="mb-1">SĐT: {{ Auth::user()->phone }}</p>
                    <p class="mb-1" id="location">{{ $order->province }}/{{ $order->district }}/{{ $order->ward }}</p>
                    <p class="mb-0">Địa chỉ chi tiết: {{ Auth::user()->address }}</p>
                </div>
            </div>

            <div class="mb-4">
                <h5 class="fw-semibold mb-3">Trạng Thái Đơn Hàng</h5>
                <ul class="timeline">
                    <li><span class="text-success fw-bold">✔ 15:29 12-02-2025</span> - <span class="text-success">Giao hàng thành công</span></li>
                    <li>07:56 12-02-2025 - Đang vận chuyển</li>
                </ul>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-danger text-uppercase px-3 py-2 rounded-pill">Yêu thích</span>
                <div>
                    <button class="btn btn-outline-primary btn-sm me-2">Chat</button>
                    <button class="btn btn-outline-dark btn-sm">Xem Shop</button>
                </div>
            </div>

            <div class="mb-4">
                @foreach($order->items as $item)
                <div class="d-flex align-items-start border rounded-3 p-3 mb-3 shadow-sm">
                    <img src="{{ asset('images/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="100" class="me-3 rounded-2">
                    <div class="img-padding">
                        <p class="mb-1 fw-bold">{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</p>
                        <p class="mb-1">Giá: {{ number_format($item->price, 0, ',', '.') }} VNĐ</p>
                        <p class="mb-1">Số lượng: {{ $item->quantity }}</p>
                        <p class="mb-0 fw-semibold text-primary">Thành tiền: {{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-end">
                <p class="mb-1">Tổng tiền hàng: <del>{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</del></p>
                <h5 class="fw-bold text-danger">Thành tiền: {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</h5>
                <p class="text-warning mt-2 fw-semibold">Vui lòng thanh toán {{ number_format($order->total_price, 0, ',', '.') }} VNĐ khi nhận hàng.</p>
            </div>
            @if($order->status === 'Chờ Xác Nhận')
            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" style="display: inline-block;">
                @csrf
                <input type="hidden" name="status" value="Đã Xác Nhận">
                <button type="submit" class="btn btn-primary">
                    Xác nhận
                </button>
            </form>

            @elseif($order->status === 'Đã Xác Nhận')
            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" style="display: inline-block;">
                @csrf
                <input type="hidden" name="status" value="Đang Giao">
                <button type="submit" class="btn btn-primary">
                    Xác nhận giao hàng
                </button>
            </form>

            @elseif($order->status === 'Đang Giao')
            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" style="display: inline-block;">
                @csrf
                <input type="hidden" name="status" value="Đã Hoàn Thành">
                <button type="submit" class="btn btn-primary">
                    Xác nhận hoàn thành
                </button>
            </form>

            @elseif($order->status === 'Đã Hoàn Thành')
            <button class="btn btn-success" disabled>
                Đơn hàng đã hoàn thành
            </button>
            @endif
        </div>
    </div>
</div>

<style>
    * {
        font-family: Arial, Helvetica, sans-serif;
    }

    .timeline {
        list-style: none;
        padding-left: 0;
        position: relative;
        margin-left: 1rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 6px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline li {
        position: relative;
        padding-left: 25px;
        margin-bottom: 10px;
    }

    .timeline li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 3px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #6c757d;
    }

    .timeline li:first-child::before {
        background: green;
    }

    .img-padding {
        margin-left: 20px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const locationTd = document.getElementById('location');
        const locationValue = locationTd.textContent.trim();

        const [provinceId, districtId, wardCode] = locationValue.split('/').map(i => i.trim());

        let provinceName = '',
            districtName = '',
            wardName = '';

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
                districtName = district ? district.DistrictName : 'Không rõ quận';
                return fetch(`/address/wards/${districtId}`);
            })
            .then(res => res.json())
            .then(data => {
                const ward = data.data.find(w => w.WardCode == wardCode);
                wardName = ward ? ward.WardName : 'Không rõ phường';
                locationTd.textContent = `${provinceName} / ${districtName} / ${wardName}`;
            })
            .catch(error => {
                console.error('Lỗi khi load địa chỉ:', error);
                locationTd.textContent = 'Không thể hiển thị địa chỉ';
            });
    });
</script>
@endsection
