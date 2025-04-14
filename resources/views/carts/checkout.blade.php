@extends('layouts.app2')
<!-- Start Slider -->
@section('content')
<!-- Start Cart  -->
<br><br><br><br>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }

    h3,
    h5 {
        color: #2c3e50;
    }

    .card {
        border-radius: 12px;
        border: none;
        background-color: #fff;
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .card-body {
        padding: 2rem;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 0.6rem 0.75rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #5dade2;
        box-shadow: 0 0 0 0.2rem rgba(93, 173, 226, 0.25);
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.6rem 1rem;
    }

    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .btn-success {
        background-color: #2ecc71;
        border-color: #2ecc71;
    }

    .btn-success:hover {
        background-color: #27ae60;
        border-color: #27ae60;
    }

    .btn-danger {
        background-color: #e74c3c;
        border-color: #e74c3c;
    }

    .btn-danger:hover {
        background-color: #c0392b;
        border-color: #c0392b;
    }

    .form-check-input:checked {
        background-color: #3498db;
        border-color: #3498db;
    }

    .form-check-label {
        font-weight: 500;
    }

    .form-check .text-muted {
        font-size: 0.85rem;
        margin-left: 1.5rem;
        color: #7f8c8d !important;
    }

    .d-flex.justify-content-between span,
    .d-flex.justify-content-between strong {
        font-weight: 500;
    }

    .d-flex.justify-content-between h5 {
        margin: 0;
    }

    a {
        color: #2980b9;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>

<form action="{{ route('checkout.process') }}" method="POST">
    @csrf
    <div class="container py-5">
        <div class="row g-4">
            <!-- Địa chỉ thanh toán -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h3 class="mb-3">Địa chỉ thanh toán</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tên *</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Họ *</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Tên người dùng *</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Địa chỉ email *</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Địa chỉ chi tiết *</label>
                                <select class="form-select w-100" name="address" id="address" required style="min-width: 100%; padding: 12px;">
                                    <option value="">-- Chọn địa chỉ --</option>
                                    @foreach ($addresses as $address)
                                    <option value="{{ $address->id }}" id="location">{{ $address->province }}/{{ $address->district }}/{{ $address->ward }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tỉnh/Thành</label>
                                <select class="form-select" name="province" id="province">
                                    <option value="">Chọn tỉnh/thành</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Quận/Huyện</label>
                                <select class="form-select" name="district" id="district" disabled>
                                    <option value="">Chọn quận/huyện</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phường/Xã</label>
                                <select class="form-select" name="ward" id="ward" disabled>
                                    <option value="">Chọn phường/xã</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mã bưu điện</label>
                                <input type="text" name="postal_code" class="form-control" required>
                            </div>
                        </div>

                        <hr>
                        <h5>Phương thức thanh toán</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentmethod" id="cod" value="COD" checked>
                            <label class="form-check-label" for="cod">Thanh toán khi nhận hàng</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentmethod" id="vnpay" value="VNPAY">
                            <label class="form-check-label" for="vnpay">VNPAY</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="paymentmethod" id="paypal" value="PAYPAL">
                            <label class="form-check-label" for="momo">MOMO</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phương thức giao hàng và đơn hàng -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h3 class="mb-3">Phương thức giao hàng</h3>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shipping" id="shipping1" value="standard" checked>
                            <label class="form-check-label d-flex justify-content-between" for="shipping1">
                                Giao hàng tiêu chuẩn <span>MIỄN PHÍ</span>
                            </label>
                            <div class="small text-muted ps-4">(3–7 ngày làm việc)</div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shipping" id="shipping2" value="fast">
                            <label class="form-check-label d-flex justify-content-between" for="shipping2">
                                Giao hàng nhanh <span>$10.00</span>
                            </label>
                            <div class="small text-muted ps-4">(2–4 ngày làm việc)</div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shipping" id="shipping3" value="express">
                            <label class="form-check-label d-flex justify-content-between" for="shipping3">
                                Giao hàng ngày mai <span>$20.00</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="mb-3">Tóm tắt đơn hàng</h3>
                        <div class="mb-2">
                            @foreach ($carts as $cart)
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <div>
                                    <strong>{{ $cart->name }}</strong><br>
                                    <small class="text-muted">Giá: ${{ $cart->price }} | SL: {{ $cart->qty }} | Tạm tính: ${{ $cart->price * $cart->qty }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <span>Tổng phụ</span><strong>$440</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Giảm giá</span><strong>$40</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Giảm mã khuyến mãi</span><strong>$10</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Thuế</span><strong>$2</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Phí giao hàng</span><strong>Miễn phí</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5>Tổng cộng</h5>
                            <h5>$388</h5>
                        </div>

                        <button type="submit" class="btn btn-danger w-100 mt-3">Đặt hàng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('province');
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');

        // Load danh sách tỉnh
        fetch('/address/provinces')
            .then(res => res.json())
            .then(data => {
                if (data.data && Array.isArray(data.data)) {
                    data.data.forEach(province => {
                        let option = document.createElement('option');
                        option.value = province.ProvinceID;
                        option.textContent = province.ProvinceName;
                        provinceSelect.appendChild(option);
                    });
                    provinceSelect.disabled = false;
                }
            })
            .catch(error => console.error('Lỗi load tỉnh:', error));

        // Khi chọn tỉnh
        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            districtSelect.disabled = true;
            wardSelect.disabled = true;

            if (provinceId) {
                fetch(`/address/districts/${provinceId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.data && Array.isArray(data.data)) {
                            data.data.forEach(district => {
                                let option = document.createElement('option');
                                option.value = district.DistrictID;
                                option.textContent = district.DistrictName;
                                districtSelect.appendChild(option);
                            });
                            districtSelect.disabled = false;
                        }
                    })
                    .catch(error => console.error('Lỗi load quận:', error));
            }
        });

        // Khi chọn quận
        districtSelect.addEventListener('change', function() {
            const districtId = this.value;
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            wardSelect.disabled = true;

            if (districtId) {
                fetch(`/address/wards/${districtId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.data && Array.isArray(data.data)) {
                            data.data.forEach(ward => {
                                let option = document.createElement('option');
                                option.value = ward.WardCode;
                                option.textContent = ward.WardName;
                                wardSelect.appendChild(option);
                            });
                            wardSelect.disabled = false;
                        }
                    })
                    .catch(error => console.error('Lỗi load phường:', error));
            }
        });
    });

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
