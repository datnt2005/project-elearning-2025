<h2>Thống kê đơn hàng hoàn thành</h2>

<div class="chart-container">
    <div class="stats-container">
        <div class="stat-card" id="totalOrders"></div>
        <div class="stat-card" id="totalRevenue"></div>
    </div>

    <select id="chartType" class="form-select mb-3">
        <option value="all">Tổng hợp</option>
        <option value="day">Theo ngày</option>
        <option value="month">Theo tháng</option>
        <option value="year">Theo năm</option>
    </select>

    <div class="date-range-container">
        <select id="dateRange" class="custom-select">
            <option value="4">📅 Ngày hôm qua</option>
            <option value="9">📆 7 ngày qua</option>
            <option value="30">📊 30 ngày qua</option>
            <option value="90">📈 90 ngày qua</option>
        </select>
    </div>

    <div class="chart-wrapper" style="height: 400px;">
        <canvas id="ordersSummaryChart"></canvas>
    </div>
</div>

<style>
:root {
    --header-bg: #343a40;
    --primary-btn: #dc3545;
    --primary-btn-hover: #bb2d3b;
    --text-light: #f8f9fa;
    --chart-primary: rgba(44, 62, 80, 0.85);
    --chart-secondary: rgba(211, 84, 0, 0.85);
}

/* Chart Container Styles */
.chart-container {
    position: relative;
    margin: 20px 0;
    padding: 15px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Summary Stats Styles */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.stat-card {
    padding: 15px;
    background: white;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Chart Type Selector */
#chartType {
    width: 100%;
    max-width: 300px;
    padding: 10px 15px;
    font-size: 16px;
    border: 2px solid var(--header-bg);
    border-radius: 8px;
    background-color: white;
    margin-bottom: 20px;
}

/* Date Range Selector */
.date-range-container {
    margin: 20px 0;
}

.custom-select {
    width: 100%;
    max-width: 300px;
    padding: 10px;
    border: 2px solid var(--header-bg);
    border-radius: 8px;
    background: white;
}

/* Modal Styles */
.modal-content {
    border-radius: 10px;
}

.modal-header {
    background: var(--header-bg);
    color: var(--text-light);
    border-radius: 10px 10px 0 0;
}

.modal-footer .btn-secondary {
    background-color: var(--primary-btn);
    border-color: var(--primary-btn);
}

.modal-footer .btn-secondary:hover {
    background-color: var(--primary-btn-hover);
    border-color: var(--primary-btn-hover);
}

/* Responsive Tables */
@media (max-width: 768px) {
    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
    }

    .stats-container {
        grid-template-columns: 1fr;
    }

    .chart-container {
        padding: 10px;
    }

    #chartType, 
    .custom-select {
        max-width: 100%;
    }
}

/* Chart Canvas Styling */
canvas {
    max-width: 100%;
    height: auto !important;
}

/* Header Styling */
h2 {
    color: var(--header-bg);
    text-align: center;
    margin-bottom: 30px;
}
</style>
<br>

