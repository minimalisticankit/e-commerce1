<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['admin_name'])) {
    header('location: index.php');
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location: index.php');
    exit();
}

$messages = '';
$error_message = '';

function escape($conn, $str) {
    return mysqli_real_escape_string($conn, $str);
}

if (isset($_POST['add_product'])) {
    $name = escape($conn, $_POST['name']);
    $details = escape($conn, $_POST['details']);
    $price = escape($conn, $_POST['price']);
    $quantity = escape($conn, $_POST['quantity']);
    $brand_id = escape($conn, $_POST['brand']);
    $processor_id = escape($conn, $_POST['processor']);
    $ram_id = escape($conn, $_POST['ram']);
    $storage_id = escape($conn, $_POST['storage']);
    $display_id = escape($conn, $_POST['display']);
    $graphic_id = escape($conn, $_POST['graphic']);
    $new_brand_name = escape($conn, $_POST['new_brand']);
    $new_processor_name = escape($conn, $_POST['new_processor']);
    $new_ram_name = escape($conn, $_POST['new_ram']);
    $new_storage_name = escape($conn, $_POST['new_storage']);
    $new_display_name = escape($conn, $_POST['new_display']);
    $new_graphic_name = escape($conn, $_POST['new_graphic']);

    if (!empty($new_brand_name)) {
        $insert_brand = mysqli_query($conn, "INSERT INTO `brands` (`name`) VALUES ('$new_brand_name')");
        if ($insert_brand) {
            $brand_id = mysqli_insert_id($conn);
        } else {
            $error_message .= "Error adding new brand.<br>";
        }
    }

    if (!empty($new_processor_name)) {
        $insert_processor = mysqli_query($conn, "INSERT INTO `processors` (`name`) VALUES ('$new_processor_name')");
        if ($insert_processor) {
            $processor_id = mysqli_insert_id($conn);
        } else {
            $error_message .= "Error adding new processor.<br>";
        }
    }

    if (!empty($new_ram_name)) {
        $insert_ram = mysqli_query($conn, "INSERT INTO `rams` (`name`) VALUES ('$new_ram_name')");
        if ($insert_ram) {
            $ram_id = mysqli_insert_id($conn);
        } else {
            $error_message .= "Error adding new RAM.<br>";
        }
    }

    if (!empty($new_storage_name)) {
        $insert_storage = mysqli_query($conn, "INSERT INTO `storages` (`name`) VALUES ('$new_storage_name')");
        if ($insert_storage) {
            $storage_id = mysqli_insert_id($conn);
        } else {
            $error_message .= "Error adding new storage.<br>";
        }
    }

    if (!empty($new_display_name)) {
        $insert_display = mysqli_query($conn, "INSERT INTO `displays` (`name`) VALUES ('$new_display_name')");
        if ($insert_display) {
            $display_id = mysqli_insert_id($conn);
        } else {
            $error_message .= "Error adding new display.<br>";
        }
    }

    if (!empty($new_graphic_name)) {
        $insert_graphic = mysqli_query($conn, "INSERT INTO `graphics` (`name`) VALUES ('$new_graphic_name')");
        if ($insert_graphic) {
            $graphic_id = mysqli_insert_id($conn);
        } else {
            $error_message .= "Error adding new graphic.<br>";
        }
    }

    $image_names = [];
    foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
        $image_name = $_FILES['image']['name'][$key];
        $image_tmp_name = $_FILES['image']['tmp_name'][$key];
        $target_file = 'img/' . basename($image_name);
        if (move_uploaded_file($image_tmp_name, $target_file)) {
            $image_names[] = $image_name;
        }
    }

    if (!empty($image_names)) {
        $insert_product = mysqli_query($conn, "INSERT INTO `products` (`name`, `details`, `price`, `quantity`, `brand_id`, `processor_id`, `ram_id`, `storage_id`, `display_id`, `graphic_id`, `image`) VALUES ('$name', '$details', '$price', '$quantity', '$brand_id', '$processor_id', '$ram_id', '$storage_id', '$display_id', '$graphic_id', '" . implode(",", $image_names) . "')");
        if ($insert_product) {
            $messages .= "Product added successfully.<br>";
        } else {
            $error_message .= "Error adding product: " . mysqli_error($conn) . "<br>";
        }
    } else {
        $error_message .= "Error uploading images.<br>";
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $conn->begin_transaction();

    try {
        $delete_stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $delete_stmt->bind_param("i", $delete_id);
        $delete_stmt->execute();
        $delete_stmt->close();

        $conn->commit();

        $messages .= "Product deleted successfully.<br>";
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        $error_message .= "Error deleting product: " . $exception->getMessage() . "<br>";
    }
}

$search_query = '';
if (isset($_GET['search'])) {
    $search_query = escape($conn, $_GET['search']);
}

