@extends('layouts.app2')

@section('content')
<br><br><br><br>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
        @include('layouts.sidebar')
        </div>
        <div class="col-md-9">
            <h2>Chỉnh sửa địa chỉ</h2>

            <form action="{{ route('addresses.update', $address->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Tên</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $address->name) }}">
                </div>

                <div class="mb-3">
                    <label>Điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $address->phone) }}">
                </div>

                <div class="mb-3">
                    <label>Địa chỉ chi tiết</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $address->address) }}">
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tỉnh/Thành phố</label>
                        <select class="form-select" name="province" id="province" value="{{ old('province', $address->province) }}">
                            <option value="{{ old('province', $address->province) }}">Chọn tỉnh/thành</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Quận/Huyện</label>
                        <select class="form-select" name="district" id="district" disabled value="{{ old('district', $address->district) }}">
                            <option value="{{ old('district', $address->district) }}">Chọn quận/huyện</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phường/Xã</label>
                        <select class="form-select" name="ward" id="ward" disabled value="{{ old('ward', $address->ward) }}">
                            <option value="{{ old('ward', $address->ward) }}">Chọn phường/xã</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('addresses.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
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
