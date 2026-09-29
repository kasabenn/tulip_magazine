<?php

session_start();

require_once 'config/database.php';

header('Content-Type: application/json; charset=utf-8');


/*
|--------------------------------------------------------------------------
| Ambil user ID dari session
|--------------------------------------------------------------------------
*/

function getCurrentUserId(): int
{
    if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
        return (int) $_SESSION['user_id'];
    }

    if (
        isset($_SESSION['user']['id']) &&
        is_numeric($_SESSION['user']['id'])
    ) {
        return (int) $_SESSION['user']['id'];
    }

    if (
        isset($_SESSION['user']['user_id']) &&
        is_numeric($_SESSION['user']['user_id'])
    ) {
        return (int) $_SESSION['user']['user_id'];
    }

    return 0;
}


$userId = getCurrentUserId();


/*
|--------------------------------------------------------------------------
| User harus login
|--------------------------------------------------------------------------
*/

if ($userId <= 0) {
    echo json_encode([
        'success' => false,
        'saved' => false,
        'message' => 'Silakan login terlebih dahulu.'
    ]);
    exit;
}


/*
|--------------------------------------------------------------------------
| Ambil article_id
|--------------------------------------------------------------------------
*/

$articleId = isset($_POST['article_id'])
    ? (int) $_POST['article_id']
    : 0;


if ($articleId <= 0) {
    echo json_encode([
        'success' => false,
        'saved' => false,
        'message' => 'ID artikel tidak valid.'
    ]);
    exit;
}


/*
|--------------------------------------------------------------------------
| Cek apakah artikel sudah disimpan
|--------------------------------------------------------------------------
*/

$checkSql = "
    SELECT id
    FROM saved_articles
    WHERE user_id = ?
      AND article_id = ?
    LIMIT 1
";

$checkStmt = mysqli_prepare($conn, $checkSql);

if (!$checkStmt) {
    echo json_encode([
        'success' => false,
        'saved' => false,
        'message' => 'Gagal memeriksa artikel tersimpan.'
    ]);
    exit;
}

mysqli_stmt_bind_param(
    $checkStmt,
    'ii',
    $userId,
    $articleId
);

mysqli_stmt_execute($checkStmt);

$checkResult = mysqli_stmt_get_result($checkStmt);


/*
|--------------------------------------------------------------------------
| Kalau sudah tersimpan → HAPUS
|--------------------------------------------------------------------------
*/

if ($checkResult && mysqli_num_rows($checkResult) > 0) {

    $savedRow = mysqli_fetch_assoc($checkResult);

    $deleteSql = "
        DELETE FROM saved_articles
        WHERE id = ?
          AND user_id = ?
    ";

    $deleteStmt = mysqli_prepare($conn, $deleteSql);

    if (!$deleteStmt) {
        echo json_encode([
            'success' => false,
            'saved' => true,
            'message' => 'Gagal menghapus artikel.'
        ]);
        exit;
    }

    mysqli_stmt_bind_param(
        $deleteStmt,
        'ii',
        $savedRow['id'],
        $userId
    );

    $deleted = mysqli_stmt_execute($deleteStmt);

    mysqli_stmt_close($deleteStmt);

    if (!$deleted) {
        echo json_encode([
            'success' => false,
            'saved' => true,
            'message' => 'Gagal menghapus artikel dari tersimpan.'
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'saved' => false,
        'message' => 'Artikel dihapus dari tersimpan.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Kalau belum tersimpan → SIMPAN
|--------------------------------------------------------------------------
*/

$insertSql = "
    INSERT INTO saved_articles (user_id, article_id)
    VALUES (?, ?)
";

$insertStmt = mysqli_prepare($conn, $insertSql);

if (!$insertStmt) {
    echo json_encode([
        'success' => false,
        'saved' => false,
        'message' => 'Gagal menyimpan artikel.'
    ]);
    exit;
}

mysqli_stmt_bind_param(
    $insertStmt,
    'ii',
    $userId,
    $articleId
);

$inserted = mysqli_stmt_execute($insertStmt);

mysqli_stmt_close($insertStmt);


if (!$inserted) {

    /*
     * Kemungkinan ada UNIQUE constraint
     * user_id + article_id.
     */
    echo json_encode([
        'success' => false,
        'saved' => false,
        'message' => 'Artikel gagal disimpan.'
    ]);

    exit;
}


echo json_encode([
    'success' => true,
    'saved' => true,
    'message' => 'Artikel berhasil disimpan.'
]);

exit;