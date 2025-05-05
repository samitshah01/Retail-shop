<?php
    $active = "Details";

    if (isset($_GET['details']) && isset($_SESSION['customer_email'])) {
        $cname    = $_SESSION['customer_name'];
        $cemail   = $_SESSION['customer_email'];
        $ccontact = $_SESSION['customer_contact'];
        $caddress = $_SESSION['customer_address'];

        echo  "
        <div class='col-md-6 col-12' style='margin:0px auto'>
            <div class='bg-light text-dark' style='border:solid #e5e5e5 0.2px; padding: 10px 40px'> 
                <div class='ci-text'>
                    <span style='font-size:large;font-weight:600'>Email</span>
                    <p style='text-align:center'>$cemail</p>
                </div>
                <div class='ci-text'>
                    <span style='font-size:large;font-weight:600'>Contact</span>
                    <p style='text-align:center'>$ccontact</p>
                </div>
                <div class='ci-text'>
                    <span style='font-size:large;font-weight:600'>Address</span>
                    <p style='text-align:center'>$caddress</p>
                </div>        
            </div>
        </div>";
    }
?>