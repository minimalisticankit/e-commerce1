<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['admin_name'])) {
    header('location: index.php');
    exit();
}

$messages = '';
$error_message = '';

function escape($conn, $str) {
    return mysqli_real_escape_string($conn, $str);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin_spec.css">
    <title>Manage Specifications</title>
</head>
<body>
    <?php include 'admin_header.php'; ?>

    <div class="spec-add">
        <div class="manage-section">
            <h2>Manage Brands</h2>
            <div id="brand-management">
                <form id="add-brand-form">
                    <input type="text" id="new-brand-name" placeholder="New Brand Name" required>
                    <button type="submit" id="add-brand-btn">Add Brand</button>
                </form>
                <div id="brand-list">
                    <ul>
                        <?php
                        $brands_result = mysqli_query($conn, "SELECT id, name FROM brands ORDER BY name ASC");
                        while ($brand = mysqli_fetch_assoc($brands_result)) {
                            echo "<li data-id='{$brand['id']}'>
                                <span class='brand-name'>{$brand['name']}</span>
                                <button class='edit-brand-btn'>Edit</button>
                                <button class='delete-brand-btn'>Delete</button>
                            </li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="manage-section">
            <h2>Manage Displays</h2>
            <div id="display-management">
                <form id="add-display-form">
                    <input type="text" id="new-display-name" placeholder="New Display Name" required>
                    <button type="submit" id="add-display-btn">Add Display</button>
                </form>
                <div id="display-list">
                    <ul>
                        <?php
                        $displays_result = mysqli_query($conn, "SELECT id, name FROM displays ORDER BY name ASC");
                        while ($display = mysqli_fetch_assoc($displays_result)) {
                            echo "<li data-id='{$display['id']}'>
                                <span class='display-name'>{$display['name']}</span>
                                <button class='edit-display-btn'>Edit</button>
                                <button class='delete-display-btn'>Delete</button>
                            </li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="manage-section">
            <h2>Manage Graphics</h2>
            <div id="graphic-management">
                <form id="add-graphic-form">
                    <input type="text" id="new-graphic-name" placeholder="New Graphic Name" required>
                    <button type="submit" id="add-graphic-btn">Add Graphic</button>
                </form>
                <div id="graphic-list">
                    <ul>
                        <?php
                        $graphics_result = mysqli_query($conn, "SELECT id, name FROM graphics ORDER BY name ASC");
                        while ($graphic = mysqli_fetch_assoc($graphics_result)) {
                            echo "<li data-id='{$graphic['id']}'>
                                <span class='graphic-name'>{$graphic['name']}</span>
                                <button class='edit-graphic-btn'>Edit</button>
                                <button class='delete-graphic-btn'>Delete</button>
                            </li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="manage-section">
            <h2>Manage Processors</h2>
            <div id="processor-management">
                <form id="add-processor-form">
                    <input type="text" id="new-processor-name" placeholder="New Processor Name" required>
                    <button type="submit" id="add-processor-btn">Add Processor</button>
                </form>
                <div id="processor-list">
                    <ul>
                        <?php
                        $processors_result = mysqli_query($conn, "SELECT id, name FROM processors ORDER BY name ASC");
                        while ($processor = mysqli_fetch_assoc($processors_result)) {
                            echo "<li data-id='{$processor['id']}'>
                                <span class='processor-name'>{$processor['name']}</span>
                                <button class='edit-processor-btn'>Edit</button>
                                <button class='delete-processor-btn'>Delete</button>
                            </li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="manage-section">
            <h2>Manage RAMs</h2>
            <div id="ram-management">
                <form id="add-ram-form">
                    <input type="text" id="new-ram-name" placeholder="New RAM Name" required>
                    <button type="submit" id="add-ram-btn">Add RAM</button>
                </form>
                <div id="ram-list">
                    <ul>
                        <?php
                        $rams_result = mysqli_query($conn, "SELECT id, name FROM rams ORDER BY name ASC");
                        while ($ram = mysqli_fetch_assoc($rams_result)) {
                            echo "<li data-id='{$ram['id']}'>
                                <span class='ram-name'>{$ram['name']}</span>
                                <button class='edit-ram-btn'>Edit</button>
                                <button class='delete-ram-btn'>Delete</button>
                            </li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="manage-section">
            <h2>Manage Storages</h2>
            <div id="storage-management">
                <form id="add-storage-form">
                    <input type="text" id="new-storage-name" placeholder="New Storage Name" required>
                    <button type="submit" id="add-storage-btn">Add Storage</button>
                </form>
                <div id="storage-list">
                    <ul>
                        <?php
                        $storages_result = mysqli_query($conn, "SELECT id, name FROM storages ORDER BY name ASC");
                        while ($storage = mysqli_fetch_assoc($storages_result)) {
                            echo "<li data-id='{$storage['id']}'>
                                <span class='storage-name'>{$storage['name']}</span>
                                <button class='edit-storage-btn'>Edit</button>
                                <button class='delete-storage-btn'>Delete</button>
                            </li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function ajaxRequest(url, data, callback) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    callback(xhr.responseText);
                }
            };
            xhr.send(data);
        }

        function handleAddItem(formId, listId, url, inputId, type) {
            var form = document.getElementById(formId);
            var input = document.getElementById(inputId);
            var list = document.getElementById(listId).querySelector('ul');

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                var newItemName = input.value.trim();
                if (newItemName !== '') {
                    var data = 'action=add&type=' + encodeURIComponent(type) + '&name=' + encodeURIComponent(newItemName);
                    ajaxRequest(url, data, function(response) {
                        list.innerHTML += response;
                        input.value = '';
                    });
                }
            });
        }

        function handleEditItem(buttonClass, url, type) {
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains(buttonClass)) {
                    var li = event.target.closest('li');
                    var itemId = li.dataset.id;
                    var itemName = li.querySelector('span').textContent;
                    var newName = prompt('Edit Name:', itemName);
                    if (newName !== null && newName.trim() !== '') {
                        var data = 'action=edit&type=' + encodeURIComponent(type) + '&id=' + encodeURIComponent(itemId) + '&name=' + encodeURIComponent(newName);
                        ajaxRequest(url, data, function(response) {
                            li.querySelector('span').textContent = response;
                        });
                    }
                }
            });
        }

        function handleDeleteItem(buttonClass, url, type) {
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains(buttonClass)) {
                    if (confirm('Are you sure you want to delete this item?')) {
                        var li = event.target.closest('li');
                        var itemId = li.dataset.id;
                        var data = 'action=delete&type=' + encodeURIComponent(type) + '&id=' + encodeURIComponent(itemId);
                        ajaxRequest(url, data, function(response) {
                            if (response === 'success') {
                                li.remove();
                            }
                        });
                    }
                }
            });
        }

        handleAddItem('add-brand-form', 'brand-list', 'specification_actions.php', 'new-brand-name', 'brand');
        handleEditItem('edit-brand-btn', 'specification_actions.php', 'brand');
        handleDeleteItem('delete-brand-btn', 'specification_actions.php', 'brand');

        handleAddItem('add-display-form', 'display-list', 'specification_actions.php', 'new-display-name', 'display');
        handleEditItem('edit-display-btn', 'specification_actions.php', 'display');
        handleDeleteItem('delete-display-btn', 'specification_actions.php', 'display');

        handleAddItem('add-graphic-form', 'graphic-list', 'specification_actions.php', 'new-graphic-name', 'graphic');
        handleEditItem('edit-graphic-btn', 'specification_actions.php', 'graphic');
        handleDeleteItem('delete-graphic-btn', 'specification_actions.php', 'graphic');

        handleAddItem('add-processor-form', 'processor-list', 'specification_actions.php', 'new-processor-name', 'processor');
        handleEditItem('edit-processor-btn', 'specification_actions.php', 'processor');
        handleDeleteItem('delete-processor-btn', 'specification_actions.php', 'processor');

        handleAddItem('add-ram-form', 'ram-list', 'specification_actions.php', 'new-ram-name', 'ram');
        handleEditItem('edit-ram-btn', 'specification_actions.php', 'ram');
        handleDeleteItem('delete-ram-btn', 'specification_actions.php', 'ram');

        handleAddItem('add-storage-form', 'storage-list', 'specification_actions.php', 'new-storage-name', 'storage');
        handleEditItem('edit-storage-btn', 'specification_actions.php', 'storage');
        handleDeleteItem('delete-storage-btn', 'specification_actions.php', 'storage');
    });
    </script>
</body>
</html>
