<?php
    $host = "127.0.0.1";
    $user = "root";
    $password = "";
    $database = "retail_shop";

    $con = new mysqli($host, $user, $password, $database);

    if ($con -> connect_errno) {
        die("Failed to connect to MySQL: " .$con -> connect_error);
    }

?>