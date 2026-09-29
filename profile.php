<?php
session_start();
require_once 'config/database.php';

function getCurrentUserId(): int
{
    if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
        return (int) $_SESSION['user_id'];
    }
    if (isset($_SESSION['user']['id']) && is_numeric($_SESSION['user']['id'])) {
        return (int) $_SESSION['user']['id'];
    }
    return 0;
}

$userId = getCurrentUserId();

if ($userId <= 0) {
    header("Location: auth/login.php");
    exit;
}

// Fetch user info
$stmt = mysqli_prepare($conn, "SELECT id, username, email, created_at FROM users WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$userRes = mysqli_stmt_get_result($stmt);
$userData = mysqli_fetch_assoc($userRes);
mysqli_stmt_close($stmt);

if (!$userData) {
    header("Location: auth/logout.php");
    exit;
}

// Fetch saved articles count
$savedCountQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM saved_articles WHERE user_id = $userId");
$savedCount = mysqli_fetch_assoc($savedCountQuery)['total'] ?? 0;

// Fetch saved articles
$savedArticlesQuery = mysqli_query($conn, "
    SELECT a.*, c.name as category_name, c.slug as category_slug, sa.saved_at
    FROM saved_articles sa
    JOIN articles a ON sa.article_id = a.id
    LEFT JOIN categories c ON a.category_id = c.id
    WHERE sa.user_id = $userId
    ORDER BY sa.saved_at DESC
");
$savedArticles = [];
while ($row = mysqli_fetch_assoc($savedArticlesQuery)) {
    $savedArticles[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya — TULIP MAGAZINE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --purple-bg: linear-gradient(180deg, #CD68EC 0%, #E39AF5 15%, #F3CDFD 45%, #FAF0FE 70%, #E8A8F8 90%, #BD4BE5 100%);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            min-height: 100vh;
            background: var(--purple-bg);
            color: #111;
        }
        .header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(205, 104, 236, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(17, 17, 17, 0.15);
            padding: 16px 24px;
        }
        .header-inner {
            max-width: 1140px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #111;
            font-weight: 800;
            font-size: 24px;
            letter-spacing: -0.5px;
        }
        .brand img {
            width: 38px;
            height: 38px;
            object-fit: contain;
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .btn-outline {
            padding: 8px 18px;
            border-radius: 999px;
            border: 1px solid #111;
            background: transparent;
            color: #111;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s ease;
        }
        .btn-outline:hover {
            background: #111;
            color: #fff;
        }
        .container {
            max-width: 900px;
            margin: 40px auto 80px;
            padding: 0 20px;
        }
        .profile-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.06);
            padding: 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .profile-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #BD4BE5;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 800;
            border: 3px solid #fff;
            box-shadow: 0 6px 16px rgba(189, 75, 229, 0.3);
        }
        .profile-details h1 {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 4px;
        }
        .profile-details p {
            color: #555;
            font-size: 14px;
        }
        .profile-stats {
            display: flex;
            gap: 24px;
        }
        .stat-item {
            text-align: center;
            padding: 12px 20px;
            background: rgba(205, 104, 236, 0.15);
            border-radius: 16px;
        }
        .stat-num {
            font-size: 24px;
            font-weight: 800;
            color: #BD4BE5;
        }
        .stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #444;
            font-weight: 600;
        }
        .section-title {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }
        .article-card {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.08);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            flex-direction: column;
        }
        .article-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
        }
        .card-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .card-content {
            padding: 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .card-cat {
            font-size: 10px;
            font-weight: 700;
            color: #BD4BE5;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .card-title {
            font-size: 14px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 8px;
        }
        .card-meta {
            margin-top: auto;
            font-size: 11px;
            color: #666;
        }
        .empty-state {
            background: rgba(255, 255, 255, 0.6);
            border-radius: 20px;
            padding: 48px;
            text-align: center;
        }
        .empty-state h3 {
            margin-bottom: 8px;
        }
        .empty-state p {
            color: #666;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-inner">
            <a href="index.php" class="brand">
                <img src="assets/images/logo-tulip.png" alt="TULIP." onerror="this.style.display='none'">
                <span>TULIP.</span>
            </a>
            <div class="header-actions">
                <a href="saved.php" class="btn-outline">🔖 Artikel Tersimpan</a>
                <a href="index.php" class="btn-outline">← Kembali</a>
                <a href="auth/logout.php" class="btn-outline" style="background:#111; color:#fff;">Keluar</a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="profile-card">
            <div class="profile-info">
                <div class="avatar">
                    <?php echo strtoupper(substr($userData['username'], 0, 1)); ?>
                </div>
                <div class="profile-details">
                    <h1><?php echo htmlspecialchars($userData['username']); ?></h1>
                    <p><?php echo htmlspecialchars($userData['email']); ?></p>
                    <p style="font-size: 12px; margin-top: 4px; color: #777;">Bergabung sejak <?php echo date('d M Y', strtotime($userData['created_at'])); ?></p>
                </div>
            </div>
            <div class="profile-stats">
                <div class="stat-item">
                    <div class="stat-num"><?php echo $savedCount; ?></div>
                    <div class="stat-label">Tersimpan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">Aktif</div>
                    <div class="stat-label">Status</div>
                </div>
            </div>
        </div>

        <div class="section-title">
            <span>Artikel yang Disimpan</span>
            <a href="saved.php" style="font-size: 13px; color: #BD4BE5; font-weight: 700; text-decoration: none;">Lihat Semua →</a>
        </div>

        <?php if (empty($savedArticles)): ?>
            <div class="empty-state">
                <h3>Belum ada artikel yang kamu simpan</h3>
                <p>Jelajahi berbagai cerita inspiratif dan simpan artikel favoritmu untuk dibaca nanti.</p>
                <a href="index.php" class="btn-outline" style="background: #111; color: #fff; display: inline-block;">Eksplorasi Artikel</a>
            </div>
        <?php else: ?>
            <div class="articles-grid">
                <?php foreach ($savedArticles as $art): ?>
                    <?php 
                        $artLink = 'artikel/artikel-' . str_pad($art['id'], 2, '0', STR_PAD_LEFT) . '.php';
                    ?>
                    <a href="<?php echo htmlspecialchars($artLink); ?>" class="article-card">
                        <img src="assets/images/<?php echo htmlspecialchars($art['image']); ?>" alt="<?php echo htmlspecialchars($art['title']); ?>" class="card-img">
                        <div class="card-content">
                            <span class="card-cat"><?php echo htmlspecialchars($art['category_name'] ?? 'Umum'); ?></span>
                            <h4 class="card-title"><?php echo htmlspecialchars($art['title']); ?></h4>
                            <div class="card-meta">Oleh <?php echo htmlspecialchars($art['author']); ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
