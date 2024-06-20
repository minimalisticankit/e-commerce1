<?php
    $conn = mysqli_connect('localhost', 'root', '', 'ecommerce_db');

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>
