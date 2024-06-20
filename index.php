<?php
session_start();
include 'connection.php';

// Number of products per page
$products_per_page = 5;

// Get the current page or set a default
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $current_page = (int)$_GET['page'];
} else {
    $current_page = 1;
}

// Calculate the offset for the query
$offset = ($current_page - 1) * $products_per_page;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recently Added Products</title>
    <link rel="stylesheet" href="index.css">
    <script src="scripts.js" defer></script>
</head>
<body>
<?php include 'header.php';?>
<!-- slide -->
<div class="main-i">   
    <div class="content-i w3-display-container">
        <img class="mySlides" src="img/slide1.webp">
        <img class="mySlides" src="img/slide2.jpeg">
        <img class="mySlides" src="img/slide2.webp">
        <div class="center-i w3-display-bottommiddle" style="width:100%">
            <div class="left-i" onclick="plusDivs(-1)">&#10094;</div>
            <div class="right-i" onclick="plusDivs(1)">&#10095;</div>
        </div>
    </div>
</div>

<script>
    var slideIndex = 1;
    showDivs(slideIndex);

    function plusDivs(n) {
        showDivs(slideIndex += n);
    }

    function currentDiv(n) {
        showDivs(slideIndex = n);
    }

    function showDivs(n) {
        var i;
        var x = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("demo");
        if (n > x.length) {slideIndex = 1}
        if (n < 1) {slideIndex = x.length}
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";  
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" w3-white", "");
        }
        x[slideIndex-1].style.display = "block";  
        dots[slideIndex-1].className += " w3-white";
    }
</script>
<!-- slide end -->

<div class="product-grid">
<div class="headerr">
    <h1>FEATURED PRODUCTS</h1>
    <a href="collection.php" class="shop-all-link">Shop All Products →</a>
</div>
<?php
// Query to fetch recently added products
$query = "SELECT * FROM products ORDER BY id DESC LIMIT $offset, $products_per_page";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) {
        $images = explode(',', $product['image']);
        $firstImage = $images[0];
        $productName = htmlspecialchars($product['name']);
        if (strlen($productName) > 35) {
            $productName = substr($productName, 0, 35) . '...';
        }
        $quantityText = $product['quantity'] > 0 ? "Stock Available: " . htmlspecialchars($product['quantity']) : "Stock Unavailable";
        $priceText = $product['quantity'] > 0 ? "Rs. " . htmlspecialchars($product['price']) : "Coming Soon";
        echo "<a href='product_details.php?id=" . htmlspecialchars($product['id']) . "' class='product-link'>
                <div class='product-item'>
                    <div class='image-container'>
                        <img src='img/" . htmlspecialchars($firstImage) . "' alt='" . htmlspecialchars($product['name']) . "'>
                    </div>
                    <div class='product-info'>
                        <h2 class='product-name'>" . $productName . "</h2>
                        <div class='product-quantity'>" . $quantityText . "</div>
                        <h3 class='product-price'>" . $priceText . "</h3>
                    </div>
                </div>
              </a>";
    }
} else {
    echo "<p>No products found.</p>";
}
?>
</div>

<!-- Under 1-lakh Section -->
<div class="product-grid">
<div class="headerr">
    <h1>Products Under 1 Lakh</h1>
    <a href="collection.php?price_filter=under100000" class="shop-all-link">Shop All Under 20,000 →</a>
</div>
<?php
// Query to fetch products under Rs. 20,000
$query_under_100000 = "SELECT * FROM products WHERE price < 100000 ORDER BY id DESC LIMIT $offset, $products_per_page";
$result_under_100000 = $conn->query($query_under_100000);

if ($result_under_100000->num_rows > 0) {
    while ($product = $result_under_100000->fetch_assoc()) {
        $images = explode(',', $product['image']);
        $firstImage = $images[0];
        $productName = htmlspecialchars($product['name']);
        if (strlen($productName) > 35) {
            $productName = substr($productName, 0, 35) . '...';
        }
        $quantityText = $product['quantity'] > 0 ? "Stock Available: " . htmlspecialchars($product['quantity']) : "Stock Unavailable";
        $priceText = $product['quantity'] > 0 ? "Rs. " . htmlspecialchars($product['price']) : "Coming Soon";
        echo "<a href='product_details.php?id=" . htmlspecialchars($product['id']) . "' class='product-link'>
                <div class='product-item'>
                    <div class='image-container'>
                        <img src='img/" . htmlspecialchars($firstImage) . "' alt='" . htmlspecialchars($product['name']) . "'>
                    </div>
                    <div class='product-info'>
                        <h2 class='product-name'>" . $productName . "</h2>
                        <div class='product-quantity'>" . $quantityText . "</div>
                        <h3 class='product-price'>" . $priceText . "</h3>
                    </div>
                </div>
              </a>";
    }
} else {
    echo "<p>No products found under 100k</p>";
}
?>
</div> 
</div>
</body>
</html>
