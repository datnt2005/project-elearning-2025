<h2>Thống kê đơn hàng hoàn thành</h2>


<div id="totalOrders" style="margin-bottom: 20px;"></div>
<span id="totalRevenue" style="margin-bottom: 20px;"></span>

<style>

    #chartType {
        padding: 10px 15px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f5f5f5;
        transition: border-color 0.3s, background-color 0.3s;
        cursor: pointer;
    }

    #chartType:focus {
        border-color: #007bff;
        background-color: #e6f7ff;
    }

    #chartType:hover {
        border-color: #007bff;
        background-color: #f0f8ff;
    }

    #chartType option {
        padding: 10px;
        background-color: #ffffff;
        color: #333;
        font-size: 16px;
    }

    #chartType option:hover {
        background-color: #f0f8ff;
    }

    #chartType:focus {
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
</style>
<br>

<select id="chartType" onchange="loadChart()">
    <option value="all">tổng hợp </option>
    <option value="day">Ngày</option>
    <option value="month">Tháng</option>
    <option value="year">Năm</option>
</select>


<div id="summaryChartContainer">
    <canvas id="ordersSummaryChart"></canvas>
</div>

<div id="dayChartContainer" style="display: none;">
    <canvas id="ordersDateChart"></canvas>
    
</div>
<div id="monthChartContainer" style="display: none;">
    <canvas id="ordersMonthChart"></canvas>
