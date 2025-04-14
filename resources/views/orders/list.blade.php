@extends('layouts.admin')

@section('content')
<div class="container my-5">
    <h3 class="mb-4 fw-bold">Quản Lý Đơn Hàng</h3>

    @if($orders->count())
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle shadow-sm bg-white rounded">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Người Đặt</th>
                    <th scope="col">SĐT</th>
                    <th scope="col">Địa Chỉ</th>
                    <th scope="col">Phương Thức</th>
                    <th scope="col">Tổng Tiền</th>
                    <th scope="col">Trạng Thái</th>
                    <th scope="col">Ngày Đặt</th>
                    <th scope="col">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->user->phone ?? 'Chưa có SĐT' }}</td>
                    <td class="location" data-province="{{ $order->province }}" data-district="{{ $order->district }}" data-ward="{{ $order->ward }}">
                        {{ $order->province }} / {{ $order->district }} / {{ $order->ward }}
                    </td>
                    <td>{{ $order->paymentmethod ?? 'Chưa chọn' }}</td>
                    <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                    <td><span class="badge bg-info">{{ $order->status }}</span></td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('show_admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">Xem</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-info">
        Chưa có đơn hàng nào.
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

            let provinceName = '', districtName = '', wardName = '';

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
                .catch(() => {
                    td.textContent = 'Không thể hiển thị địa chỉ';
                });
        });
    });
</script>
@endsection
