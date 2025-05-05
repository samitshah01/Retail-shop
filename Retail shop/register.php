<?php
    $active = "Register";
    include("db.php");
    include("functions.php");
    include('header.php');
?>

<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="Index.php"><i class="fa fa-home"></i> Home</a>
                    <span>Register</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Register Section Begin -->
<div class="register-login-section spad">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="register-form">
                    <h2>Register</h2>
                    <form action="register.php" method="post" enctype="multipart/form-data" id="logform">
                        <div class="row">
                            <div class="group-input col-md-6">
                                <label for="username">Name</label>
                                <input type="text" id="username" name="name" required>
                                <div id="nameerr" style="margin:20px 0"></div>
                            </div>
                            <div class="group-input col-md-6">
                                <label for="con">Contact *</label>
                                <input type="text" id="con" name="contact" required>
                                <div id="conerr" style="margin:20px 0"></div>
                            </div>
                        </div>
                        <div class="group-input">
                            <label for="email">Email *</label>
                            <input type="text" id="eemail" name="cemail" required>
                            <div id="eerr" style="margin:20px 0"></div>
                        </div>
                        <div class="group-input">
                            <label for="pass">Password *</label>
                            <input type="password" id="pass" name="password" required>
                            <small>Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.</small>
                        </div>
                        <div class="group-input">
                            <label for="con-pass">Address *</label>
                            <input type="text" id="con-pass" name="address" required>
                        </div>
                        <div class="group-input">
                            <label for="con-pass">Profile Image *</label>
                            <input type="file" name="pimage" style="border: none; margin-top:6px;" required>
                        </div>
                        <button type="submit" class="site-btn register-btn" name="register">REGISTER</button>
                    </form>
                    <div class="switch-login">
                        <a href="login.php" class="or-login">Or Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Register Form Section End -->

<?php include('footer.php'); ?>

<script>
    $("#logform").submit(function(event) {
        var name = $('#username').val();
        var email = $('#eemail').val();
        var con = $('#con').val();
        var password = $('#pass').val();

        var letters = /^[A-Za-z]+$/;
        var em = /\S+@\S+\.\S+/;
        var numbers = /^[0-9]{11}$/;
        var strongPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

        if (!name.match(letters)) {
            $("#nameerr").html(
                "<span class='alert alert-danger'>Enter Valid Name (Letters only)</span>");
            event.preventDefault();
        }

        if (!con.match(numbers)) {
            $("#conerr").html(
                "<span class='alert alert-danger'>Enter Valid Contact (11 Digit)</span>");
            event.preventDefault();
        }

        if (!email.match(em)) {
            $("#eerr").html(
                "<span class='alert alert-danger'>Enter Valid Email</span>");
            event.preventDefault();
        }

        if (!password.match(strongPass)) {
            alert("Password must be at least 8 characters and include an uppercase letter, a lowercase letter, a number, and a special character.");
            event.preventDefault();
        }
    });
</script>

</body>
</html>

<?php
if (isset($_POST['register'])) {
    require_once("db.php");

    $c_name    = trim($_POST['name']);
    $c_email   = trim($_POST['cemail']);
    $c_address = trim($_POST['address']);
    $c_contact = trim($_POST['contact']);
    $c_pass_raw = $_POST['password'];
    $c_ip = getRealIpUser();

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $c_pass_raw)) {
        echo "<script>alert('Password must be at least 8 characters and include uppercase, lowercase, number, and special character.')</script>";
        exit();
    }

    $c_pass = password_hash($c_pass_raw, PASSWORD_DEFAULT);

    $tardir = "img/customer/";
    $fileName = basename($_FILES['pimage']['name']);
    $targetPath = $tardir . $fileName;
    $fileType = pathinfo($targetPath, PATHINFO_EXTENSION);
    $allow = array('jpg', 'jpeg', 'png');

    if (!in_array(strtolower($fileType), $allow)) {
        echo "<script>alert('Only JPG, JPEG, and PNG files are allowed.')</script>";
        exit();
    }

    if (!move_uploaded_file($_FILES['pimage']['tmp_name'], $targetPath)) {
        echo "<script>alert('Failed to upload image. Check folder permissions.')</script>";
        exit();
    }

    $stmt = $con->prepare("INSERT INTO customer (customer_name, customer_email, customer_pass, customer_address, customer_contact, customer_image, customer_ip)
                           VALUES (?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        error_log("Prepare failed: " . $con->error);
        echo "<script>alert('Database error: prepare failed.')</script>";
        exit();
    }

    $stmt->bind_param("sssssss", $c_name, $c_email, $c_pass, $c_address, $c_contact, $fileName, $c_ip);

    if ($stmt->execute()) {
        $_SESSION['customer_email'] = $c_email;
        $_SESSION['customer_name'] = $c_name;
        $_SESSION['customer_contact'] = $c_contact;
        $_SESSION['customer_address'] = $c_address;
        $_SESSION['customer_image'] = $fileName;

        $stmt_cart = $con->prepare("SELECT * FROM cart WHERE c_id = ?");
        $stmt_cart->bind_param("s", $c_email);
        $stmt_cart->execute();
        $result_cart = $stmt_cart->get_result();
        $check_cart = $result_cart->num_rows;

        echo "<script>alert('Account registered successfully. You are now logged in.');</script>";

        if ($check_cart > 0) {
            echo "<script>window.open('check-out.php','_self')</script>";
        } else {
            echo "<script>window.open('index.php','_self')</script>";
        }
    } else {
        error_log("Execute failed: " . $stmt->error);
        echo "<script>alert('Failed to register user. Please try again later.')</script>";
    }

    $stmt->close();
}
?>