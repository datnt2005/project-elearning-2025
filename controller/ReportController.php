<?php
require_once "model/OrderModel.php";
require_once "view/helpers.php";

class ReportController
{
    private $orderModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
    }

    // Hiển thị danh sách báo cáo
    public function index()
    {
        renderViewAdmin("view/admin/reports/report_list.php", [], "Report List");
    }
    public function completedOrdersByDateChart()
    {
        header('Content-Type: application/json'); // Đảm bảo trả về JSON
    
        $data = $this->orderModel->getCompletedOrdersByDate();
    
        if (!$data) {
            echo json_encode(["error" => "Không có dữ liệu"]);
        } else {
            echo json_encode($data);
        }
        exit;
    }
    public function completedOrdersDetailByDate()
{
    header('Content-Type: application/json; charset=UTF-8');

    $date = $_GET['date'] ?? null;
    if (!$date) {
        echo json_encode(["error" => "Ngày không hợp lệ"]);
        exit;
    }

    // Gọi model để lấy đơn hàng theo ngày
    $orders = $this->orderModel->getCompletedOrdersDetailByDate($date);

    echo json_encode(["orders" => $orders]);
    exit;
}
public function completedOrdersDetailByMonth()
{
    header('Content-Type: application/json; charset=UTF-8');

    $month = $_GET['month'] ?? null;
    if (!$month) {
        echo json_encode(["error" => "Tháng không hợp lệ"]);
        exit;
    }

    // Gọi model để lấy đơn hàng theo ngày
    $orders = $this->orderModel->getCompletedOrdersDetailByMonth($month);

    echo json_encode(["orders" => $orders]);
    exit;
}
public function completedOrdersDetailByYear()
{
    header('Content-Type: application/json; charset=UTF-8');

    $year = $_GET['year'] ?? null;
    if (!$year) {
        echo json_encode(["error" => "năm không hợp lệ"]);
        exit;
    }

    // Gọi model để lấy đơn hàng theo ngày
    $orders = $this->orderModel->getCompletedOrdersDetailByYear($year);

    echo json_encode(["orders" => $orders]);
    exit;
}

    
    
    // Hiển thị biểu đồ theo ngày
    public function showDateChartPage() {
        renderViewAdmin("view/admin/reports/completed_orders_date_chart.php", [], "Completed Orders By Date");
    }
    // API lấy dữ liệu đơn hàng hoàn thành theo tháng
    public function completedOrdersByMonthChart()
    {
        header('Content-Type: application/json');
        $data = $this->orderModel->getCompletedOrdersByMonth();
        echo json_encode($data);
        exit;
    }

    // API lấy dữ liệu đơn hàng hoàn thành theo năm
    public function completedOrdersByYearChart()
    {
        header('Content-Type: application/json');
        $data = $this->orderModel->getCompletedOrdersByYear();
        echo json_encode($data);
        exit;
    }

    // Hiển thị trang biểu đồ theo tháng
    public function showMonthChartPage()
    {
        renderViewAdmin("view/admin/reports/completed_orders_month_chart.php", [], "Completed Orders By Month");
    }

    // Hiển thị trang biểu đồ theo năm
    public function showYearChartPage()
    {
        renderViewAdmin("view/admin/reports/completed_orders_year_chart.php", [], "Completed Orders By Year");
    }
}
