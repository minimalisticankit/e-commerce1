<?php
include 'connection.php';
session_start();

$error_message = '';

if (isset($_POST['submit-btn'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $select_usert = mysqli_query($conn, "SELECT * FROM `user` WHERE email = '$email'") or die('Query failed');

    if (mysqli_num_rows($select_usert) > 0) {
        $row = mysqli_fetch_assoc($select_usert);
        if (password_verify($password, $row['password'])) {
            if ($row['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_id'] = $row['id'];
                header('location: admin_pannel.php');
                exit();
            } elseif ($row['user_type'] == 'user') {
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];
                header('location: index.php');
                exit();
            } else {
                $error_message = 'Incorrect email or password';
            }
        } else {
            $error_message = 'Incorrect email or password';
        }
    } else {
        $error_message = 'Incorrect email or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="login.css">   
    <script src="https://kit.fontawesome.com/68f1c79717.js" crossorigin="anonymous"></script>
    <title>Login Page</title>
</head>
<body>
<?php 
    if (!empty($error_message)) {
        echo "<div class='error'>$error_message<i class='fa-solid fa-xmark' onclick='this.parentElement.remove()' style='color: #ffffff;'></i></div>";
    }
?>
<div class="container" id="container">
    <div class="form-container sign-container">
        <form method="post">
            <h1>Login Now</h1>
            <input type="email" name="email" placeholder="Enter your Email" required>
            <input type="password" name="password" placeholder="Enter Password" required>         
            <button type="submit" name="submit-btn" class="button">Sign in</button>
        </form>   
    </div>           
    <div class="side-container">
        <div class="side">
            <div class="side-panel side-right">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <a href="register.php"><button class="ghost" id="signUp">Register now</button></a>
            </div>
        </div>
    </div>                                                              
</div>
</body>
</html>
