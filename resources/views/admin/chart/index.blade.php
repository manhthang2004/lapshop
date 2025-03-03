@extends('admin.index')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row justify-content-center mt-4">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">📊 Thống Kê Doanh Thu</h4>
                    </div>
                    <div class="card-body">
                        <!-- Chọn ngày -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="startDate" class="form-label fw-bold">📅 Ngày bắt đầu:</label>
                                <input type="date" id="startDate" class="form-control" value="{{ isset($labels) && count($labels) > 0 ? $labels[0] : date('Y-m-01') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="endDate" class="form-label fw-bold">📅 Ngày kết thúc:</label>
                                <input type="date" id="endDate" class="form-control" value="{{ isset($labels) && count($labels) > 0 ? $labels[count($labels) - 1] : date('Y-m-d') }}">
                            </div>
                        </div>

                        <!-- Biểu đồ -->
                        <div class="chart-container p-3" style="background: rgba(54, 162, 235, 0.1); border-radius: 10px;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Thư viện Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const labels = {!! json_encode($labels ?? []) !!};
        const dataValues = {!! json_encode($data ?? []) !!};

        const data = {
            labels: labels,
            datasets: [{
                label: '📈 Doanh Thu (VNĐ)',
                data: dataValues,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        const config = {
            type: "bar",
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#1A1A1A',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                },
                scales: {
                    x: {
                        type: "category",
                        ticks: {
                            color: "#333",
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: "#333",
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        };

        const revenueChart = new Chart(document.getElementById("revenueChart"), config);

        // Cập nhật ngày bắt đầu
        document.getElementById("startDate").addEventListener("change", function() {
            revenueChart.options.scales.x.min = this.value;
            revenueChart.update();
        });

        // Cập nhật ngày kết thúc
        document.getElementById("endDate").addEventListener("change", function() {
            revenueChart.options.scales.x.max = this.value;
            revenueChart.update();
        });
    });
</script>

@endsection
