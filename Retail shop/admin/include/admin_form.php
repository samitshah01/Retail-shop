<?php 
session_start();
require_once '../../db.php';

    if (isset($_POST['product_add'])) {

        $p_cat_id = $_POST['p_cat_id'];
        $cat_id = $_POST['cat_id'];
        $product_title = $_POST['product_title'];
        $product_img1 = $_FILES['product_img1']['name'];
        $product_img2 = $_FILES['product_img2']['name'];
        $product_price = $_POST['product_price'];
        $product_keywords = $_POST['product_keywords'];
        $product_desc = $_POST['product_desc'];


        $temp_name1 = $_FILES['product_img1']['tmp_name'];
        $temp_name2 = $_FILES['product_img2']['tmp_name'];

        move_uploaded_file($temp_name1, "../../img/products/$product_img1");
        move_uploaded_file($temp_name2, "../../img/products/$product_img2");

        $insert_product = "Insert into products (p_cat_id,cat_id,date,product_title,product_img1,product_img2,product_price,product_keywords,product_desc)
        values ('$p_cat_id','$cat_id',NOW(),'$product_title','$product_img1','$product_img2','$product_price','$product_keywords','$product_desc')";

        $run_insert_product = mysqli_query($con, $insert_product);

        if ($run_insert_product) {
            $_SESSION['success'] = "Product added successfully";
            header('location:../product.php');
        }
    }

    if (isset($_POST['product_add'])) {

        $p_cat_id = $_POST['p_cat_id'];
        $cat_id = $_POST['cat_id'];
        $product_title = $_POST['product_title'];
        $product_img1 = $_FILES['product_img1']['name'];
        $product_img2 = $_FILES['product_img2']['name'];
        $product_price = $_POST['product_price'];
        $product_keywords = $_POST['product_keywords'];
        $product_desc = $_POST['product_desc'];


        $temp_name1 = $_FILES['product_img1']['tmp_name'];
        $temp_name2 = $_FILES['product_img2']['tmp_name'];

        move_uploaded_file($temp_name1, "../../img/products/$product_img1");
        move_uploaded_file($temp_name2, "../../img/products/$product_img2");

        $insert_product = "Insert into products (p_cat_id,cat_id,date,product_title,product_img1,product_img2,product_price,product_keywords,product_desc)
        values ('$p_cat_id','$cat_id',NOW(),'$product_title','$product_img1','$product_img2','$product_price','$product_keywords','$product_desc')";

        $run_insert_product = mysqli_query($con, $insert_product);

        if ($run_insert_product) {
            $_SESSION['success'] = "Product added successfully";
            header('location:../product.php');
        }
    }

    

    if(isset($_POST['login'])){
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "SELECT * FROM admin WHERE admin_email = '$email' AND password = '$password'";

        $result = mysqli_query($con, $query);

        if(mysqli_num_rows($result) > 0){
            $data = mysqli_fetch_assoc($result);
            $_SESSION['admin_id'] = $data['admin_id'];
            $_SESSION['admin_email'] = $data['email'];
            header('location:../index.php');
        }
        else{
            $_SESSION['error'] = "Username or password invalid !!";
            header('location:../login.php');

        }
    }
    if(isset($_POST['find'])){
		$email = $_POST['email'];

		$query = "SELECT * FROM admin WHERE admin_email = '$email'";

		$result = mysqli_query($con, $query);

		if(mysqli_num_rows($result) > 0){
			$data = mysqli_fetch_assoc($result);
			$_SESSION['success'] = "Email found! Change your password";

			$_SESSION['admin_email'] = $data['email'];
			header('location:../update-password.php');
		}
		else{
			$_SESSION['error'] = "No email found!";
			header('location:../forgot.php');

		}
	}
    if(isset($_POST['update'])){
		$pass = $_POST['password'];
		$email = $_SESSION['admin_email'];

		$query = "UPDATE admin SET admin.password ='$pass' WHERE admin.admin_email = '$email'";

		$result = mysqli_query($con, $query);
		$_SESSION['success'] = "Password updated successfully, Login to continue";
		header('location:../login.php');
	}
    if (isset($_POST['product_update'])) {

        $p_cat_id = $_POST['p_cat_id'];
        $cat_id = $_POST['cat_id'];
        $product_title = $_POST['product_title'];
        $product_price = $_POST['product_price'];
        $product_keywords = $_POST['product_keywords'];
        $product_desc = $_POST['product_desc'];
        $id = $_POST['product_id'];

        $update = "UPDATE products SET p_cat_id = '$p_cat_id',cat_id='$cat_id',product_title='$product_title',product_price='$product_price',product_desc='$product_desc',product_keywords='$product_keywords'
                    WHERE products_id = '$id'";
        echo $update;

        $run_insert_product = mysqli_query($con, $update);

        if ($run_insert_product) {
            $_SESSION['success'] = "Product updated successfully";
            header('location:../product.php');
        }
    }

?>