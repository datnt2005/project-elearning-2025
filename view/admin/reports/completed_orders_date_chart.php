<h2>Thống kê đơn hàng hoàn thành theo ngày</h2>
<p id="totalOrders"><strong>Tổng đơn hàng hoàn thành:</strong> 0</p>
<canvas id="ordersDateChart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    fetch("/admin/reports/completed-orders-by-date")
        .then(response => response.json())
        .then(responseData => {
            let data = responseData.data;
            let totalOrders = responseData.total_orders;

            document.getElementById("totalOrders").innerHTML = `<strong>Tổng đơn hàng hoàn thành:</strong> ${totalOrders}`;

            let labels = data.map(item => item.period);
            let values = data.map(item => item.total);

            let ctx = document.getElementById("ordersDateChart").getContext("2d");
            let chart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Số đơn hoàn thành",
                        data: values,
                        borderColor: "rgba(75, 192, 192, 1)",
                        backgroundColor: "rgba(75, 192, 192, 0.2)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
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
                        }
                    },
                    onClick: function(evt, activeElements) {
                        if (activeElements.length > 0) {
                            let index = activeElements[0].index;
                            let selectedDate = labels[index];

                            fetch(`/admin/reports/completed-orders-detail-date?date=${selectedDate}`)
                                .then(response => response.json())
                                .then(orderData => {
                                    let modal = document.getElementById("orderDetailsModal");
                                    let tableBody = document.getElementById("orderDetailsTable");

                                    document.getElementById("modalDate").innerText = selectedDate;
                                    tableBody.innerHTML = ""; // Xóa dữ liệu cũ

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

                                    modal.style.display = "block"; // Hiển thị modal
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
<a href="/admin/reports" class="btn btn-primary">quay lại</a>
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

/* Modal Overlay */
#modalOverlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    z-index: 999;
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
<div id="orderDetailsModal" style="display:none; position:fixed; top:50px; left:50%; transform:translateX(-50%); background:#fff; padding:20px; border:1px solid #ccc; box-shadow:0px 0px 10px rgba(0,0,0,0.2);">
    <h3>Chi tiết đơn hàng ngày <span id="modalDate"></span></h3>
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
    <button onclick="document.getElementById('orderDetailsModal').style.display='none'" style="margin-top: 10px; padding: 10px 20px; font-size: 16px; background: #f05123; color: white; border: none; border-radius: 5px; cursor: pointer;">Đóng</button>
</div>

