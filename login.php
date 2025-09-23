<?php
session_start();
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']); // adjust hash

    $sql = "SELECT id, name FROM users WHERE email='$email' AND password='$password' AND role='user' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) === 1){
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Invalid email or password'); window.history.back();</script>";
    }
}
