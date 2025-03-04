@extends('admin.index')

@section('content')
<div class="container-fluid my-4" style="max-width: 80%;">
    <h2 class="text-center mb-4 fw-bold">📊 Thống Kê Tổng Quan</h2>

    <div class="row g-4 text-center">
        @php
            $stats = [
                ['title' => '📦 Tổng Đơn Hàng', 'value' => $totalOrders ?? '0', 'bg' => 'bg-primary'],
                ['title' => '💰 Tổng Doanh Thu', 'value' => number_format($totalRevenue, 0, ',', '.') . ' VNĐ', 'bg' => 'bg-success'],
                ['title' => '📈 Sản Phẩm Đã Bán', 'value' => $totalProductsSold ?? '0', 'bg' => 'bg-warning text-dark'],
                ['title' => '👤 Tổng Người Dùng', 'value' => $totalUsers ?? '0', 'bg' => 'bg-danger']
            ];
        @endphp

        @foreach($stats as $stat)
            <div class="col-md-3 col-12">
                <div class="card shadow border-0 {{ $stat['bg'] }} text-white">
                    <div class="card-body">
                        <h5 class="card-title">{{ $stat['title'] }}</h5>
                        <p class="display-6 fw-bold">{{ $stat['value'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Biểu đồ -->
    <div class="row mt-5">
        <div class="col-md-6 col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h5 class="card-title text-center">📊 Biểu Đồ Doanh Thu</h5>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h5 class="card-title text-center">🔥 Sản Phẩm Bán Chạy</h5>
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng chi tiết -->
    <h3 class="mt-5 text-center">👀 Top 10 Sản Phẩm Được Click Nhiều Nhất</h3>
    <div class="table-responsive">
        <table class="table table-hover text-center mt-3">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Lượt Click</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mostClickedProducts as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->views }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-muted">Chưa có dữ liệu.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const revenueCtx = document.getElementById("revenueChart").getContext("2d");
        new Chart(revenueCtx, {
            type: "bar",
            data: {
                labels: {!! json_encode($topProducts->pluck('name')) !!},
                datasets: [{
                    label: "Doanh Thu (VNĐ)",
                    data: {!! json_encode($topProducts->pluck('total_revenue')) !!},
                    backgroundColor: "rgba(54, 162, 235, 0.6)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1,
                    borderRadius: 5
                }]
            }
        });

        const topProductsCtx = document.getElementById("topProductsChart").getContext("2d");
        new Chart(topProductsCtx, {
            type: "doughnut",
            data: {
                labels: {!! json_encode($topProducts->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($topProducts->pluck('total_quantity')) !!},
                    backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"],
                    hoverOffset: 4
                }]
            }
        });
    });
</script>
@endsection