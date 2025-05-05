<?php
$active = "Login";
include("db.php");
include("functions.php");
include("header.php");
?>


<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="index.php"><i class="fa fa-home"></i> Home</a>
                    <span>Login</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Form Section Begin -->

<!-- Register Section Begin -->
<div class="register-login-section spad">
    <div class="container"> 
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="login-form">
                    <h2>Login</h2>
                    <form action="login.php" method="post">
                        <div class="group-input">
                            <label for="username">Email *</label>
                            <input type="text" id="username" name="cemail" required>
                            <div id="email_error"></div>
                        </div>
                        <div class="group-input">
                            <label for="pass">Password *</label>
                            <input type="password" id="pass" name="password" required>
                            <div id="password_error"></div>
                        </div>

                        <button name="login" class="site-btn login-btn">Sign In</button>
                    </form>
                    <div class="switch-login">
                        <a href="register.php" class="or-login">Or Create An Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Register Form Section End -->


<?php
include('footer.php');
?>

</body>

</html>

<?php
    if (isset($_POST['login'])) {
        require_once("db.php");
        session_start();

        $log_email = trim($_POST['cemail']);
        $log_pass = $_POST['password'];
        $c_id = $log_email;

        $get_ip = getRealIpUser();

        // Prepare statement to fetch user info
        $stmt = $con->prepare("SELECT * FROM customer WHERE customer_email = ?");
        $stmt->bind_param("s", $log_email);
        $stmt->execute();
        $result = $stmt->get_result();

        // If user not found
        if ($result->num_rows === 0) {
            echo "<script>
                    bootbox.alert({
                        message: 'Invalid Username or Password',
                        backdrop: true
                    });
                </script>";
            exit();
        }

        $user = $result->fetch_assoc();

        // Verify password
        if (!password_verify($log_pass, $user['customer_pass'])) {
            echo "<script>
                    bootbox.alert({
                        message: 'Invalid Username or Password',
                        backdrop: true
                    });
                </script>";
            exit();
        }

        // ✅ Store user data in session
        $_SESSION['customer_email']  = $user['customer_email'];
        $_SESSION['customer_name']   = $user['customer_name'];
        $_SESSION['customer_id']     = $user['customer_id'];
        $_SESSION['customer_ip']     = $user['customer_ip'];
        $_SESSION['customer_image']  = $user['customer_image'];
        $_SESSION['customer_address']  = $user['customer_address'];
        $_SESSION['customer_contact']= $user['customer_contact'];

        // Check for cart items
        $stmt_cart = $con->prepare("SELECT * FROM cart WHERE c_id = ?");
        $stmt_cart->bind_param("s", $c_id);
        $stmt_cart->execute();
        $result_cart = $stmt_cart->get_result();
        $check_cart = $result_cart->num_rows;

        // Redirect
        if ($check_cart == 0) {
            echo "<script>window.open('index.php?stat=1','_self')</script>";
        } else {
            echo "<script>window.open('check-out.php','_self')</script>";
        }
    }
?>