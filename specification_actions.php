<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'], $_POST['type'])) {
        $action = $_POST['action'];
        $type = $_POST['type'];
        $response = '';

        // Determine the table name based on the type
        $table = '';
        switch ($type) {
            case 'brand':
                $table = 'brands';
                break;
            case 'processor':
                $table = 'processors';
                break;
            case 'ram':
                $table = 'rams';
                break;
            case 'storage':
                $table = 'storages';
                break;
            case 'display':
                $table = 'displays';
                break;
            case 'graphic':
                $table = 'graphics';
                break;
            default:
                echo 'Invalid type';
                exit();
        }

        switch ($action) {
            case 'add':
                if (isset($_POST['name'])) {
                    $name = mysqli_real_escape_string($conn, $_POST['name']);
                    $query = "INSERT INTO $table (name) VALUES ('$name')";
                    if (mysqli_query($conn, $query)) {
                        $id = mysqli_insert_id($conn);
                        $response = "<li data-id='$id'>
                                        <span class='$type-name'>$name</span>
                                        <button class='edit-$type-btn'>Edit</button>
                                        <button class='delete-$type-btn'>Delete</button>
                                     </li>";
                    }
                }
                break;

            case 'edit':
                if (isset($_POST['id'], $_POST['name'])) {
                    $id = intval($_POST['id']);
                    $name = mysqli_real_escape_string($conn, $_POST['name']);
                    $query = "UPDATE $table SET name='$name' WHERE id=$id";
                    if (mysqli_query($conn, $query)) {
                        $response = $name;
                    }
                }
                break;

            case 'delete':
                if (isset($_POST['id'])) {
                    $id = intval($_POST['id']);
                    $query = "DELETE FROM $table WHERE id=$id";
                    if (mysqli_query($conn, $query)) {
                        $response = 'success';
                    }
                }
                break;

            default:
                echo 'Invalid action';
                exit();
        }

        echo $response;
    } else {
        echo 'Invalid request';
    }
}
?>
