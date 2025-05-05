<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    header('Content-Type: application/json');
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $con->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $orderId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    exit;
}
?>
<?php require_once 'include/header.php'; ?>
<?php
    if (isset($_GET['delete_id'])) {
        $query1 = "DELETE FROM orders WHERE order_id = " . $_GET['delete_id'];
        $result1 = mysqli_query($con, $query1);
        if ($result1) {
            $_SESSION['success'] = "The order has been deleted successfully";
            header("Location: orders.php");
            exit;
        }
    }

    $query = "SELECT orders.order_id, orders.order_price, orders.order_qty, orders.date, orders.status,
                customer.customer_email, products.product_title, products.product_img1
              FROM orders
              INNER JOIN customer ON orders.c_id = customer.customer_id
              INNER JOIN products ON orders.product_id = products.products_id
              ORDER BY orders.date DESC";
    $result = mysqli_query($con, $query);
?>

<!-- Status Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Order Status</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <label for="status">Product Status</label>
        <select class="form-control" name="product_status" id="modalStatus">
          <option value="Active">Active</option>
          <option value="Processing">Processing</option>
          <option value="Sold">Sold</option>
          <option value="Out of Stock">Out of Stock</option>
          <option value="Coming Soon">Coming Soon</option>
          <option value="Discontinued">Discontinued</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveStatus">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">All Orders</h1>

      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
          <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
      <?php endif ?>

      <div class="card mb-4">
        <div class="card-header"><i class="fas fa-table me-1"></i> Orders Overview</div>
        <div class="card-body">
          <table id="datatablesSimple">
            <thead>
              <tr>
                <th>#</th>
                <th>Email</th>
                <th>Product</th>
                <th>Image</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($result as $i => $order): ?>
                <tr>
                  <td><?php echo ++$i; ?></td>
                  <td><?php echo $order['customer_email']; ?></td>
                  <td><?php echo $order['product_title']; ?></td>
                  <td><img src="../img/products/<?php echo $order['product_img1']; ?>" width="50" height="50"></td>
                  <td><?php echo $order['order_price']; ?> USD</td>
                  <td><?php echo $order['order_qty']; ?></td>
                  <td><?php echo $order['order_price'] * $order['order_qty']; ?> USD</td>
                  <td><?php echo $order['date']; ?></td>
                  <td>
                    <span class="badge fs-6 
                            <?php 
                            switch ($order['status']) {
                                case 'Active': echo 'bg-success'; break;
                                case 'Processing': echo 'bg-warning text-dark'; break;
                                case 'Sold': echo 'bg-primary'; break;
                                case 'Out of Stock': echo 'bg-danger'; break;
                                case 'Coming Soon': echo 'bg-info text-dark'; break;
                                case 'Discontinued': echo 'bg-secondary'; break;
                                default: echo 'bg-light text-dark'; break;
                            }
                            ?>">
                            <?php echo $order['status']; ?>
                    </span>
                   </td>
                  <td>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalCenter"
                      onclick="openEditModal(<?php echo $order['order_id']; ?>, '<?php echo $order['status']; ?>')">
                      <i class="fas fa-edit"></i>
                    </button>
                    <a href="javascript:delete_id(<?php echo $order['order_id']; ?>)" class="btn btn-danger btn-sm">
                      <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  let currentOrderId = null;

  function openEditModal(orderId, currentStatus) {
    currentOrderId = orderId;
    document.getElementById('modalStatus').value = currentStatus;
  }

  document.getElementById('saveStatus').addEventListener('click', function () {
    const newStatus = document.getElementById('modalStatus').value;

    if (currentOrderId && newStatus) {
      fetch('orders.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'update_status=1&order_id=' + currentOrderId + '&status=' + encodeURIComponent(newStatus)
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert("Error updating status: " + (data.error || "Unknown error"));
        }
      })
      .catch(err => {
        alert("Network or server error");
        console.error(err);
      });
    }
  });

  function delete_id(id) {
    if (confirm('Are you sure you want to delete this order?')) {
      window.location.href = 'orders.php?delete_id=' + id;
    }
  }
</script>
<?php require_once 'include/footer.php' ?>