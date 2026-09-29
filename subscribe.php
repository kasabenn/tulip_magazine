<?php
session_start();
require_once 'config/database.php';

$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

$email = trim($_POST['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'Silakan masukkan alamat email yang valid.'
        ]);
        exit;
    } else {
        $_SESSION['subscribe_error'] = 'Silakan masukkan alamat email yang valid.';
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        exit;
    }
}

// Check existing
$stmt = mysqli_prepare($conn, "SELECT id FROM subscribers WHERE email = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if ($res && mysqli_num_rows($res) > 0) {
    mysqli_stmt_close($stmt);
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'message' => 'Email kamu sudah terdaftar di newsletter TULIP!'
        ]);
        exit;
    } else {
        $_SESSION['subscribe_success'] = 'Email kamu sudah terdaftar di newsletter TULIP!';
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        exit;
    }
}
mysqli_stmt_close($stmt);

// Insert new subscriber
$insertStmt = mysqli_prepare($conn, "INSERT INTO subscribers (email) VALUES (?)");
mysqli_stmt_bind_param($insertStmt, "s", $email);
$saved = mysqli_stmt_execute($insertStmt);
mysqli_stmt_close($insertStmt);

if ($saved) {
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'message' => 'Terima kasih telah berlangganan newsletter TULIP!'
        ]);
        exit;
    } else {
        $_SESSION['subscribe_success'] = 'Terima kasih telah berlangganan newsletter TULIP!';
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        exit;
    }
} else {
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
        ]);
        exit;
    } else {
        $_SESSION['subscribe_error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        exit;
    }
}
