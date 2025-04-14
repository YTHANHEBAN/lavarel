@extends('layouts.admin')
@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Thống kê doanh thu</h2>

    <!-- Thống kê tổng -->
    <div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm p-4 text-center border-start border-success border-4">
            <div class="mb-2">
                <i class="fas fa-money-bill-wave fa-2x text-success"></i>
            </div>
            <h6 class="fw-bold text-muted">Tổng doanh thu</h6>
            <p class="fs-4 text-success mb-0">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm p-4 text-center border-start border-primary border-4">
            <div class="mb-2">
                <i class="fas fa-shopping-cart fa-2x text-primary"></i>
            </div>
            <h6 class="fw-bold text-muted">Tổng số đơn hàng</h6>
            <p class="fs-4 text-primary mb-0">{{ $totalOrders }}</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm p-4 text-center border-start border-info border-4">
            <div class="mb-2">
                <i class="fas fa-check-circle fa-2x text-info"></i>
            </div>
            <h6 class="fw-bold text-muted">Số đơn đã giao</h6>
            <p class="fs-4 text-info mb-0">{{ $completedOrders }}</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm p-4 text-center border-start border-warning border-4">
            <div class="mb-2">
                <i class="fas fa-boxes fa-2x text-warning"></i>
            </div>
            <h6 class="fw-bold text-muted">Sản phẩm tồn kho</h6>
            <p class="fs-4 text-warning mb-0">{{ $totalStockQuantity }}</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm p-4 text-center border-start border-danger border-4">
            <div class="mb-2">
                <i class="fas fa-chart-line fa-2x text-danger"></i>
            </div>
            <h6 class="fw-bold text-muted">Lãi</h6>
            <p class="fs-4 text-danger mb-0">{{ number_format($lai, 0, ',', '.') }} VNĐ</p>
        </div>
    </div>
</div>


    <!-- Biểu đồ doanh thu -->
    <h4 class="mt-5 mb-3">Biểu đồ doanh thu theo tháng</h4>
    <canvas id="revenueChart" height="120"></canvas>

    <!-- Bảng doanh thu -->
    <table class="table table-bordered mt-5">
        <thead class="table-dark">
            <tr>
                <th>Tháng</th>
                <th>Doanh thu (VNĐ)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyRevenue as $month)
                <tr>
                    <td>Tháng {{ $month->month }}</td>
                    <td>{{ number_format($month->revenue, 0, ',', '.') }} VNĐ</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Thêm thư viện Chart.js và script vẽ biểu đồ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const revenueData = @json($monthlyRevenue);

    const labels = revenueData.map(item => 'Tháng ' + item.month);
    const data = revenueData.map(item => item.revenue);

    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' VNĐ';
                        }
                    }
                }
            },
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Doanh thu theo tháng'
                }
            }
        }
    });
</script>
@endsection
