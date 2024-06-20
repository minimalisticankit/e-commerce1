<?php
include 'connection.php';

$messages = [];

if (isset($_POST['submit-btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $select_usert = mysqli_query($conn, "SELECT * FROM `user` WHERE email = '$email'") or die('Query failed');
    
    if (mysqli_num_rows($select_usert) > 0) {
        $messages[] = 'User already exists';
    } else {
        mysqli_query($conn, "INSERT INTO `user` (`name`, `email`, `password`) VALUES ('$name', '$email', '$hashed_password')") or die('Query failed');
        header('location:login.php');
        exit();
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
    <title>Register Page</title>
</head>
<body> 
<?php 
    if (!empty($messages)) {
        foreach ($messages as $msg) {
            echo "<div class='message'>$msg<i class='fa-solid fa-xmark' onclick='this.parentElement.remove()' style='color: #ffffff;'></i></div>";
        }
    }
?> 
<div class="container" id="container"> 
    <div class="form-container sign-container">
        <form method="post">
            <h1>Register Now</h1>
            <input type="text" name="name" placeholder="Enter your Name" required>
            <input type="email" name="email" placeholder="Enter your Email" required>     
            <input type="password" name="password" placeholder="Enter Password" required>  
            <button type="submit" name="submit-btn" class="button">Sign in</button>
        </form>
    </div>
    <div class="side-container">
        <div class="side">
            <div class="side-panel side-right">
                <h1>Welcome Back!</h1>
                <p>Sign in to </p>
                <a href="login.php"><button class="ghost" id="signIn">Login now</button></a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
