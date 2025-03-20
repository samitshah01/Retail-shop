<?php require_once 'include/header.php'?>
<div id="layoutSidenav_content">
   <main>
      <div class="container-fluid px-4">
         <h1 class="mt-4">Product </h1>
         <div class="row">
            <div class="col-xl-6">
               <div class="card mb-4">
                  <div class="card-header">
                     <i class="fas fa-list me-1"></i>
                     Enter Product Details
                  </div>
                  <div class="card-body">
                    <form method="post" action="include/admin_form.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Product Title/Name</label>
                            <input type="text" class="form-control" name="product_title" placeholder="Enter product name" required>
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

                                        echo "
                                        
                                        <option value='$p_cat_id'>$p_cat_title</option>  
                                        
                                    
                                        ";
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

                                        echo "
                                        
                                        <option value='$cat_id'>$cat_title</option>  
                                        
                                    
                                        ";
                                    }

                                    ?>

                                </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Product Image # 1</label>
                            <input type="file" class="form-control" name="product_img1" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Product Image # 2</label>
                            <input type="file" class="form-control" name="product_img2" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Product Price</label>
                            <input type="text" class="form-control" name="product_price" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Product Keywords</label>
                            <input type="text" class="form-control" name="product_keywords" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Product Description</label>
                            <textarea class="form-control" name="product_desc" cols="19" rows="6"></textarea>
                        </div>
                        <input type="submit" value="Insert Product" name="product_add" class="btn btn-primary mt-2">
                    </form>

                  </div>
               </div>
            </div>
            
         </div>
      </div>
   </main>
<?php require_once 'include/footer.php'?>