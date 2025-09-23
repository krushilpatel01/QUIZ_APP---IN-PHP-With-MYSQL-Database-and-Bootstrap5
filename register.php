<?php
session_start();
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']); // adjust hash

    // check existing email
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' LIMIT 1");
    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('Email already exists'); window.history.back();</script>";
        exit();
    }

    mysqli_query($conn, "INSERT INTO users (name, email, password, role, created_at) VALUES ('$name','$email','$password','user',NOW())");
    $user_id = mysqli_insert_id($conn);
    $_SESSION['user_id'] = $user_id;
    $_SESSION['name'] = $name;
    $_SESSION['role'] = 'user';
    header("Location: index.php");
    exit();
}
