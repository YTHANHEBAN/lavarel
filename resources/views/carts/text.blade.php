<select id="province" class="form-select">
    <option value="">Chọn tỉnh/thành</option>
</select>

<select id="district" class="form-select" disabled>
    <option value="">Chọn quận/huyện</option>
</select>

<select id="ward" class="form-select" disabled>
    <option value="">Chọn phường/xã</option>
</select>

<script>
document.addEventListener('DOMContentLoaded', function () {
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
    provinceSelect.addEventListener('change', function () {
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
    districtSelect.addEventListener('change', function () {
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
</script>
