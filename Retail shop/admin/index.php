<?php require_once 'include/header.php' ?>

<?php
if (!isset($_SESSION['admin_id'])) {
   echo "<script>window.location.href = 'login.php'</script>";
}

require_once '../db.php';

// Total Orders
$query_total_orders = "SELECT COUNT(*) AS total_orders FROM orders";
$result_total_orders = mysqli_query($con, $query_total_orders);
$total_orders = mysqli_fetch_assoc($result_total_orders)['total_orders'] ?? 0;

// Total Revenue
$query_total_revenue = "SELECT SUM(order_price * order_qty) AS total_revenue FROM orders WHERE status = 'Sold'";
$result_total_revenue = mysqli_query($con, $query_total_revenue);
$total_revenue = mysqli_fetch_assoc($result_total_revenue)['total_revenue'] ?? 0;

// Total Products Sold
$query_total_products = "SELECT COUNT(*) AS total_products_sold FROM orders WHERE status = 'Sold'";
$result_total_products = mysqli_query($con, $query_total_products);
$total_products_sold = mysqli_fetch_assoc($result_total_products)['total_products_sold'] ?? 0;

// Total Products Sold by Item (Fixed with JOIN to products table)
$query_total_items_sold = "
    SELECT p.product_title, SUM(o.order_qty) AS total_qty 
    FROM orders o
    JOIN cart c ON o.c_id = c.c_id
    JOIN products p ON c.products_id = p.products_id
    GROUP BY p.product_title
";

$result_total_items_sold = mysqli_query($con, $query_total_items_sold);

$products = [];
$items_sold = [];

while ($row = mysqli_fetch_assoc($result_total_items_sold)) {
    $products[] = $row['product_name'];
    $items_sold[] = $row['total_qty'];
}

$query_total_items_by_category = "
    SELECT c.cat_title, SUM(o.order_qty) AS total_qty
    FROM orders o
    JOIN cart ca ON o.c_id = ca.c_id
    JOIN products p ON ca.products_id = p.products_id
    JOIN category c ON p.cat_id = c.cat_id
    GROUP BY c.cat_title
";

// bar graph of product name based on the category
$result = mysqli_query($con, $query_total_items_by_category);

$categories = [];
$quantities = [];

// Fetch the results and store them in arrays
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row['cat_title'];
    $quantities[] = (int)$row['total_qty'];
}

// Pending Orders
$query_pending_orders = "SELECT COUNT(*) AS pending_orders FROM orders WHERE status = 'Processing'";
$result_pending_orders = mysqli_query($con, $query_pending_orders);
$pending_orders = mysqli_fetch_assoc($result_pending_orders)['pending_orders'] ?? 0;

// Orders by Status for Chart
$query_orders_by_status = "SELECT status, COUNT(*) AS count FROM orders GROUP BY status";
$result_orders_by_status = mysqli_query($con, $query_orders_by_status);
$statuses = [];
$counts = [];

$revenueData = [];
$revenueLabels = [];

// Orders by Status for Line Graph
$revenue_query = "SELECT DATE(date) AS order_date, SUM(order_price * order_qty) AS daily_revenue 
                  FROM orders 
                  WHERE status = 'Sold' 
                  GROUP BY DATE(date) 
                  ORDER BY DATE(date) ASC";
$revenue_result = mysqli_query($con, $revenue_query);

if ($revenue_result) {
   while ($row = mysqli_fetch_assoc($revenue_result)) {
      $revenueLabels[] = $row['order_date'];
      $revenueData[] = $row['daily_revenue'];
   }
} else {
   $revenueLabels = ['No Data'];
   $revenueData = [0];
}

while ($row = mysqli_fetch_assoc($result_orders_by_status)) {
   $statuses[] = $row['status'];
   $counts[] = $row['count'];
}
?>

