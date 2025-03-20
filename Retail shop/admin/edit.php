<?php require_once 'include/header.php'?>
<?php
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $query = "SELECT * FROM `products` WHERE products_id = '$id'";
        $result = mysqli_query($con, $query);
        $data = mysqli_fetch_assoc($result);
    }
?>
<div id="layoutSidenav_content">
   <main>
      <div class="container-fluid px-4">
         <h1 class="mt-4">Product </h1>
         <div class="row">
            <div class="col-xl-6">
               <div class="card mb-4">
                  <div class="card-header">
                     <i class="fas fa-list me-1"></i>
                     Edit Product Details
                  </div>
                  <div class="card-body">
                    <form method="post" action="include/admin_form.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Product Title/Name</label>
                            <input type="text" class="form-control" name="product_title" placeholder="Enter product name" value="<?php echo $data['product_title']?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Product Category</label>
                                <select class="form-control" name="p_cat_id">

                                    <option>Select a Product Category</option>

                                    <?php

                                    $get_p_category = "select * from product_categories";
                                    $run_p_category = mysqli_query($con, $get_p_category);

                                    while ($p_cat_row = mysqli_fetch_array($run_p_category)) {

                                        $p_cat_id = $p_cat_row['p_cat_id'];
                                        $p_cat_title = $p_cat_row['p_cat_title'];
                                        if($p_cat_id == $data['p_cat_id']){
                                            echo "
                                        
                                            <option value='$p_cat_id' selected>$p_cat_title</option>  
                                            
                                        
                                            ";
                                        }
                                        else{
                                            echo "
                                        
                                            <option value='$p_cat_id'>$p_cat_title</option>  
                                            
                                        
                                            ";

                                        }

                                        
                                    }

                                    ?>

                                </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Category</label>
                                <select class="form-control" name="cat_id">

                                    <option>Select a Category</option>

                                    <?php

                                    $get_category = "select * from category";
                                    $run_category = mysqli_query($con, $get_category);

                                    while ($cat_row = mysqli_fetch_array($run_category)) {

                                        $cat_id = $cat_row['cat_id'];
                                        $cat_title = $cat_row['cat_title'];
                                        if($cat_id == $data['cat_id']){
                                            echo "
                                        
                                            <option value='$cat_id' selected>$cat_title</option>  
                                            
                                        
                                            ";
                                        }
                                        else{
                                            echo "
                                        
                                            <option value='$cat_id'>$cat_title</option>  
                                            
                                        
                                            ";
                                        }

                                        
                                    }

                                    ?>

                                </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Product Price</label>
                            <input type="text" class="form-control" name="product_price" value="<?php echo $data['product_price']?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Product Keywords</label>
                            <input type="text" class="form-control" name="product_keywords" value="<?php echo $data['product_keywords']?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Product Description</label>
                            <textarea class="form-control" name="product_desc" cols="19" rows="6"><?php echo strip_tags($data['product_desc'])?></textarea>
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="product_id" value="<?php echo $data['products_id']?>">
                        </div>
                        <input type="submit" value="Update Product" name="product_update" class="btn btn-primary mt-2">
                    </form>

                  </div>
               </div>
            </div>
            
         </div>
      </div>
   </main>
<?php require_once 'include/footer.php'?>