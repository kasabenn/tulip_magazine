<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_username = mysqli_real_escape_string($conn, $_POST['email_username']);
    $password = $_POST['password'];

    // Cek apakah inputan user adalah email atau username
    $query = "SELECT * FROM users WHERE email = '$email_username' OR username = '$email_username'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verifikasi password yang di-hash
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['username'] = $row['username'];
            
            echo "<script>alert('Berhasil login! Selamat datang kembali.'); window.location='../index.php';</script>";
            exit;
        }
    }
    
    // Jika gagal
    echo "<script>alert('Email/Username atau Kata Sandi salah!'); window.location='login.php';</script>";
}
?>