<div id="layoutSidenav_content">
   <main>
      <div class="container-fluid px-4">
         <h1 class="mt-4">Dashboard</h1>
         <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
         </ol>
         <div class="row">
            <!-- KPI Cards -->
            <div class="col-xl-3 col-md-6">
               <div class="card bg-primary text-white mb-4">
                  <div class="card-body">
                     <h5>Total Orders</h5>
                     <p><?= $total_orders ?></p>
                  </div>
                  <div class="card-footer d-flex align-items-center justify-content-between">
                     <a class="small text-white stretched-link" href="orders.php">View Details</a>
                     <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                  </div>
               </div>
            </div>

            <div class="col-xl-3 col-md-6">
               <div class="card bg-warning text-white mb-4">
                  <div class="card-body">
                     <h5>Total Revenue</h5>
                     <p>$<?= number_format($total_revenue, 2) ?></p>
                  </div>
                  <div class="card-footer d-flex align-items-center justify-content-between">
                     <a class="small text-white stretched-link" href="#">View Details</a>
                     <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                  </div>
               </div>
            </div>

            <div class="col-xl-3 col-md-6">
               <div class="card bg-success text-white mb-4">
                  <div class="card-body">
                     <h5>Total Products Sold</h5>
                     <p><?= $total_products_sold ?></p>
                  </div>
                  <div class="card-footer d-flex align-items-center justify-content-between">
                     <a class="small text-white stretched-link" href="#">View Details</a>
                     <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                  </div>
               </div>
            </div>

            <div class="col-xl-3 col-md-6">
               <div class="card bg-danger text-white mb-4">
                  <div class="card-body">
                     <h5>Pending Orders</h5>
                     <p><?= $pending_orders ?></p>
                  </div>
                  <div class="card-footer d-flex align-items-center justify-content-between">
                     <a class="small text-white stretched-link" href="#">View Details</a>
                     <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                  </div>
               </div>
            </div>
         </div>

         <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
               <div>
                  <i class="fas fa-chart-bar me-1"></i>
                  Sales Overview
               </div>

               <div class="d-flex gap-2">
                  <select id="filterStatus" class="form-select form-select-sm">
                     <option value="All">All Statuses</option>
                     <option value="Sold">Sold</option>
                     <option value="Processing">Processing</option>
                     <option value="Cancelled">Cancelled</option>
                  </select>
                  <select id="filterTime" class="form-select form-select-sm">
                     <option value="30">Last 30 Days</option>
                     <option value="90">Last 90 Days</option>
                     <option value="365">Last Year</option>
                  </select>
               </div>
            </div>

            <div class="card-body">
               <!-- Charts Row -->
               <div class="row">
                  <div class="col-lg-6">
                     <div style="max-width: 100%; height: 300px;">
                        <canvas id="ordersChart"></canvas>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div style="max-width: 100%; height: 300px;">
                        <canvas id="revenueTrendChart"></canvas>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div style="max-width: 100%; height: 300px;">
                        <canvas id="itemsSoldChart"></canvas>
                     </div>
                  </div>
               </div>
            </div>
         </div>

      </div>
   </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   window.onload = function() {
    const ordersCanvas = document.getElementById('ordersChart');
    const revenueCanvas = document.getElementById('revenueTrendChart');
    const itemsSoldCanvas = document.getElementById('itemsSoldChart'); // New canvas for items sold chart

    if (!ordersCanvas || !revenueCanvas || !itemsSoldCanvas) {
        console.error('Canvas elements are missing in the HTML.');
        return;
    }

    const ordersCtx = ordersCanvas.getContext('2d');
    const revenueCtx = revenueCanvas.getContext('2d');
    const itemsSoldCtx = itemsSoldCanvas.getContext('2d'); // New context for items sold chart

    const ordersChart = new Chart(ordersCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode($statuses) ?>,
            datasets: [{
                data: <?= json_encode($counts) ?>,
                backgroundColor: ['#dc3545', '#ffc107', '#28a745', '#007bff', '#6c757d']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($revenueLabels); ?>,
            datasets: [{
                label: 'Daily Revenue ($)',
                data: <?php echo json_encode($revenueData); ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointHoverRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Revenue Trend (Daily)'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue ($)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            }
        }
    });

    // New chart for Items Sold
    const itemsSoldChart = new Chart(itemsSoldCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($products) ?>, // Product names
            datasets: [{
                label: 'Items Sold',
                data: <?= json_encode($items_sold) ?>, // Total quantities sold
                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Bar color
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Items Sold (Quantity)'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quantity Sold'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Product'
                    }
                }
            }
        }
    });

    document.getElementById('filterStatus').addEventListener('change', updateCharts);
    document.getElementById('filterTime').addEventListener('change', updateCharts);

    function updateCharts() {
        const status = document.getElementById('filterStatus').value;
        const timeRange = document.getElementById('filterTime').value;

        console.log('Filter applied:', status, timeRange);

        fetch('chart_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status,
                    timeRange
                })
            })
            .then(response => response.json())
            .then(data => {
                updateOrdersChart(data.ordersData);
                updateRevenueTrendChart(data.revenueData);
            })
            .catch(error => {
                console.error('Error fetching chart data:', error);
            });
    }

    function updateOrdersChart(data) {
        if (window.ordersChart instanceof Chart) {
            window.ordersChart.destroy();
        }

        window.ordersChart = new Chart(ordersCtx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Orders',
                    data: data.orders,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function updateRevenueTrendChart(data) {
        if (window.revenueChart instanceof Chart) {
            window.revenueChart.destroy();
        }

        window.revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Revenue Trend',
                    data: data.revenue,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
};

</script>

<?php require_once 'include/footer.php' ?>