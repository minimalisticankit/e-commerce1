
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/68f1c79717.js"></script>
    <link rel="stylesheet" href="admin_header.css">
    <title>Admin Panel</title>
</head>
<body>
    <header class="header">
        <div class="flex">
            <a href="admin_pannel.php" class="logo"><h1>TecHub</h1></a>
            <nav class="navbar">
                <a href="admin_pannel.php">home</a>
                <a href="admin_product.php">product</a>
                <a href="admin_order.php">order</a>
                <a href="admin_user_management.php">users</a>
             
         
            </nav>
            <form method="POST" class="logout-form">
                <button type="submit" name="logout" class="logout-btn">log out</button>
            </form>
        </div>
    </header>
</body>
</html>