</div>
<div id="yearChartContainer" style="display: none;">
    <canvas id="ordersYearChart"></canvas>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

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
                console.log("Dữ liệu chi tiết:", ordersData);

                
                let labels = ordersData.map((item, index) => item.period || `Tổng hợp ${index + 1}`);

                let totalOrders = ordersData.map(item => item.total_orders);
                let totalRevenue = ordersData.map(item => Number(item.total_revenue));

                console.log("Labels:", labels);
                console.log("Total Orders:", totalOrders);
                console.log("Total Revenue:", totalRevenue);

                let totalOrdersSum = totalOrders.reduce((acc, value) => acc + value, 0);
                let totalRevenueSum = totalRevenue.reduce((acc, value) => acc + value, 0);
                document.getElementById("totalOrders").innerText = `Tổng số đơn hoàn thành: ${totalOrdersSum}`;
                document.getElementById("totalRevenue").innerText = `Tổng tiền thu được: ${Number(totalRevenueSum).toLocaleString('vi-VN')} VND`;

                let canvas = document.getElementById("ordersSummaryChart");

                if (canvas.chartInstance) {
                    canvas.chartInstance.destroy();
                }

                canvas.chartInstance = new Chart(canvas, {
                    type: "bar",
                    data: {
                        labels: labels,
                        datasets: [{
                                label: "Số đơn hoàn thành",
                                data: totalOrders,
                                backgroundColor: "rgba(54, 162, 235, 0.5)",
                                borderColor: "rgba(54, 162, 235, 1)",
                                borderWidth: 1,
                                yAxisID: "y"
                            },
                            {
                                label: "Tổng tiền (VND)",
                                data: totalRevenue,
                                backgroundColor: "rgba(255, 99, 132, 0.5)",
                                borderColor: "rgba(255, 99, 132, 1)",
                                borderWidth: 1,
                                yAxisID: "y1"
                            }
                        ]

                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                            y1: {
                                beginAtZero: true,
                                position: "right",
                                grid: {
                                    drawOnChartArea: false
                                },
                            }
                        }
                    }

                });
            })
            .catch(error => console.error("Lỗi khi tải dữ liệu biểu đồ tổng hợp:", error));
    }

    document.addEventListener("DOMContentLoaded", function() {
        loadSummaryChart();
    });


    function loadDayChart() {
    fetch("/admin/reports/completed-orders-by-date")
        .then(response => response.json())
        .then(data => {
            let labels = data.data.map(item => item.period);
            let orderValues = data.data.map(item => item.total_orders);
            let revenueValues = data.data.map(item => item.total_revenue);

          
            let totalOrders = orderValues.reduce((acc, value) => acc + value, 0);
            let totalRevenue = revenueValues.reduce((acc, value) => acc + value, 0).toLocaleString();
            document.getElementById("totalOrders").innerText = `Tổng số đơn hoàn thành: ${totalOrders} | Tổng doanh thu: ${totalRevenue} VND`;
            
            let ctx = document.getElementById("ordersDateChart");
            let chart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Số đơn hoàn thành",
                            data: orderValues,
                            borderColor: "rgba(75, 192, 192, 1)",
                            backgroundColor: "rgba(75, 192, 192, 0.2)",
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: "Tổng doanh thu (VND)",
                            data: revenueValues,
                            borderColor: "rgba(255, 99, 132, 1)",
                            backgroundColor: "rgba(255, 99, 132, 0.2)",
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: "Ngày"
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: "Số đơn hoàn thành"
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: "Tổng doanh thu (VND)"
                            }
                        }
                    },
                    onClick: function(evt, activeElements) {
                        if (activeElements.length > 0) {
                            let index = activeElements[0].index;
                            let selectedDate = labels[index];
                            fetch(`/admin/reports/completed-orders-detail-date?date=${selectedDate}`)
                                .then(response => response.json())
                                .then(orderData => {
                                    openOrderDetails(selectedDate, orderData.orders);
                                })
                                .catch(error => console.error("Error fetching order details:", error));
                        }
                    }
                }
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

            // Hiển thị tổng số đơn hoàn thành và tổng doanh thu
            let totalOrders = orderValues.reduce((acc, value) => acc + value, 0);
            let totalRevenue = revenueValues.reduce((acc, value) => acc + parseFloat(value), 0).toLocaleString("vi-VN");
            document.getElementById("totalOrders").innerText = `Tổng số đơn hoàn thành: ${totalOrders} | Tổng doanh thu: ${totalRevenue} VND`;

            new Chart(document.getElementById("ordersMonthChart"), {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Số đơn hoàn thành",
                            data: orderValues,
                            backgroundColor: "rgba(54, 162, 235, 0.5)",
                            borderColor: "rgba(54, 162, 235, 1)",
                            borderWidth: 1
                        },
                        {
                            label: "Tổng doanh thu (VND)",
                            data: revenueValues,
                            backgroundColor: "rgba(255, 99, 132, 0.5)",
                            borderColor: "rgba(255, 99, 132, 1)",
                            borderWidth: 1,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: "Số đơn hoàn thành"
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: "Tổng doanh thu (VND)"
                            }
                        }
                    },
                    onClick: function(evt, activeElements) {
                        if (activeElements.length > 0) {
                            let index = activeElements[0].index;
                            let selectedMonth = labels[index];
                            fetch(`/admin/reports/completed-orders-detail-month?month=${selectedMonth}`)
                                .then(response => response.json())
                                .then(orderData => {
                                    openOrderDetails(selectedMonth, orderData.orders);
                                })
                                .catch(error => console.error("Error fetching order details:", error));
                        }
                    }
                }
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
            document.getElementById("totalOrders").innerText = `Tổng số đơn hoàn thành: ${totalOrders} | Tổng doanh thu: ${totalRevenue.toLocaleString()} VND`;
            new Chart(document.getElementById("ordersYearChart"), {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Số đơn hoàn thành",
                            data: orderValues,
                            backgroundColor: "rgba(54, 162, 235, 0.5)",
                            borderColor: "rgba(54, 162, 235, 1)",
                            borderWidth: 1
                        },
                        {
                            label: "Tổng doanh thu (VND)",
                            data: revenueValues,
                            backgroundColor: "rgba(255, 99, 132, 0.5)",
                            borderColor: "rgba(255, 99, 132, 1)",
                            borderWidth: 1,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: "Số đơn hoàn thành"
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: "Tổng doanh thu (VND)"
                            }
                        }
                    },
                    onClick: function(evt, activeElements) {
                        if (activeElements.length > 0) {
                            let index = activeElements[0].index;
                            let selectedYear = labels[index];
                            fetch(`/admin/reports/completed-orders-detail-year?year=${selectedYear}`)
                                .then(response => response.json())
                                .then(orderData => {
                                    openOrderDetails(selectedYear, orderData.orders);
                                })
                                .catch(error => console.error("Error fetching order details:", error));
                        }
                    }
                }
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
                <h5 class="modal-title" id="orderDetailsModalLabel">Chi tiết đơn hàng ngày <span id="modalDate"></span></h5>
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