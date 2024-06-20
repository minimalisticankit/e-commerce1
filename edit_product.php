<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['admin_name'])) {
    header('location: index.php');
    exit();
}

$messages = '';
$edit_id = '';

// Fetch options for select elements
$brands = mysqli_query($conn, "SELECT id, name FROM `brands` ORDER BY name ASC");
$rams = mysqli_query($conn, "SELECT id, name FROM `rams` ORDER BY name ASC");
$storages = mysqli_query($conn, "SELECT id, name FROM `storages` ORDER BY name ASC");
$processors = mysqli_query($conn, "SELECT id, name FROM `processors` ORDER BY name ASC");
$graphics = mysqli_query($conn, "SELECT id, name FROM `graphics` ORDER BY name ASC");
$displays = mysqli_query($conn, "SELECT id, name FROM `displays` ORDER BY name ASC"); // Fetch display options

if (isset($_GET['edit_id'])) {
    $edit_id = mysqli_real_escape_string($conn, $_GET['edit_id']);
    $product_query = mysqli_query($conn, "SELECT * FROM products WHERE id = $edit_id");
    $product = mysqli_fetch_assoc($product_query);
    $current_images = explode(",", $product['image']);
}

if (isset($_POST['update_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $brand_id = mysqli_real_escape_string($conn, $_POST['brand']);
    $ram_id = mysqli_real_escape_string($conn, $_POST['ram']);
    $storage_id = mysqli_real_escape_string($conn, $_POST['storage']);
    $processor_id = mysqli_real_escape_string($conn, $_POST['processor']);
    $graphic_id = mysqli_real_escape_string($conn, $_POST['graphic']);
    $display_id = mysqli_real_escape_string($conn, $_POST['display']); // Capture display selection

    $image_names = $current_images; // Array of current images

    // Handle image deletion and replacement
    foreach ($current_images as $key => $old_image) {
        if (isset($_POST["delete_image_{$key}"])) {
            if (file_exists("img/{$old_image}")) {
                unlink("img/{$old_image}");
            }
            unset($image_names[$key]);
        } elseif (!empty($_FILES["new_image_{$key}"]['name'])) {
            $image_tmp_name = $_FILES["new_image_{$key}"]['tmp_name'];
            $image_name = $_FILES["new_image_{$key}"]['name'];
            $target_file = "img/" . basename($image_name);
            if (move_uploaded_file($image_tmp_name, $target_file)) {
                if (file_exists("img/{$old_image}")) {
                    unlink("img/{$old_image}");
                }
                $image_names[$key] = $image_name;
            }
        }
    }

    // Handle adding new images
    if (!empty($_FILES['new_images']['name'][0])) {
        foreach ($_FILES['new_images']['tmp_name'] as $i => $tmp_name) {
            $new_image_name = $_FILES['new_images']['name'][$i];
            $new_image_tmp_name = $_FILES['new_images']['tmp_name'][$i];
            $new_target_file = "img/" . basename($new_image_name);
            if (move_uploaded_file($new_image_tmp_name, $new_target_file)) {
                $image_names[] = $new_image_name;
            }
        }
    }

    // Update product information in the database
    $image_names = array_values(array_filter($image_names));
    $image_string = implode(",", $image_names);
    $query = "UPDATE products SET name = '$name', details = '$details', price = '$price', quantity = '$quantity', brand_id = '$brand_id', ram_id = '$ram_id', storage_id = '$storage_id', processor_id = '$processor_id', graphic_id = '$graphic_id', display_id = '$display_id', image = '$image_string' WHERE id = $edit_id";
    if (mysqli_query($conn, $query)) {
        $messages .= "Product updated successfully.";
    } else {
        $messages .= "Error updating product.";
    }

    // Re-fetch updated product details
    $product_query = mysqli_query($conn, "SELECT * FROM products WHERE id = $edit_id");
    $product = mysqli_fetch_assoc($product_query);
    $current_images = explode(",", $product['image']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="edit_product.css">
    <title>Edit Product</title>
</head>
<body>
<?php include 'admin_header.php'; ?>
<div class="container">
    <h1>Edit Product</h1>
    <form action="" method="post" enctype="multipart/form-data" class="product-form">
        <!-- Form fields for product information -->
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="details">Details:</label>
            <textarea id="details" name="details"><?= htmlspecialchars($product['details']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="<?= htmlspecialchars($product['quantity']) ?>" required>
        </div>

        <!-- Select elements for brand, RAM, storage, processor, and graphics -->
        <div class="form-group">
            <label for="brand">Brand:</label>
            <select id="brand" name="brand" required>
                <?php while ($brand = mysqli_fetch_assoc($brands)): ?>
                    <option value="<?= htmlspecialchars($brand['id']) ?>" <?= $product['brand_id'] == $brand['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($brand['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="display">Display:</label>
            <select id="display" name="display" required>
                <?php while ($display = mysqli_fetch_assoc($displays)): ?>
                    <option value="<?= htmlspecialchars($display['id']) ?>" <?= $product['display_id'] == $display['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($display['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="ram">RAM:</label>
            <select id="ram" name="ram" required>
                <?php while ($ram = mysqli_fetch_assoc($rams)): ?>
                    <option value="<?= htmlspecialchars($ram['id']) ?>" <?= $product['ram_id'] == $ram['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ram['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="storage">Storage:</label>
            <select id="storage" name="storage" required>
                <?php while ($storage = mysqli_fetch_assoc($storages)): ?>
                    <option value="<?= htmlspecialchars($storage['id']) ?>" <?= $product['storage_id'] == $storage['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($storage['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="processor">Processor:</label>
            <select id="processor" name="processor" required>
                <?php while ($processor = mysqli_fetch_assoc($processors)): ?>
                    <option value="<?= htmlspecialchars($processor['id']) ?>" <?= $product['processor_id'] == $processor['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($processor['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="graphic">Graphic:</label>
            <select id="graphic" name="graphic" required>
                <?php while ($graphic = mysqli_fetch_assoc($graphics)): ?>
                    <option value="<?= htmlspecialchars($graphic['id']) ?>" <?= $product['graphic_id'] == $graphic['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($graphic['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Image management section -->
        <table class="images-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($current_images as $key => $img): ?>
                    <tr>
                        <td>
                            <img src="img/<?= htmlspecialchars($img) ?>" alt="Product Image" style="width: 100px; height: auto;">
                        </td>
                        <td>
                            <div class="image-actions">
                                <label class="action-button"><input type="checkbox" name="delete_image_<?= $key ?>" value="1"> Delete</label>
                                <label class="action-button"><input type="file" name="new_image_<?= $key ?>" onchange="previewImage(this);"> Replace</label>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="form-group">
            <label for="new_images">Add New Images:</label>
            <input type="file" id="new_images" name="new_images[]" multiple onchange="previewMultipleImages(this);">
        </div>

        <div class="form-actions">
            <input type="submit" name="update_product" value="Update Product">
        </div>
    </form>
    <div id="popup-message">
        <?php 
            if (!empty($messages)) {
                echo "<p>$messages</p>";
            }
        ?>
    </div>
</div>
<script src="scripts.js"></script>
</body>
</html>