<script>
    function toggleDateRange() {
        const chartType = document.getElementById("chartType").value;
        const dateRange = document.getElementById("dateRange");

        if (chartType === "day") {
            dateRange.style.display = "block";
        } else {
            dateRange.style.display = "none";
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Common chart options
    const commonChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: {
                        size: 14,
                        weight: 'bold'
                    },
                    padding: 20
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 14
                },
                padding: 12
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#e9ecef'
                }
            }
        }
    };

    function loadSummaryChart() {
        fetch("/admin/reports/completed-orders-summary")
            .then(response => response.json())
            .then(data => {
                console.log("Dữ liệu API trả về:", data);

                if (!data.data || !Array.isArray(data.data)) {
                    console.error("Lỗi: Dữ liệu không phải là mảng!", data);
                    return;
                }

                let ordersData = data.data;
                let labels = ordersData.map((item, index) => item.period || "Tổng hợp từ trước đến nay");
                let totalOrders = ordersData.map(item => item.total_orders);
                let totalRevenue = ordersData.map(item => Number(item.total_revenue));

                let totalOrdersSum = totalOrders.reduce((acc, value) => acc + value, 0);
                let totalRevenueSum = totalRevenue.reduce((acc, value) => acc + value, 0);
                document.getElementById("totalOrders").innerText = `🛒 Tổng đơn hoàn thành: ${totalOrdersSum} | 💰 Tổng doanh thu: ${totalRevenueSum.toLocaleString()} VND`;
                document.getElementById("totalRevenue").innerText = `💵 Tổng tiền thu được: ${totalRevenueSum.toLocaleString('vi-VN')} VND`;

                let canvas = document.getElementById("ordersSummaryChart");
                if (canvas.chartInstance) {
                    canvas.chartInstance.destroy();
                }
                canvas.style.border = "2px solid #dee2e6";
                canvas.style.borderRadius = "8px";
                canvas.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.1)";
                canvas.style.padding = "10px";
                canvas.style.backgroundColor = "#fff";

                let summaryChartConfig = {
                    ...commonChartOptions,
                    scales: {
                        y: {
                            ...commonChartOptions.scales.y,
                            title: {
                                display: true,
                                text: 'Số đơn hàng',
                                font: { size: 14, weight: 'bold' }
                            }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: 'Doanh thu (VND)',
                                font: { size: 14, weight: 'bold' }
                            }
                        }
                    }
                };

                canvas.chartInstance = new Chart(canvas, {
                    type: "bar",
                    data: {
                        labels: labels,
                        datasets: [{
                                label: "Số đơn hoàn thành",
                                data: totalOrders,
                                backgroundColor: "rgba(44, 62, 80, 0.85)",
                                borderColor: "rgba(44, 62, 80, 1)",
                                borderWidth: 3,
                                borderRadius: 8,
                                hoverBackgroundColor: "rgba(52, 73, 94, 1)",
                                yAxisID: "y"
                            },
                            {
                                label: "Tổng tiền (VND)",
                                data: totalRevenue,
                                backgroundColor: "rgba(211, 84, 0, 0.85)",
                                borderColor: "rgba(211, 84, 0, 1)",
                                borderWidth: 3,
                                borderRadius: 8,
                                hoverBackgroundColor: "rgba(230, 126, 34, 1)",
                                yAxisID: "y1"
                            }
                        ]
                    },
                    options: summaryChartConfig
                });
            })
            .catch(error => console.error("Lỗi khi tải dữ liệu biểu đồ tổng hợp:", error));
    }

    document.addEventListener("DOMContentLoaded", function() {
        loadSummaryChart();
    });

    function loadDayChart() {
        let days = document.getElementById("dateRange").value;
        let endDate = new Date();
        let startDate = new Date();
        startDate.setDate(endDate.getDate() - days + 1); 

        fetch(`/admin/reports/completed-orders-by-date?days=${days}`)
            .then(response => response.json())
            .then(data => {
                let filteredData = data.data.filter(item => {
                    let itemDate = new Date(item.period);
                    return itemDate >= startDate && itemDate <= endDate;
                });

                let labels = filteredData.map(item => item.period);
                let orderValues = filteredData.map(item => item.total_orders);
                let revenueValues = filteredData.map(item => item.total_revenue);
                
                let totalOrders = orderValues.reduce((acc, value) => acc + value, 0);
                let totalRevenue = revenueValues.reduce((acc, value) => acc + value, 0).toLocaleString();
                document.getElementById("totalOrders").innerText = `Tổng số đơn hoàn thành: ${totalOrders} | Tổng doanh thu: ${totalRevenue} VND`;

                let ctx = document.getElementById("ordersDateChart");
                if (window.ordersChart) {
                    window.ordersChart.destroy();
                }

                ctx.style.border = "2px solid #dee2e6";
                ctx.style.borderRadius = "8px";
                ctx.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.1)";
                ctx.style.padding = "10px";
                ctx.style.backgroundColor = "#fff";

                let dayChartConfig = {
                    ...commonChartOptions,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: "Ngày"
                            }
                        },
                        y: {
                            ...commonChartOptions.scales.y,
                            title: {
                                display: true,
                                text: "Số đơn hoàn thành"
                            }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: "Doanh thu (VND)"
                            }
                        }
                    }
                };

                window.ordersChart = new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: "Số đơn hoàn thành",
                                data: orderValues,
                                borderColor: "rgba(33, 150, 243, 1)",
                                backgroundColor: "rgba(33, 150, 243, 0.2)",
                                borderWidth: 3,
                                fill: true,
                                tension: 0.3 
                            },
                            {
                                label: "Tổng doanh thu (VND)",
                                data: revenueValues,
                                borderColor: "rgba(255, 87, 34, 1)",
                                backgroundColor: "rgba(255, 87, 34, 0.2)",
                                borderWidth: 3,
                                fill: true,
                                tension: 0.3,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: dayChartConfig
                });
            })
            .catch(error => console.error("Lỗi khi tải dữ liệu biểu đồ ngày:", error));
    }

    function loadMonthChart() {
        fetch("/admin/reports/completed-orders-by-month")
            .then(response => response.json())
            .then(data => {
                let labels = data.map(item => item.period);
                let orderValues = data.map(item => item.total_orders);
                let revenueValues = data.map(item => item.total_revenue);

                let totalOrders = orderValues.reduce((acc, value) => acc + value, 0);
                let totalRevenue = revenueValues.reduce((acc, value) => acc + parseFloat(value), 0).toLocaleString("vi-VN");
                document.getElementById("totalOrders").innerText = `🛒 Tổng đơn hoàn thành: ${totalOrders} | 💰 Tổng doanh thu: ${totalRevenue} VND`;

                let canvas = document.getElementById("ordersMonthChart");
                if (canvas.chartInstance) {
                    canvas.chartInstance.destroy();
                }
                canvas.style.border = "2px solid #dee2e6";
                canvas.style.borderRadius = "8px";
                canvas.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.1)";
                canvas.style.padding = "10px";
                canvas.style.backgroundColor = "#fff";

                let monthChartConfig = {
                    ...commonChartOptions,
                    scales: {
                        y: {
                            ...commonChartOptions.scales.y,
                            title: {
                                display: true,
                                text: "Số đơn hàng",
                                font: { size: 14, weight: "bold" }
                            }
                        },
                        y1: {
                            position: "right",
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: "Doanh thu (VND)",
                                font: { size: 14, weight: "bold" }
                            }
                        }
                    }
                };

                canvas.chartInstance = new Chart(canvas, {
                    type: "bar",
                    data: {
                        labels: labels,
                        datasets: [{
                                label: "Số đơn hoàn thành",
                                data: orderValues,
                                backgroundColor: "rgba(44, 62, 80, 0.85)",
                                borderColor: "rgba(44, 62, 80, 1)",
                                borderWidth: 2,
                                borderRadius: 6,
                                hoverBackgroundColor: "rgba(52, 73, 94, 1)",
                                yAxisID: "y"
                            },
                            {
                                label: "Tổng tiền (VND)",
                                data: revenueValues,
                                backgroundColor: "rgba(211, 84, 0, 0.85)",
                                borderColor: "rgba(211, 84, 0, 1)",
                                borderWidth: 2,
                                borderRadius: 6,
                                hoverBackgroundColor: "rgba(230, 126, 34, 1)",
                                yAxisID: "y1"
                            }
                        ]
                    },
                    options: monthChartConfig
                });
            })
            .catch(error => console.error("Lỗi khi tải dữ liệu biểu đồ tháng:", error));
    }

    function loadYearChart() {
        fetch("/admin/reports/completed-orders-by-year")
            .then(response => response.json())
            .then(data => {
                let labels = data.map(item => item.period);
                let orderValues = data.map(item => item.total_orders);
                let revenueValues = data.map(item => item.total_revenue);

                let totalOrders = orderValues.reduce((acc, value) => acc + value, 0);
                let totalRevenue = revenueValues.reduce((acc, value) => acc + parseFloat(value), 0);
                document.getElementById("totalOrders").innerText = `🛒 Tổng đơn hoàn thành: ${totalOrders} | 💰 Tổng doanh thu: ${totalRevenue.toLocaleString()} VND`;

                let canvas = document.getElementById("ordersYearChart");
                if (canvas.chartInstance) {
                    canvas.chartInstance.destroy();
                }
                canvas.style.border = "2px solid #dee2e6";
                canvas.style.borderRadius = "8px";
                canvas.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.1)";
                canvas.style.padding = "10px";
                canvas.style.backgroundColor = "#fff";

                let yearChartConfig = {
                    ...commonChartOptions,
                    scales: {
                        y: {
                            ...commonChartOptions.scales.y,
                            title: {
                                display: true,
                                text: "Số đơn hàng",
                                font: { size: 14, weight: "bold" }
                            }
                        },
                        y1: {
                            position: "right",
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: "Doanh thu (VND)",
                                font: { size: 14, weight: "bold" }
                            }
                        }
                    }
                };

                canvas.chartInstance = new Chart(canvas, {
                    type: "bar",
                    data: {
                        labels: labels,
                        datasets: [{
                                label: "Số đơn hoàn thành",
                                data: orderValues,
                                backgroundColor: "rgba(44, 62, 80, 0.85)",
                                borderColor: "rgba(44, 62, 80, 1)",
                                borderWidth: 2,
                                borderRadius: 6,
                                hoverBackgroundColor: "rgba(52, 73, 94, 1)",
                                yAxisID: "y"
                            },
                            {
                                label: "Tổng tiền (VND)",
                                data: revenueValues,
                                backgroundColor: "rgba(211, 84, 0, 0.85)",
                                borderColor: "rgba(211, 84, 0, 1)",
                                borderWidth: 2,
                                borderRadius: 6,
                                hoverBackgroundColor: "rgba(230, 126, 34, 1)",
                                yAxisID: "y1"
                            }
                        ]
                    },
                    options: yearChartConfig
                });
            })
            .catch(error => console.error("Lỗi khi tải dữ liệu biểu đồ năm:", error));
    }

    // Hàm mở Modal và hiển thị chi tiết đơn hàng
    function openOrderDetails(date, details) {
        document.getElementById("modalDate").textContent = date;
        const tableBody = document.getElementById("orderDetailsTable");
        tableBody.innerHTML = ''; // Clear previous content

        details.forEach(detail => {
            const row = document.createElement("tr");
            row.innerHTML = `
            <td>${detail.order_code || 'N/A'}</td>
            <td>${detail.customer_name || 'N/A'}</td>
            <td>${detail.total_amount || 'N/A'}</td>
            <td>${detail.payment_method || 'N/A'}</td>
            <td>${detail.created_at || 'N/A'}</td>
        `;
            tableBody.appendChild(row);
        });

        // Sử dụng Bootstrap để mở modal
        var myModal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
        myModal.show();
    }

    // Hàm đóng Modal
    document.getElementById("closeModalBtn").onclick = function() {
        var myModal = bootstrap.Modal.getInstance(document.getElementById('orderDetailsModal'));
        myModal.hide();
    };

    // Đảm bảo rằng các sự kiện chỉ được gán khi DOM đã sẵn sàng
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("closeModalBtn").addEventListener("click", closeModal);
        document.getElementById("modalOverlay").addEventListener("click", closeModal);

        loadChart();
    });

    // Hàm tải biểu đồ tùy chọn
    function loadChart() {
        const chartType = document.getElementById("chartType").value;

        document.getElementById("summaryChartContainer").style.display = "none";
        document.getElementById("dayChartContainer").style.display = "none";
        document.getElementById("monthChartContainer").style.display = "none";
        document.getElementById("yearChartContainer").style.display = "none";

        if (chartType === "day") {
            document.getElementById("dayChartContainer").style.display = "block";
            loadDayChart();
        } else if (chartType === "month") {
            document.getElementById("monthChartContainer").style.display = "block";
            loadMonthChart();
        } else if (chartType === "year") {
            document.getElementById("yearChartContainer").style.display = "block";
            loadYearChart();
        } else if (chartType === "all") {
            document.getElementById("summaryChartContainer").style.display = "block";
            loadSummaryChart();
        }
    }
</script>

<!-- Modal hiển thị chi tiết đơn hàng -->
<div id="orderDetailsModal" class="modal fade" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Chi tiết đơn hàng <span id="modalDate"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Mã đơn</th>
                            <th scope="col">Người mua</th>
                            <th scope="col">Tổng tiền</th>
                            <th scope="col">Thanh toán</th>
                            <th scope="col">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody id="orderDetailsTable"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Overlay -->
<div id="modalOverlay" class="modal-backdrop fade" style="display:none;"></div>

<!-- Quay lại -->

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle (bao gồm Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>