$brands = mysqli_query($conn, "SELECT id, name FROM `brands` ORDER BY name ASC");
$processors = mysqli_query($conn, "SELECT id, name FROM `processors` ORDER BY name ASC");
$rams = mysqli_query($conn, "SELECT id, name FROM `rams` ORDER BY name ASC");
$storages = mysqli_query($conn, "SELECT id, name FROM `storages` ORDER BY name ASC");
$displays = mysqli_query($conn, "SELECT id, name FROM `displays` ORDER BY name ASC");
$graphics = mysqli_query($conn, "SELECT id, name FROM `graphics` ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin_product.css">
    <title>Admin Panel</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include 'admin_header.php'; ?>

    <div class="gridbox">
        <div class="grid">

            <h2>Add Product</h2>
            <a href="admin_spec.php" class="spec-link">Manage Specifications</a>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label><br>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="details">Details:</label><br>
                    <textarea id="details" name="details"></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price:</label><br>
                    <input type="number" id="price" name="price" required>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity:</label><br>
                    <input type="number" id="quantity" name="quantity" required>
                </div>

                <div class="form-group">
                    <label for="brand">Brand:</label><br>
                    <select id="brand" name="brand" required>
                        <option value="" disabled selected>-- Choose Brand --</option>
                        <?php while ($brand = mysqli_fetch_assoc($brands)): ?>
                        <option value="<?php echo htmlspecialchars($brand['id']); ?>">
                            <?php echo htmlspecialchars($brand['name']); ?>
                        </option>
                        <?php endwhile; ?>
                        <option value="0">--- Add New Brand ---</option>
                    </select>
                </div>

                <div id="new-brand-container" class="form-group" style="display: none;">
                    <label for="new_brand">New Brand Name:</label><br>
                    <input type="text" id="new_brand" name="new_brand">
                </div>

                <div class="form-group">
                    <label for="processor">Processor:</label><br>
                    <select id="processor" name="processor" required>
                        <option value="" disabled selected>-- Choose Processor --</option>
                        <?php while ($processor = mysqli_fetch_assoc($processors)): ?>
                        <option value="<?php echo htmlspecialchars($processor['id']); ?>">
                            <?php echo htmlspecialchars($processor['name']); ?>
                        </option>
                        <?php endwhile; ?>
                        <option value="0">--- Add New Processor ---</option>
                    </select>
                </div>

                <div id="new-processor-container" class="form-group" style="display: none;">
                    <label for="new_processor">New Processor Name:</label><br>
                    <input type="text" id="new_processor" name="new_processor">
                </div>

                <div class="form-group">
                    <label for="ram">RAM:</label><br>
                    <select id="ram" name="ram" required>
                        <option value="" disabled selected>-- Choose RAM --</option>
                        <?php while ($ram = mysqli_fetch_assoc($rams)): ?>
                        <option value="<?php echo htmlspecialchars($ram['id']); ?>">
                            <?php echo htmlspecialchars($ram['name']); ?>
                        </option>
                        <?php endwhile; ?>
                        <option value="0">--- Add New RAM ---</option>
                    </select>
                </div>

                <div id="new-ram-container" class="form-group" style="display: none;">
                    <label for="new_ram">New RAM Name:</label><br>
                    <input type="text" id="new_ram" name="new_ram">
                </div>

                <div class="form-group">
                    <label for="storage">Storage:</label><br>
                    <select id="storage" name="storage" required>
                        <option value="" disabled selected>-- Choose Storage --</option>
                        <?php while ($storage = mysqli_fetch_assoc($storages)): ?>
                        <option value="<?php echo htmlspecialchars($storage['id']); ?>">
                            <?php echo htmlspecialchars($storage['name']); ?>
                        </option>
                        <?php endwhile; ?>
                        <option value="0">--- Add New Storage ---</option>
                    </select>
                </div>

                <div id="new-storage-container" class="form-group" style="display: none;">
                    <label for="new_storage">New Storage Name:</label><br>
                    <input type="text" id="new_storage" name="new_storage">
                </div>

                <div class="form-group">
                    <label for="display">Display:</label><br>
                    <select id="display" name="display" required>
                        <option value="" disabled selected>-- Choose Display --</option>
                        <?php while ($display = mysqli_fetch_assoc($displays)): ?>
                        <option value="<?php echo htmlspecialchars($display['id']); ?>">
                            <?php echo htmlspecialchars($display['name']); ?>
                        </option>
                        <?php endwhile; ?>
                        <option value="0">--- Add New Display ---</option>
                    </select>
                </div>

                <div id="new-display-container" class="form-group" style="display: none;">
                    <label for="new_display">New Display Name:</label><br>
                    <input type="text" id="new_display" name="new_display">
                </div>

                <div class="form-group">
                    <label for="graphic">Graphic:</label><br>
                    <select id="graphic" name="graphic" required>
                        <option value="" disabled selected>-- Choose Graphic --</option>
                        <?php while ($graphic = mysqli_fetch_assoc($graphics)): ?>
                        <option value="<?php echo htmlspecialchars($graphic['id']); ?>">
                            <?php echo htmlspecialchars($graphic['name']); ?>
                        </option>
                        <?php endwhile; ?>
                        <option value="0">--- Add New Graphic ---</option>
                    </select>
                </div>

                <div id="new-graphic-container" class="form-group" style="display: none;">
                    <label for="new_graphic">New Graphic Name:</label><br>
                    <input type="text" id="new_graphic" name="new_graphic">
                </div>

                <div class="form-group" style="width: 100%;">
                    <label for="image">Images:</label><br>
                    <input type="file" id="image" name="image[]" multiple required>
                </div>

                <div class="submit-container">
                    <input type="submit" name="add_product" value="Add Product">
                </div>
            </form>
        </div>

        <div id="popup-message">
            <?php 
                if (!empty($messages)) {
                    echo "<p>$messages</p>";
                }
                if (!empty($error_message)) {
                    echo "<p>$error_message</p>";
                }
            ?>
        </div>
        <script>
            window.onload = function() {
                var popup = document.getElementById('popup-message');
                if (popup.innerHTML.trim() !== '') {
                    popup.style.display = 'block';
                    setTimeout(function() {
                        popup.style.display = 'none';
                    }, 5000);
                }

                var brandSelect = document.getElementById('brand');
                var newBrandContainer = document.getElementById('new-brand-container');
                brandSelect.addEventListener('change', function() {
                    if (brandSelect.value === '0') {
                        newBrandContainer.style.display = 'block';
                    } else {
                        newBrandContainer.style.display = 'none';
                    }
                });

                var processorSelect = document.getElementById('processor');
                var newProcessorContainer = document.getElementById('new-processor-container');
                processorSelect.addEventListener('change', function() {
                    if (processorSelect.value === '0') {
                        newProcessorContainer.style.display = 'block';
                    } else {
                        newProcessorContainer.style.display = 'none';
                    }
                });

                var ramSelect = document.getElementById('ram');
                var newRamContainer = document.getElementById('new-ram-container');
                ramSelect.addEventListener('change', function() {
                    if (ramSelect.value === '0') {
                        newRamContainer.style.display = 'block';
                    } else {
                        newRamContainer.style.display = 'none';
                    }
                });

                var storageSelect = document.getElementById('storage');
                var newStorageContainer = document.getElementById('new-storage-container');
                storageSelect.addEventListener('change', function() {
                    if (storageSelect.value === '0') {
                        newStorageContainer.style.display = 'block';
                    } else {
                        newStorageContainer.style.display = 'none';
                    }
                });

                var displaySelect = document.getElementById('display');
                var newDisplayContainer = document.getElementById('new-display-container');
                displaySelect.addEventListener('change', function() {
                    if (displaySelect.value === '0') {
                        newDisplayContainer.style.display = 'block';
                    } else {
                        newDisplayContainer.style.display = 'none';
                    }
                });

                var graphicSelect = document.getElementById('graphic');
                var newGraphicContainer = document.getElementById('new-graphic-container');
                graphicSelect.addEventListener('change', function() {
                    if (graphicSelect.value === '0') {
                        newGraphicContainer.style.display = 'block';
                    } else {
                        newGraphicContainer.style.display = 'none';
                    }
                });
            };
        </script>

        <div class="full_product_details">
            <h2>Manage Products</h2>
            <div class="search-container">
                <form id="search-form" method="get" action="">
                    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query); ?>">
                </form>
            </div>

            <div class="table-container" id="product-table">
                <!-- This section will be dynamically updated via AJAX -->
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Details</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="product-table-body">
                        <?php
                        $sql = "SELECT p.id, p.image, p.name, p.details, p.price, p.quantity, b.name as brand_name 
                                FROM products p
                                LEFT JOIN brands b ON p.brand_id = b.id";
                        if (!empty($search_query)) {
                            $sql .= " WHERE p.name LIKE '%$search_query%' OR p.details LIKE '%$search_query%' OR b.name LIKE '%$search_query%'";
                        }
                        $sql .= " ORDER BY p.id DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($product = $result->fetch_assoc()) {
                            $images = explode(',', $product['image']);
                            $first_image = htmlspecialchars($images[0]);
                            echo "<tr>
                                <td>
                                    <div class='image-container'>
                                        <img src='img/$first_image' alt='Product Image'>
                                    </div>
                                </td>
                                <td>" . htmlspecialchars($product['name']) . "</td>
                                <td>" . htmlspecialchars($product['brand_name']) . "</td>
                                <td>" . nl2br(htmlspecialchars(substr($product['details'], 0, 200))) . "</td>
                                <td>RS:" . htmlspecialchars($product['price']) . "</td>
                                <td>" . htmlspecialchars($product['quantity']) . "</td>
                                <td class='action-buttons'>
                                    <a href='edit_product.php?edit_id=" . htmlspecialchars($product['id']) . "' class='edit-btn'>Edit</a>
                                    <a href='?delete_id=" . htmlspecialchars($product['id']) . "' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                </td>
                            </tr>";
                        }
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="manage-spec-button">
            <a href="admin_spec.php">Manage Specifications</a>
        </div>
    </div>
</body>

</html>

