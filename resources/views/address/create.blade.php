@extends('layouts.app2')

@section('content')
<br><br><br>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
        @include('layouts.sidebar')
        </div>
        <div class="col-md-9">
            <div class="row justify-content-center">
                <div class=" col-md-12">
                    <div class="card shadow rounded-4 border-0">
                        <div class="card-body p-5">
                            <h3 class="mb-4 text-center fw-bold">Thêm địa chỉ mới</h3>
                            <form action="{{ route('addresses.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Họ và tên</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Địa chỉ chi tiết</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Tỉnh/Thành phố</label>
                                        <select class="form-select" name="province" id="province" required>
                                            <option value="">Chọn tỉnh/thành</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Quận/Huyện</label>
                                        <select class="form-select" name="district" id="district" disabled required>
                                            <option value="">Chọn quận/huyện</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phường/Xã</label>
                                        <select class="form-select" name="ward" id="ward" disabled required>
                                            <option value="">Chọn phường/xã</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('addresses.index') }}" class="btn btn-outline-secondary">Quay lại</a>
                                    <button type="submit" class="btn btn-success px-4">Lưu địa chỉ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('province');
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');

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
            .catch(error => console.error('Lỗi tải tỉnh:', error));

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
                    .catch(error => console.error('Lỗi tải quận/huyện:', error));
            }
        });

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
                    .catch(error => console.error('Lỗi tải phường/xã:', error));
            }
        });
    });
</script>
@endsection
