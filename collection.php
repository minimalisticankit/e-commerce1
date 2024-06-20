<?php
session_start();
include 'connection.php';

$products_per_page = 10;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $products_per_page;

$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 1000000;
$selected_brands = isset($_GET['brands']) ? $_GET['brands'] : [];
$selected_processors = isset($_GET['processors']) ? $_GET['processors'] : [];
$selected_rams = isset($_GET['rams']) ? $_GET['rams'] : [];
$selected_storages = isset($_GET['storages']) ? $_GET['storages'] : [];
$selected_displays = isset($_GET['displays']) ? $_GET['displays'] : [];
$selected_graphics = isset($_GET['graphics']) ? $_GET['graphics'] : [];

function fetchOptions($conn, $table, $product_column, $order_by_numeric = false) {
    $query = "SELECT $table.*, COUNT(products.id) as product_count 
              FROM $table 
              LEFT JOIN products ON products.$product_column = $table.id 
              GROUP BY $table.id";
    
    if ($order_by_numeric) {
        $query .= " ORDER BY CAST($table.name AS UNSIGNED) DESC, product_count DESC";
    } else {
        $query .= " ORDER BY product_count DESC";
    }

    $result = $conn->query($query);
    $options = [];
    while ($row = $result->fetch_assoc()) {
        $options[] = $row;
    }
    return $options;
}

$brands = fetchOptions($conn, 'brands', 'brand_id');
$processors = fetchOptions($conn, 'processors', 'processor_id', true);
$rams = fetchOptions($conn, 'rams', 'ram_id', true);
$storages = fetchOptions($conn, 'storages', 'storage_id', true);
$displays = fetchOptions($conn, 'displays', 'display_id', true);
$graphics = fetchOptions($conn, 'graphics', 'graphic_id', true);

$query = "SELECT products.*, brands.name AS brand_name, processors.name AS processor_name, rams.name AS ram_name, 
                 storages.name AS storage_name, displays.name AS display_name, graphics.name AS graphic_name 
          FROM products 
          JOIN brands ON products.brand_id = brands.id 
          JOIN processors ON products.processor_id = processors.id 
          JOIN rams ON products.ram_id = rams.id 
          JOIN storages ON products.storage_id = storages.id 
          JOIN displays ON products.display_id = displays.id 
          JOIN graphics ON products.graphic_id = graphics.id 
          WHERE 1";
$params = [];
$types = '';

if ($min_price > 0) {
    $query .= " AND products.price >= ?";
    $params[] = $min_price;
    $types .= 'i';
}
if ($max_price < 1000000) {
    $query .= " AND products.price <= ?";
    $params[] = $max_price;
    $types .= 'i';
}

function addFilterClause(&$query, &$params, &$types, $selected_filters, $column) {
    if (!empty($selected_filters)) {
        $placeholders = implode(',', array_fill(0, count($selected_filters), '?'));
        $query .= " AND products.$column IN ($placeholders)";
        foreach ($selected_filters as $filter_id) {
            $params[] = $filter_id;
            $types .= 'i';
        }
    }
}

addFilterClause($query, $params, $types, $selected_brands, 'brand_id');
addFilterClause($query, $params, $types, $selected_processors, 'processor_id');
addFilterClause($query, $params, $types, $selected_rams, 'ram_id');
addFilterClause($query, $params, $types, $selected_storages, 'storage_id');
addFilterClause($query, $params, $types, $selected_displays, 'display_id');
addFilterClause($query, $params, $types, $selected_graphics, 'graphic_id');

$query .= " ORDER BY products.id DESC LIMIT ?, ?";
$params[] = $offset;
$params[] = $products_per_page;
$types .= 'ii';

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Products</title>
    <link rel="stylesheet" href="collection.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.js" defer></script>
    <script src="scripts.js" defer></script>
</head>
<body>
<?php include 'header.php';?>

<div class="content-wrapper">
    <div class="filters">
        <h2>Filters <a href="collection.php" class="clear-filters">Clear Filters</a></h2>
        <form method="GET" action="collection.php">
            <div class="price-dropdown">
                <h3>Price</h3>
                <div class="price-inputs">
                    <input type="number" id="min_price" name="min_price" value="<?php echo htmlspecialchars($min_price); ?>" placeholder="NPR 0">
                    <span>to</span>
                    <input type="number" id="max_price" name="max_price" value="<?php echo htmlspecialchars($max_price); ?>" placeholder="NPR 100000">
                </div>
                <div class="price-range-slider" id="price-slider"></div>
                <div class="filter-actions">
                    <input type="submit" value="Apply">
                </div>
            </div>
            <?php
            function renderFilterSection($title, $items, $selected_items, $type) {
                echo "<div class='{$type}-filters'>
                        <h3>$title</h3>
                        <input type='text' id='{$type}-search' placeholder='Search $title'>
                        <div class='{$type}-checkboxes'>";
                $initialCount = 5;
                foreach ($items as $index => $item) {
                    $extraClass = $index >= $initialCount ? 'extra-filter' : '';
                    echo "<div class='{$type}-filter $extraClass' style='" . ($index >= $initialCount ? 'display:none;' : '') . "'>
                            <input type='checkbox' id='{$type}-" . htmlspecialchars($item['id']) . "' name='{$type}s[]' value='" . htmlspecialchars($item['id']) . "' " . (in_array($item['id'], $selected_items) ? 'checked' : '') . ">
                            <label for='{$type}-" . htmlspecialchars($item['id']) . "'>" . (strlen($item['name']) > 33 ? substr(htmlspecialchars($item['name']), 0, 33) . '...' : htmlspecialchars($item['name'])) . " (" . htmlspecialchars($item['product_count']) . ")</label>
                          </div>";
                }
                echo "</div>";
                if (count($items) > $initialCount) {
                    echo "<button type='button' class='show-more' data-type='$type'>Show More</button>";
                }
                echo "</div>";
            }

            renderFilterSection('Brand', $brands, $selected_brands, 'brand');
            renderFilterSection('Processor', $processors, $selected_processors, 'processor');
            renderFilterSection('RAM', $rams, $selected_rams, 'ram');
            renderFilterSection('Storage', $storages, $selected_storages, 'storage');
            renderFilterSection('Display', $displays, $selected_displays, 'display');
            renderFilterSection('Graphic', $graphics, $selected_graphics, 'graphic');
            ?>
            <div class="filter-actions">
                <input type="submit" value="Apply">
            </div>
        </form>
    </div>

    <div class="product-grid-wrapper">
        <div class="product-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) {
                    $images = explode(',', $product['image']);
                    $firstImage = $images[0];
                    $productName = htmlspecialchars($product['name']);
                    if (strlen($productName) > 33) {
                        $productName = substr($productName, 0, 33) . '...';
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
    </div>
</div>

</body>
</html>
