<div class="row">
    <div class="col-md-5 mb-3">
        <label for="country">Country *</label>
        <select class="wide w-100" id="country" name="country" required>
            <option value="" data-display="Select">Choose...</option>
        </select>
        <div class="invalid-feedback">Please select a valid country.</div>
    </div>

    <div class="col-md-4 mb-3">
        <label for="state">State *</label>
        <select class="wide w-100" id="state" name="state" required>
            <option value="" data-display="Select">Choose...</option>
        </select>
        <div class="invalid-feedback">Please provide a valid state.</div>
    </div>

    <div class="col-md-4 mb-3">
        <label for="ward">Ward *</label>
        <select class="wide w-100" id="ward" name="ward" required>
            <option value="" data-display="Select">Choose...</option>
        </select>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function () {
        const elmCountry = document.getElementById('country');
        const elmState = document.getElementById('state');
        const elmWard = document.getElementById('ward');

        // Load provinces (countries)
        fetch('/address/province')
            .then((response) => {
                if (!response.ok) throw new Error('Failed to fetch provinces.');
                return response.json();
            })
            .then((data) => {
                if (Array.isArray(data.data)) {
                    data.data.forEach((item) => {
                        const option = document.createElement('option');
                        option.value = item.ProvinceID;
                        option.textContent = item.ProvinceName;
                        elmCountry.appendChild(option);
                    });
                }
            })
            .catch((error) => {
                console.error('Error loading provinces:', error);
            });

        // When province changes, load districts
        elmCountry.addEventListener('change', function () {
            const provinceId = this.value;
            if (!provinceId) return;

            elmState.innerHTML = '<option value="" data-display="Select">Choose...</option>';
            elmWard.innerHTML = '<option value="" data-display="Select">Choose...</option>';

            fetch('/address/district/' + provinceId)
                .then((response) => {
                    if (!response.ok) throw new Error('Failed to fetch districts.');
                    return response.json();
                })
                .then((data) => {
                    if (Array.isArray(data.data)) {
                        data.data.forEach((item) => {
                            const option = document.createElement('option');
                            option.value = item.DistrictID;
                            option.textContent = item.DistrictName;
                            elmState.appendChild(option);
                        });
                    }
                })
                .catch((error) => {
                    console.error('Error loading districts:', error);
                });
        });

        // When district changes, load wards
        elmState.addEventListener('change', function () {
            const districtId = this.value;
            if (!districtId) return;

            elmWard.innerHTML = '<option value="" data-display="Select">Choose...</option>';

            fetch('/address/ward/' + districtId)
                .then((response) => {
                    if (!response.ok) throw new Error('Failed to fetch wards.');
                    return response.json();
                })
                .then((data) => {
                    if (Array.isArray(data.data)) {
                        data.data.forEach((item) => {
                            const option = document.createElement('option');
                            option.value = item.WardCode;
                            option.textContent = item.WardName;
                            elmWard.appendChild(option);
                        });
                    }
                })
                .catch((error) => {
                    console.error('Error loading wards:', error);
                });
        });
    });
</script>
@endsection
