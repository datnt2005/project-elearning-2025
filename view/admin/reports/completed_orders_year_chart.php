<h2>Thống kê đơn hàng hoàn thành theo năm</h2>
<canvas id="ordersYearChart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    fetch("/admin/reports/completed-orders-by-year")
        .then(response => response.json())
        .then(data => {
            let labels = data.map(item => item.period);
            let values = data.map(item => item.total);

            let chart = new Chart(document.getElementById("ordersYearChart"), {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Số đơn hoàn thành",
                        data: values,
                        backgroundColor: "rgba(255, 99, 132, 0.5)",
                        borderColor: "rgba(255, 99, 132, 1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    onClick: function(evt, activeElements) {
                        if (activeElements.length > 0) {
                            let index = activeElements[0].index;
                            let selectedYear = labels[index];

                            fetch(`/admin/reports/completed-orders-detail-year?year=${selectedYear}`)
                                .then(response => response.json())
                                .then(orderData => {
                                    console.log("Order Data:", orderData);

                                    if (!orderData || !Array.isArray(orderData.orders)) {
                                        console.error("Invalid order data format:", orderData);
                                        return;
                                    }

                                    let modal = document.getElementById("orderDetailsModal");
                                    let tableBody = document.getElementById("orderDetailsTable");

                                    document.getElementById("modalYear").innerText = selectedYear;
                                    tableBody.innerHTML = "";

                                    orderData.orders.forEach(order => {
                                        let row = `<tr>
                                            <td>${order.order_code}</td>
                                            <td>${order.customer_name}</td>
                                            <td>${order.total_amount}</td>
                                            <td>${order.payment_method}</td>
                                            <td>${order.created_at}</td>
                                        </tr>`;
                                        tableBody.innerHTML += row;
                                    });

                                    modal.style.display = "block";
                                })
                                .catch(error => console.error("Error fetching order details:", error));
                        }
                    }
                }
            });
        })
        .catch(error => console.error("Error loading chart data:", error));
});
</script>

<a href="/admin/reports" class="btn btn-primary">Quay lại</a>

<style>
/* Modal Container */
#orderDetailsModal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    width: 90%;
    max-width: 600px;
    z-index: 1000;
    animation: fadeIn 0.3s ease-in-out;
}

/* Modal Header */
#orderDetailsModal h3 {
    margin-bottom: 15px;
    text-align: center;
    font-size: 20px;
    font-weight: bold;
}

/* Table Styling */
#orderDetailsModal table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

#orderDetailsModal th, 
#orderDetailsModal td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

#orderDetailsModal th {
    background: #007BFF;
    color: white;
}

/* Close Button */
#closeModalBtn {
    display: block;
    width: 100%;
    margin-top: 15px;
    padding: 8px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.2s;
}

#closeModalBtn:hover {
    background: #c82333;
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translate(-50%, -55%);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}
</style>

<!-- Modal hiển thị chi tiết đơn hàng -->
<div id="orderDetailsModal">
    <h3>Chi tiết đơn hàng năm <span id="modalYear"></span></h3>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Người mua</th>
                <th>Tổng tiền</th>
                <th>Thanh toán</th>
                <th>Thời gian</th>
            </tr>
        </thead>
        <tbody id="orderDetailsTable"></tbody>
    </table>
    <button onclick="document.getElementById('orderDetailsModal').style.display='none'" id="closeModalBtn">Đóng</button>
</div>
