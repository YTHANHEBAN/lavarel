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
    </div>

    <!-- Biểu đồ doanh thu -->
    <h4 class="mt-5 mb-3">Biểu đồ doanh thu theo tháng</h4>
    <canvas id="revenueChart" height="100"></canvas>

    <h4 class="mt-5 mb-3">Biểu đồ doanh thu theo tuần</h4>
    <canvas id="weeklyChart" height="100"></canvas>

    <h4 class="mt-5 mb-3">Biểu đồ doanh thu theo ngày</h4>
    <canvas id="dailyChart" height="100"></canvas>

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

<!-- Thư viện Chart.js và script vẽ biểu đồ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthlyRevenue = @json($monthlyRevenue);
    const weeklyRevenue = @json($weeklyRevenue);
    const dailyRevenue = @json($dailyRevenue);

    // Biểu đồ tháng
    const monthlyLabels = monthlyRevenue.map(item => 'Tháng ' + item.month);
    const monthlyData = monthlyRevenue.map(item => item.revenue);

    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Doanh thu theo tháng (VNĐ)',
                data: monthlyData,
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
                        callback: value => value.toLocaleString('vi-VN') + ' VNĐ'
                    }
                }
            }
        }
    });

    // Biểu đồ tuần
    const weeklyLabels = weeklyRevenue.map(item => 'Tuần ' + item.week);
    const weeklyData = weeklyRevenue.map(item => item.revenue);

    new Chart(document.getElementById('weeklyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: weeklyLabels,
            datasets: [{
                label: 'Doanh thu theo tuần (VNĐ)',
                data: weeklyData,
                backgroundColor: 'rgba(255, 159, 64, 0.6)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => value.toLocaleString('vi-VN') + ' VNĐ'
                    }
                }
            }
        }
    });

    // Biểu đồ ngày
    const dailyLabels = dailyRevenue.map(item => item.date);
    const dailyData = dailyRevenue.map(item => item.revenue);

    new Chart(document.getElementById('dailyChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Doanh thu theo ngày (VNĐ)',
                data: dailyData,
                backgroundColor: 'rgba(75, 192, 192, 0.4)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => value.toLocaleString('vi-VN') + ' VNĐ'
                    }
                }
            }
        }
    });
</script>
@endsection
