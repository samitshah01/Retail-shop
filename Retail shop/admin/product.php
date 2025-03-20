<?php require_once 'include/header.php'?>
<?php 
   require_once '../db.php';
   if(isset($_GET['delete_id'])){
       $query1 = "DELETE FROM products WHERE products_id=".$_GET['delete_id'];

       $result1 = mysqli_query($con, $query1);
       if($result1){
           $_SESSION['success'] = "The data has been deleted successfully";
       }
   }

   $query = "SELECT * FROM `products`";
   $result = mysqli_query($con, $query);
?>
<div id="layoutSidenav_content">
   <main>
      <div class="container-fluid px-4">
      <h1 class="mt-4">Product Table</h1>
        <?php if(isset($_SESSION['success'])):?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']);?>
            </div>
        <?php endif?>
        <div class="card mb-4">
            <div class="card-header">
                <div style="float: right;">
                    <a href="add_product.php" class="btn btn-secondary">Add Products</a>
                </div>
               <i class="fas fa-table me-1"></i>
               All Products
            </div>
            
            <div class="card-body">
                <table id="datatablesSimple">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Product Title</th>
                        <th>Product Price</th>
                        <th>Product Description</th>
                        <th>Product Image</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tfoot>
                     <tr>
                     <th>#</th>
                        <th>Product Title</th>
                        <th>Product Price</th>
                        <th>Product Description</th>
                        <th>Product Image</th>
                        <th>Action</th>
                     </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach($result as $i => $data):?>
                     <tr>
                        <td><?php echo ++$i;?></td>
                        <td><?php echo $data['product_title']?></td>
                        <td><?php echo $data['product_price']?>/per day</td>
                        <td><?php echo $data['product_desc']?></td>
                        <td><img src="../img/products/<?php echo $data['product_img1']?>" alt="" height="50"></td>
                        <td>
                            <a href="edit.php?id=<?php echo $data['products_id']; ?>)" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="javascript:delete_id(<?php echo $data['products_id']; ?>)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                        </td>
                     </tr>
                     <?php endforeach?>
                  </tbody>
                </table>
            </div>
         </div>
        </div>
   </main>
<script>
   function delete_id(id) {
    if (confirm('Are you sure you want to delete this?')) {
        window.location.href = 'product.php?delete_id=' + id;
    }
}
</script>


<?php require_once 'include/footer.php'?>
