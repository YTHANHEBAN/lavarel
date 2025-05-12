@extends('layouts.app2')

@section('content')
<br><br>
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">Chi Tiết Đơn Hàng</h4>

            <!-- Địa chỉ nhận hàng -->
            <div class="mb-4">
                <h5 class="fw-semibold mb-3">Địa Chỉ Nhận Hàng</h5>
                <div class="bg-light p-3 rounded-3">
                    <p class="mb-1 fw-bold">{{ Auth::user()->name }}</p>
                    <p class="mb-1">SĐT: {{ Auth::user()->phone }}</p>
                    <p class="mb-1" id="location">{{ $order->province }}/{{ $order->district }}/{{ $order->ward }}</p>
                    <p class="mb-0">Địa chỉ chi tiết: {{ Auth::user()->address }}</p>
                </div>
            </div>

            <!-- Trạng thái đơn hàng -->
            <div class="mb-4">
                <h5 class="fw-semibold mb-3">Trạng Thái Đơn Hàng</h5>
                <ul class="timeline">
                    <li><span class="text-success fw-bold">✔ {{ $order->updated_at }}</span> - <span class="text-success">{{ $order->status }}</span></li>
                </ul>
            </div>

            <!-- Nút yêu thích + Chat + Xem Shop -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-danger text-uppercase px-3 py-2 rounded-pill">Yêu thích</span>
                <div>
                    <button class="btn btn-outline-primary btn-sm me-2">Chat</button>
                    <button class="btn btn-outline-dark btn-sm">Xem Shop</button>
                </div>
            </div>

            <!-- Chi tiết sản phẩm trong đơn -->
            <div class="mb-4">
                @foreach($order->items as $item)
                    @php
                        $item->reviewed = $item->product->reviews()->where('user_id', Auth::id())->exists();
                    @endphp

                    <div class="d-flex align-items-start border rounded-3 p-3 mb-3 shadow-sm w-100">
                        <img src="{{ asset('images/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="100" class="me-3 rounded-2">

                        <div class="img-padding flex-grow-1">
                            <p class="mb-1 fw-bold">{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</p>
                            <p class="mb-1">Giá: {{ number_format($item->price, 0, ',', '.') }} VNĐ</p>
                            <p class="mb-1">Số lượng: {{ $item->quantity }}</p>
                            <p class="mb-0 fw-semibold text-primary">Thành tiền: {{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ</p>

                            @if($order->status === 'Đã Hoàn Thành')
                                @if(!$item->reviewed)
                                    <!-- Nút đánh giá -->
                                    <button type="button" onclick="toggleReviewForm({{ $item->id }})" class="btn btn-primary mt-2">
                                        Đánh giá
                                    </button>

                                    <!-- Form đánh giá -->
                                    <form action="{{ route('products.review.store', $item->product->id) }}" method="POST" class="mt-3 d-none" id="review-form-{{ $item->id }}">
                                        @csrf
                                        <label for="rating-{{ $item->id }}">Số sao:</label>
                                        <select name="rating" id="rating-{{ $item->id }}" class="form-select form-select-sm w-auto mb-2" required>
                                            @for ($i = 5; $i >= 1; $i--)
                                                <option value="{{ $i }}">{{ $i }} sao</option>
                                            @endfor
                                        </select>

                                        <label for="comment-{{ $item->id }}">Nhận xét:</label>
                                        <textarea name="comment" id="comment-{{ $item->id }}" rows="3" class="form-control mb-2" placeholder="Nhập nhận xét của bạn..."></textarea>

                                        <button type="submit" class="btn btn-success btn-sm">Gửi đánh giá</button>
                                    </form>
                                @else
                                    <div class="mt-2 text-success fw-semibold">
                                    <a href="/products/detail/{{ $item->product->id }}" class="mt-2 text-success fw-semibold" ><i class="bi bi-check-circle"></i> Bạn đã đánh giá sản phẩm này.</a>
                                    </div>
                                @endif

                                <!-- Mua lại -->
                                <form action="/carts/add" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                    <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                    <input type="hidden" name="price" value="{{ $item->product->price }}">
                                    <input type="hidden" name="total_price" value="{{ $item->product->price }}">
                                    <input type="hidden" name="input_price" value="{{ $item->product->input_price }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-success btn-sm">Mua Lại</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Hủy đơn nếu còn chờ xác nhận -->
            @if($order->status === 'Chờ Xác Nhận')
                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?');">
                    @csrf
                    <input type="hidden" name="status" value="Đã Hủy">
                    <button type="submit" class="btn btn-danger">Hủy Đơn</button>
                </form>
            @endif

            <!-- Tổng kết -->
            <div class="text-end">
                <p class="mb-1">Tổng tiền hàng: <del>{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</del></p>
                <h5 class="fw-bold text-danger">Thành tiền: {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</h5>
                <p class="text-warning mt-2 fw-semibold">Vui lòng thanh toán {{ number_format($order->total_price, 0, ',', '.') }} VNĐ khi nhận hàng.</p>
                <p id="shipping-fee" class="mt-2 fw-semibold d-flex align-items-center">
                    <span id="shipping-status-icon" class="spinner-border spinner-border-sm text-primary me-2" role="status" aria-hidden="true"></span>
                    <span id="shipping-status-text" class="text-primary">Đang tính phí vận chuyển...</span>
                </p>
            </div>
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

    document.addEventListener('DOMContentLoaded', function() {
        // Đảm bảo địa chỉ đã hiển thị đúng mới tính phí ship
        setTimeout(() => {
            const location = document.getElementById('location').textContent.trim();
            const [provinceId, districtId, wardCode] = location.split('/').map(i => i.trim());

            fetch('{{ route("calculate.shipping") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        district_id: districtId,
                        ward_code: wardCode,
                        weight: 100, // hoặc bạn có thể tùy chỉnh theo sản phẩm
                        insurance_value: 200000
                    })
                })
                .then(res => res.json())
                .then(data => {
                    const fee = data.data.total || 0;
                    const statusText = document.getElementById('shipping-status-text');
                    const statusIcon = document.getElementById('shipping-status-icon');

                    statusIcon.classList.remove('spinner-border');
                    if (fee === 0) {
                        statusText.innerHTML = '<span class="text-success">Miễn phí vận chuyển</span>';
                    } else {
                        statusText.innerHTML = `Phí vận chuyển: ${fee.toLocaleString()} VNĐ`;
                    }
                })
                .catch(err => {
                    console.error("Lỗi tính phí ship:", err);
                    document.getElementById('shipping-status-text').textContent = 'Không thể tính phí vận chuyển';
                });
        }, 1500); // delay nhẹ để chắc chắn địa chỉ được cập nhật
    });

    function toggleReviewForm(itemId) {
        const form = document.getElementById(`review-form-${itemId}`);
        form.classList.toggle('d-none');
    }
</script>
@endsection
