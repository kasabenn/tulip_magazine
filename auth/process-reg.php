<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah email atau username sudah terdaftar
    $cek_sql = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
    $cek_result = mysqli_query($conn, $cek_sql);

    if (mysqli_num_rows($cek_result) > 0) {
        echo "<script>alert('Username atau Email sudah digunakan!'); window.location='register.php';</script>";
    } else {
        // Masukkan data baru ke database
        $insert_sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $insert_sql)) {
            echo "<script>alert('Registrasi berhasil! Silakan masuk.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan sistem, coba lagi.'); window.location='register.php';</script>";
        }
    }
}
?>