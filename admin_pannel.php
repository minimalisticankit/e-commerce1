<?php
session_start(); // Start the session
include 'connection.php';

$admin_id = $_SESSION['admin_name'];

if (!isset($admin_id)){
    header('location: index.php');
    exit(); // Add exit after header to stop script execution
}

if (isset($_POST['logout'])){
    session_destroy();
    header('location: index.php');
    exit(); // Add exit after header to stop script execution
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin_pannel.css">
    <title>Admin Panel</title>
</head>
<body>
    <?php include 'admin_header.php';?>
    <div class="banner">
        <div class="detail">
            <h1>admin dashboard</h1>
              
        </div>
    </div>
    <section class= "dashboard">
        <div class="box-container">
            <div class="box">
            <?php 
                $total_pendings = 0;
                $select_pendings = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'pending'")
                     or die('query failed');
                while ($fetch_pending = mysqli_fetch_assoc($select_pendings)){
                $total_pendings += $fetch_pending['total_price'];
                }
                ?>
                
                <h3>RS. <?php echo $total_pendings; ?>/-</h3>
                <p>total pendings</p>
            </div>
            <div class="box">
            <?php 
                $total_orders = 0;
                $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'pending'")
                     or die('query failed');
                $num_of_orders = mysqli_num_rows($select_orders);
                ?>
                
                <h3><?php echo $num_of_orders; ?></h3>
                <p>Pending orders</p>
            </div>


            <div class="box">
            <?php 
                $total_completes = 0;
                $select_completes = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'complete'")
                     or die('query failed');
                while ($fetch_pending = mysqli_fetch_assoc($select_completes)){
                $total_completes += $fetch_pending['total_price'];
                }
                ?>
                
                <h3>RS. <?php echo $total_completes; ?>/-</h3>
                <p>total completes</p>
            </div>
            <div class="box">
            <?php 
                $total_orders = 0;
                $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'complete'")
                     or die('query failed');
                $num_of_orders = mysqli_num_rows($select_orders);
                ?>
                
                <h3><?php echo $num_of_orders; ?></h3>
                <p>complete orders</p>
            </div>


            

            <div class="box">
            <?php 
                $total_users = 0;
                $select_users = mysqli_query($conn, "SELECT * FROM `user` WHERE user_type = 'user'")
                     or die('query failed');
                $num_of_users = mysqli_num_rows($select_users);
                ?>               
                <h3><?php echo $num_of_users; ?></h3>
                <p>total normal users</p>
            </div>

            <div class="box">
            <?php 
                $total_admins = 0;
                $select_admins = mysqli_query($conn, "SELECT * FROM `user` WHERE user_type = 'admin'")
                     or die('query failed');
                $num_of_admins = mysqli_num_rows($select_admins);
                ?>               
                <h3><?php echo $num_of_admins; ?></h3>
                <p>total admins</p>
            </div>

            <div class="box">
            <?php 
                $total_users = 0;
                $select_users = mysqli_query($conn, "SELECT * FROM `user`")
                     or die('query failed');
                $num_of_users = mysqli_num_rows($select_users);
                ?>               
                <h3><?php echo $num_of_users; ?></h3>
                <p>total users</p>
            </div>


        </div>
        
    </section>
    <script src="script.js"></script>
</body>
</html>
