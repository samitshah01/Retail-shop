<?php require_once '../db.php'; ?>
<?php require_once 'include/header.php'; ?>
<?php
    $query = "SELECT orders.order_id, orders.order_price, orders.order_qty, orders.date, orders.status,
                customer.customer_email, products.product_title, products.product_img1
            FROM orders
            INNER JOIN customer ON orders.c_id = customer.customer_id
            INNER JOIN products ON orders.product_id = products.products_id
            WHERE orders.status = 'Sold'
            ORDER BY orders.date DESC";
    $result = mysqli_query($con, $query);
?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Sold Orders</h1>

      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
          <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
      <?php endif ?>

      <div class="card mb-4">
        <div class="card-header"><i class="fas fa-table me-1"></i> Sold Orders Overview</div>
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
                  <td><span class="badge bg-primary fs-6"><?php echo $order['status']; ?></span></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</div>
<?php require_once 'include/footer.php' ?>