<?php
    require_once '../db.php';

    $requestData = json_decode(file_get_contents("php://input"), true);
    $status = $requestData['status'] ?? 'All';
    $timeRange = $requestData['timeRange'] ?? '30';

    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime("-{$timeRange} days"));

    $query = "SELECT * FROM orders WHERE date BETWEEN '$startDate' AND '$endDate'";
    if ($status !== 'All') {
        $query .= " AND status = '$status'";
    }

    $result = mysqli_query($con, $query);
    if (!$result) {
        die(json_encode(['error' => mysqli_error($con)]));
    }

    $ordersData = [
        'labels' => [],
        'orders' => []
    ];

    $revenueData = [
        'labels' => [],
        'revenue' => []
    ];

    while ($row = mysqli_fetch_assoc($result)) {
        $ordersData['labels'][] = $row['date'];
        $ordersData['orders'][] = $row['order_qty'];

        $revenueData['labels'][] = $row['date'];
        $revenueData['revenue'][] = $row['order_qty'] * $row['order_price'];
    }

    echo json_encode([
        'ordersData' => $ordersData,
        'revenueData' => $revenueData
    ]);
?>