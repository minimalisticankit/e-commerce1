<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connection.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="product_details.css">
</head>
<body id="top">

    <!-- Header -->
    <header class="header">
        <div class="alert">
            <div class="container">
                <p class="alert-text">Exclusive Products</p>
            </div>
        </div>

        <div class="header-top" data-header>
            <div class="container">

                <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
                    <span class="line line-1"></span>
                    <span class="line line-2"></span>
                    <span class="line line-3"></span>
                </button>

                <a href="index.php" class="logo">
                    <h1>TecHub</h1>
                </a>

                <div class="header-actions">
                    <a href="<?php echo isset($_SESSION['user_id']) ? 'user.php' : 'login.php'; ?>" class="header-action-btn" aria-label="user">
                        <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
                    </a>
                    <a href="cart.php" class="header-action-btn" aria-label="cart item">
                        <ion-icon name="bag-handle-outline" aria-hidden="true"></ion-icon>
                    </a>
                </div>

                <nav class="navbar">
                    <ul class="navbar-list">
                        <li><a href="index.php" class="navbar-link has-after">Home</a></li>
                        <li><a href="collection.php" class="navbar-link has-after">Collection</a></li>
                        
                        <li><a href="#About Us" class="navbar-link has-after">About Us</a></li>
                    </ul>
                </nav>

            </div>
        </div>

    </header>

    <!-- Ionicons link -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>
