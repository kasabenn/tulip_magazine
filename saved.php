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

// If POST request, handle toggle save API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    if ($userId <= 0) {
        echo json_encode([
            'success' => false,
            'saved' => false,
            'message' => 'Silakan login terlebih dahulu.'
        ]);
        exit;
    }

    $articleId = isset($_POST['article_id']) ? (int) $_POST['article_id'] : 0;
    if ($articleId <= 0) {
        echo json_encode([
            'success' => false,
            'saved' => false,
            'message' => 'ID artikel tidak valid.'
        ]);
        exit;
    }

    $check = mysqli_prepare($conn, "SELECT id FROM saved_articles WHERE user_id = ? AND article_id = ? LIMIT 1");
    mysqli_stmt_bind_param($check, 'ii', $userId, $articleId);
    mysqli_stmt_execute($check);
    $res = mysqli_stmt_get_result($check);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $del = mysqli_prepare($conn, "DELETE FROM saved_articles WHERE id = ?");
        mysqli_stmt_bind_param($del, 'i', $row['id']);
        mysqli_stmt_execute($del);
        mysqli_stmt_close($del);
        echo json_encode([
            'success' => true,
            'saved' => false,
            'message' => 'Artikel dihapus dari tersimpan.'
        ]);
        exit;
    }

    $ins = mysqli_prepare($conn, "INSERT INTO saved_articles (user_id, article_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($ins, 'ii', $userId, $articleId);
    if (mysqli_stmt_execute($ins)) {
        echo json_encode([
            'success' => true,
            'saved' => true,
            'message' => 'Artikel berhasil disimpan.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'saved' => false,
            'message' => 'Gagal menyimpan artikel.'
        ]);
    }
    mysqli_stmt_close($ins);
    exit;
}

