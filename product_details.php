<?php
session_start();
include 'connection.php';
// Fetch product details
$product_id = $_GET['id'];
$query = "
    SELECT p.*, b.name as brand_name, 
           st.name as storage_name, 
           r.name as ram_name, 
           pr.name as processor_name, 
           d.name as display_name,
           g.name as graphic_name
    FROM products p 
    LEFT JOIN brands b ON p.brand_id = b.id 
    LEFT JOIN storages st ON p.storage_id = st.id
    LEFT JOIN rams r ON p.ram_id = r.id
    LEFT JOIN processors pr ON p.processor_id = pr.id
    LEFT JOIN displays d ON p.display_id = d.id
    LEFT JOIN graphics g ON p.graphic_id = g.id
    WHERE p.id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit();
}
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Product not found.";
    exit();
}

$product = $result->fetch_assoc();
$images = explode(',', $product['image']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" type="text/css" href="product_details.css">
</head>
<body>
<?php include 'header.php';?>
    <div class="container-i">
        <div class="image-gallery">
            <div class="thumbnails">
                <?php foreach ($images as $index => $image): ?>
                    <img src="img/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onclick="showImage(<?php echo $index; ?>)">
                <?php endforeach; ?>
            </div>
            <div class="main-image">
                <img id="mainImage" src="img/<?php echo htmlspecialchars($images[0]); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
        </div>
        <div class="product-details">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <?php if ($product['quantity'] > 0): ?>
            <p class="price">
                <span class="discounted-price">Price: Rs. <?php echo htmlspecialchars($product['price']); ?></span>
            </p>
            <?php endif; ?>
            <p class="stock-status" style="color: <?php echo $product['quantity'] > 0 ? 'green' : 'red'; ?>;"><?php echo $product['quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?></p>

            <?php if ($product['quantity'] > 0): ?>
            <div class="quantity-selector">
                <label for="quantity">Qty:</label>
                <button class="quantity-btn" onclick="changeQuantity(-1)">-</button>
                <span id="quantity">1</span>
                <button class="quantity-btn" onclick="changeQuantity(1)">+</button>
                <span><?php echo htmlspecialchars($product['quantity']); ?> items left</span>
            </div>
            
            <p class="vat-info">**Price is inclusive of VAT**</p>
            
            <button class="btn add-to-cart" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
            <button class="btn buy-now" onclick="showOrderForm()">Order Now</button>
            <?php else: ?>
                <p class="unavailable-info" style="color: red;">This product is currently unavailable.</p>
            <?php endif; ?>
            <p class="brand-info">Brand: <?php echo htmlspecialchars($product['brand_name']); ?></p>
        </div>
    </div>
    <div class="container-ii">
        <div class="details-only">
            <h1>Specification</h1><br>
            <div class="specification-box">
                <table class="specification-table">
                    <tr>
                        <th>Brand</th>
                        <td><?php echo htmlspecialchars($product['brand_name']); ?></td>
                    </tr>
                    <tr>
                        <th>RAM</th>
                        <td><?php echo htmlspecialchars($product['ram_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Storage</th>
                        <td><?php echo htmlspecialchars($product['storage_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Processor</th>
                        <td><?php echo htmlspecialchars($product['processor_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Display</th>
                        <td><?php echo htmlspecialchars($product['display_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Graphics</th>
                        <td><?php echo htmlspecialchars($product['graphic_name']); ?></td>
                    </tr>
                </table>
            </div>
            <div class="specification-box">
                <h2>Details</h2>
                <?php 
                $details = nl2br(htmlspecialchars($product['details']));
                $detailsArray = explode("\n", $details);
                foreach ($detailsArray as $detail) {
                    echo "<p>{$detail}</p>";
                }
                ?>
            </div>
        </div>
    </div>
    
    <div id="orderFormModal" style="display:none;">
        <div class="modal-content">
            <form id="orderForm" onsubmit="submitOrder(event)">
                <h2>Order Form</h2>
                <label for="product_name">Product Name:</label>
                <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>" readonly>
                <label for="total_price">Price:</label>
                <input type="text" name="total_price" id="ordersPrice" value="<?php echo htmlspecialchars($product['price']); ?>" readonly>
                <label for="quantity">Quantity:</label>
                <input type="text" name="quantity" id="ordersQuantity" value="1" readonly>
                <label for="name">Name:</label>
                <input type="text" name="name" required>
                <label for="number">Phone Number:</label>
                <input type="text" name="number" required>
                <label for="email">Email:</label>
                <input type="email" name="email">
                <label for="address">Address:</label>
                <input type="text" name="address" required>
                <label for="method">Payment Method:</label>
                <select name="method" required>
                    <option value="credit_card">Credit Card</option>
                </select>
                <button type="submit" class="submit-btn">Place Order</button>
                <button type="button" class="cancel-btn" onclick="closeOrderForm()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
    function showImage(index) {
        const images = <?php echo json_encode($images); ?>;
        document.getElementById('mainImage').src = 'img/' + images[index];
    }

    let quantity = 1;
    const pricePerUnit = <?php echo $product['price']; ?>;
    const maxQuantity = <?php echo $product['quantity']; ?>;
    
    function changeQuantity(amount) {
        quantity = Math.min(maxQuantity, Math.max(1, quantity + amount));
        document.getElementById('quantity').innerText = quantity;
        document.getElementById('ordersQuantity').value = quantity; // Update the order form quantity
        document.getElementById('ordersPrice').value = quantity * pricePerUnit; // Update the order form price
    }

    function addToCart(productId) {
        let accept = confirm('Are you sure?');
        if (accept) {
            const quantity = +document.getElementById('quantity').innerText;
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity: quantity }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Product added to cart!');
                } else {
                    alert('Failed to add product to cart: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the product to the cart.');
            });
        }
    }

    function showOrderForm() {
        document.getElementById('orderFormModal').style.display = 'block';
        document.getElementById('ordersQuantity').value = quantity; // Set the initial quantity in the order form
        document.getElementById('ordersPrice').value = quantity * pricePerUnit; // Set the initial price in the order form
    }

    function closeOrderForm() {
        document.getElementById('orderFormModal').style.display = 'none';
    }

    function submitOrder(event) {
        event.preventDefault();

        const formData = new FormData(document.getElementById('orderForm'));
        formData.append('product_id', <?php echo $product['id']; ?>);
        formData.append('quantity', quantity); // Append the quantity to the form data

        fetch('place_order.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Order Placed Successfully');
                location.reload(); // Refresh the page after placing the order
            } else {
                alert('Failed to place order: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('You must login first');
        });
    }
    </script>
    <?php include 'footer.php';?>
</body>
</html>
