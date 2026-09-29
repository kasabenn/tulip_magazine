<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "tulip_magazine");

if (!$conn) {
    die("Database error");
}

$q = $_GET['q'] ?? '';

$sql = "SELECT * FROM articles
        WHERE title LIKE '%$q%'
        OR author LIKE '%$q%'
        OR content LIKE '%$q%'
        ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pencarian — TULIP MAGAZINE</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&family=Playfair+Display:ital,wght@1,400;1,600&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

body {
    background: linear-gradient(
        180deg,
        #CD68EC 0%,
        #E39AF5 15%,
        #F3CDFD 35%,
        #FAF0FE 60%,
        #E8A8F8 85%,
        #BD4BE5 100%
    );
    color: #121811;
    min-height: 100vh;
}

a {
    text-decoration: none;
    color: inherit;
}

.container {
    max-width: 1140px;
    margin: auto;
    padding: 0 24px;
}

/* NAVBAR */
header {
    position: sticky;
    top: 0;
    z-index: 100;
    background: rgba(205,104,236,.5);
    backdrop-filter: blur(14px);
    border-bottom: 1px solid rgba(255,255,255,.5);
}

.nav {
    height: 80px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 28px;
    font-weight: 800;
    font-family: 'Syne', sans-serif;
}

.actions {
    display: flex;
    gap: 10px;
}

.icon {
    width: 44px;
    height: 44px;
    border: 0;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 18px;
}

/* SEARCH */
.hero {
    padding: 55px 0 35px;
}

.label {
    font-size: 13px;
    font-weight: 800;
    margin-bottom: 8px;
    color: #121811;
}

h1 {
    font-size: 56px;
    line-height: 1.05;
    margin-bottom: 28px;
    font-family: 'Syne', sans-serif;
    letter-spacing: -1.5px;
    text-transform: uppercase;
}

.search-box {
    position: relative;
}

.search-input {
    width: 100%;
    padding: 17px 55px 17px 24px;
    border: 0;
    border-radius: 50px;
    outline: none;
    font-size: 14px;
    background: white;
}

.search-btn {
    position: absolute;
    right: 8px;
    top: 7px;
    width: 40px;
    height: 40px;
    border: 0;
    border-radius: 50%;
    background: #121811;
    color: white;
    cursor: pointer;
}

/* RESULTS */
.results {
    padding: 25px 0 80px;
    border-top: 1px solid rgba(0,0,0,.08);
}

.count {
    margin-bottom: 25px;
    color: #584F60;
    font-size: 13px;
    font-weight: 700;
}

.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

.card {
    position: relative;
    display: flex;
    gap: 18px;
    padding: 16px;
    border-radius: 20px;
    background: rgba(255,255,255,.7);
    border: 1px solid white;
    transition: .3s;
}

.card:hover {
    background: white;
    transform: translateY(-4px);
}

.thumb {
    width: 140px;
    height: 120px;
    flex-shrink: 0;
    overflow: hidden;
    border-radius: 14px;
    background: #eee;
}

.thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.info {
    padding-right: 40px;
}

.category {
    color: #B83CE0;
    font-size: 11px;
    font-weight: 800;
    margin-bottom: 5px;
}

.title {
    font-size: 17px;
    font-weight: 800;
    line-height: 1.3;
    margin-bottom: 7px;
}

.desc {
    font-size: 12px;
    color: #584F60;
    line-height: 1.4;
}

.meta {
    margin-top: 8px;
    font-size: 11px;
    color: #718096;
}

.favorite {
    position: absolute;
    right: 15px;
    top: 15px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 0;
    background: white;
    cursor: pointer;
    font-size: 20px;
}

.favorite.active {
    color: #B83CE0;
}

/* MOBILE */
@media(max-width: 800px) {
    h1 {
        font-size: 36px;
    }

    .grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 500px) {
    .container {
        padding: 0 16px;
    }

    .card {
        flex-direction: column;
    }

    .thumb {
        width: 100%;
        height: 180px;
    }

    .info {
        padding-right: 0;
    }
}
</style>
</head>

<body>

<header>
    <div class="container nav">
        <a href="index.php" class="logo">TULIP.</a>

        <div class="actions">
            <a href="search.php" class="icon">⌕</a>
            <a href="saved.php" class="icon">♡</a>
            <a href="auth/login.php" class="icon">♙</a>
        </div>
    </div>
</header>

<main class="container">

    <section class="hero">
        <div class="label">cari</div>

        <h1>
            apa yang<br>
            kamu cari?
        </h1>

        <form method="GET" class="search-box">
            <input
                type="text"
                name="q"
                class="search-input"
                placeholder="cari artikel berdasarkan judul, author, atau topik"
                value="<?= htmlspecialchars($q) ?>"
            >

            <button class="search-btn" type="submit">
                🔍
            </button>
        </form>
    </section>

    <section class="results">

        <div class="count">
            <?= mysqli_num_rows($result) ?> artikel ditemukan
        </div>

        <div class="grid">

        <?php while ($article = mysqli_fetch_assoc($result)): 
            $articleLink = 'artikel/artikel-' . sprintf('%02d', $article['id']) . '.php';
        ?>

            <article class="card">

                <a href="<?= htmlspecialchars($articleLink) ?>" style="display: contents;">
                    <div class="thumb">
                        <img
                            src="assets/images/<?= htmlspecialchars($article['image'] ?? '') ?>"
                            alt="<?= htmlspecialchars($article['title']) ?>"
                        >
                    </div>

                    <div class="info">

                        <div class="category">
                            <?= htmlspecialchars($article['author'] ?? 'TULIP') ?>
                        </div>

                        <div class="title">
                            <?= htmlspecialchars($article['title']) ?>
                        </div>

                        <div class="desc">
                            <?= htmlspecialchars($article['excerpt'] ?? '') ?>
                        </div>

                        <div class="meta">
                            <?= htmlspecialchars($article['read_time'] ?? '') ?>
                        </div>

                    </div>
                </a>

                <button
                    class="favorite"
                    onclick="saveArticle(<?= $article['id'] ?>, this)"
                >
                    ♡
                </button>

            </article>

        <?php endwhile; ?>

        </div>

    </section>

</main>

<script>
function saveArticle(id, button) {

    fetch("save_article.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "article_id=" + id
    })
    .then(response => response.text())
    .then(data => {

        button.classList.toggle("active");

        if (button.classList.contains("active")) {
            button.innerHTML = "♥";
        } else {
            button.innerHTML = "♡";
        }

    });
}
</script>

</body>
</html>