// GET REQUEST - Render HTML Page
$savedArticles = [];
if ($userId > 0) {
    $q = mysqli_query($conn, "
        SELECT a.*, c.name as category_name, c.slug as category_slug, sa.saved_at
        FROM saved_articles sa
        JOIN articles a ON sa.article_id = a.id
        LEFT JOIN categories c ON a.category_id = c.id
        WHERE sa.user_id = $userId
        ORDER BY sa.saved_at DESC
    ");
    while ($r = mysqli_fetch_assoc($q)) {
        $savedArticles[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel Tersimpan — TULIP MAGAZINE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --purple-1: #CD68EC;
            --purple-2: #E39AF5;
            --purple-3: #F3CDFD;
            --purple-4: #FAF0FE;
            --purple-5: #E8A8F8;
            --purple-6: #BD4BE5;
            --page-width: 1140px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            min-height: 100vh;
            color: #111;
            background: linear-gradient(180deg, var(--purple-1) 0%, var(--purple-2) 12%, var(--purple-3) 35%, var(--purple-4) 65%, var(--purple-5) 88%, var(--purple-6) 100%);
            display: flex;
            flex-direction: column;
        }
        header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(205, 104, 236, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(17, 17, 17, 0.15);
            padding: 14px 24px;
        }
        .header-inner {
            max-width: var(--page-width);
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #111;
            font-size: 24px;
            font-weight: 800;
        }
        .brand img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nav-btn {
            text-decoration: none;
            color: #111;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 999px;
            border: 1px solid #111;
            background: transparent;
            transition: 0.2s ease;
        }
        .nav-btn:hover {
            background: #111;
            color: #fff;
        }
        main {
            flex: 1;
            max-width: var(--page-width);
            width: 100%;
            margin: 40px auto 80px;
            padding: 0 24px;
        }
        .page-header {
            margin-bottom: 32px;
        }
        .page-title {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 8px;
        }
        .page-subtitle {
            color: #444;
            font-size: 14px;
        }
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
        }
        .saved-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(17, 17, 17, 0.15);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .saved-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }
        .card-img-wrap {
            position: relative;
            width: 100%;
            height: 190px;
            background: #e9e9e9;
        }
        .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .card-category {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: #BD4BE5;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .card-title {
            font-size: 16px;
            font-weight: 800;
            line-height: 1.3;
            margin-bottom: 10px;
            color: #111;
            text-decoration: none;
        }
        .card-title:hover {
            text-decoration: underline;
        }
        .card-excerpt {
            font-size: 12px;
            color: #555;
            line-height: 1.5;
            margin-bottom: 16px;
            flex: 1;
        }
        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 1px solid rgba(17, 17, 17, 0.1);
        }
        .read-btn {
            font-size: 12px;
            font-weight: 700;
            color: #111;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .read-btn:hover {
            text-decoration: underline;
        }
        .remove-btn {
            background: transparent;
            border: 1px solid rgba(17, 17, 17, 0.2);
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            color: #666;
            transition: 0.2s ease;
        }
        .remove-btn:hover {
            background: #ff4757;
            border-color: #ff4757;
            color: #fff;
        }
        .empty-container {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 60px 24px;
            text-align: center;
            border: 1px solid rgba(17, 17, 17, 0.1);
            max-width: 600px;
            margin: 40px auto;
        }
        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
            color: #BD4BE5;
        }
        .empty-title {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .empty-desc {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 24px;
        }
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #111;
            color: #fff;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            z-index: 1000;
            opacity: 0;
            transform: translateY(10px);
            transition: 0.3s ease;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            pointer-events: none;
        }
        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <header>
        <div class="header-inner">
            <a href="index.php" class="brand">
                <img src="assets/images/logo-tulip.png" alt="TULIP." onerror="this.style.display='none'">
                <span>TULIP.</span>
            </a>
            <div class="nav-links">
                <a href="index.php" class="nav-btn">← Beranda</a>
                <?php if ($userId > 0): ?>
                    <a href="profile.php" class="nav-btn">Profil Saya</a>
                <?php else: ?>
                    <a href="auth/login.php" class="nav-btn" style="background:#111; color:#fff;">Masuk</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main>
        <div class="page-header">
            <h1 class="page-title">Artikel Tersimpan</h1>
            <p class="page-subtitle">Daftar bacaan pilihan yang telah kamu simpan untuk dibaca kembali.</p>
        </div>

        <div id="articlesContainer">
            <?php if (empty($savedArticles)): ?>
                <div class="empty-container" id="emptyNotice">
                    <div class="empty-icon">🔖</div>
                    <h2 class="empty-title">Belum ada artikel tersimpan</h2>
                    <p class="empty-desc">Jelajahi artikel inspiratif dari dunia fashion, seni, musik, film, dan budaya pop, lalu klik tombol Simpan untuk menyimpannya di sini.</p>
                    <a href="index.php" class="nav-btn" style="background: #111; color: #fff; display: inline-block;">Mulai Jelajah</a>
                </div>
            <?php else: ?>
                <div class="articles-grid" id="articlesGrid">
                    <?php foreach ($savedArticles as $art): ?>
                        <?php 
                            $artLink = 'artikel/artikel-' . str_pad($art['id'], 2, '0', STR_PAD_LEFT) . '.php';
                        ?>
                        <div class="saved-card" id="card-<?php echo $art['id']; ?>">
                            <div class="card-img-wrap">
                                <img src="assets/images/<?php echo htmlspecialchars($art['image']); ?>" alt="<?php echo htmlspecialchars($art['title']); ?>" class="card-img">
                            </div>
                            <div class="card-body">
                                <div class="card-category"><?php echo htmlspecialchars($art['category_name'] ?? 'Umum'); ?></div>
                                <a href="<?php echo htmlspecialchars($artLink); ?>" class="card-title"><?php echo htmlspecialchars($art['title']); ?></a>
                                <p class="card-excerpt"><?php echo htmlspecialchars($art['excerpt']); ?></p>
                                <div class="card-footer">
                                    <a href="<?php echo htmlspecialchars($artLink); ?>" class="read-btn">Baca Artikel →</a>
                                    <button type="button" class="remove-btn" onclick="removeSaved(<?php echo $art['id']; ?>)">Hapus ✕</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <div id="toast" class="toast"></div>

    <script>
        function showToast(msg) {
            const toast = document.getElementById('toast');
            toast.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        async function removeSaved(articleId) {
            if (!confirm('Hapus artikel ini dari daftar tersimpan?')) return;

            try {
                const res = await fetch('save_article.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'article_id=' + articleId
                });
                const data = await res.json();
                if (data.success && !data.saved) {
                    const card = document.getElementById('card-' + articleId);
                    if (card) {
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';
                        setTimeout(() => {
                            card.remove();
                            const grid = document.getElementById('articlesGrid');
                            if (grid && grid.children.length === 0) {
                                location.reload();
                            }
                        }, 250);
                    }
                    showToast('Artikel berhasil dihapus dari tersimpan.');
                } else {
                    alert(data.message || 'Gagal menghapus artikel.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan jaringan.');
            }
        }
    </script>
</body>
</html>