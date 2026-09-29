<?php

session_start();

require_once '../config/database.php';

$articleId = 6;
$articleTheme = 'pink';

function getCurrentUserId(): int
{
    if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
        return (int) $_SESSION['user_id'];
    }

    if (isset($_SESSION['user']['id']) && is_numeric($_SESSION['user']['id'])) {
        return (int) $_SESSION['user']['id'];
    }

    if (isset($_SESSION['user']['user_id']) && is_numeric($_SESSION['user']['user_id'])) {
        return (int) $_SESSION['user']['user_id'];
    }

    return 0;
}

$userId = getCurrentUserId();

$isSaved = false;

if ($userId > 0) {
    $sql = "
        SELECT id
        FROM saved_articles
        WHERE user_id = ?
          AND article_id = ?
        LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            'ii',
            $userId,
            $articleId
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $isSaved = $result && mysqli_num_rows($result) > 0;

        mysqli_stmt_close($stmt);
    }
}

$articleTitle = 'KENAPA LAGU-LAGU LAMA KEMBALI VIRAL?';

$articleAuthor = 'Puja Adelia S';

$articleImage = '../assets/images/old-songs-viral-1.jpg';

$relatedArticles = [
    [
        'category' => 'Music',
        'title' => 'THAT ONE SONG — Kenapa Kita Bisa Begitu Terikat dengan Lagu Tertentu?',
        'image' => '../assets/images/that-one-song.jpg',
        'link' => 'artikel-05.php',
    ],
    [
        'category' => 'Film & TV',
        'title' => 'Coralie Fargeat: Mengklaim Ulang Horor demi Kritik Kecantikan',
        'image' => '../assets/images/coralie-fargeat.jpg',
        'link' => 'artikel-08.php',
    ],
    [
        'category' => 'Film & TV',
        'title' => 'Nico Robin: Membaca Kebenaran, Merayakan Perlawanan',
        'image' => '../assets/images/nico-robin.jpg',
        'link' => 'artikel-07.php',
    ],
];

$latestArticles = [
    [
        'category' => 'Art & Photography',
        'title' => 'Pameran A Portrait of Human Art Tampilkan 40 Karya Darwis Triadi di Bali',
        'image' => '../assets/images/darwis-triadi.jpeg',
        'link' => 'artikel-03.php',
    ],
    [
        'category' => 'Music',
        'title' => 'THAT ONE SONG — Kenapa Kita Bisa Begitu Terikat dengan Lagu Tertentu?',
        'image' => '../assets/images/that-one-song.jpg',
        'link' => 'artikel-05.php',
    ],
    [
        'category' => 'Life & Culture',
        'title' => 'The Offline Era: Ketika Gen Z Mulai Lelah Hidup di Balik Layar',
        'image' => '../assets/images/offline-era.jpg',
        'link' => 'artikel-10.php',
    ],
];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?php echo htmlspecialchars($articleTitle); ?> — TULIP MAGAZINE
    </title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/article.css"
    >

    <style>

        /*
        =========================================
        ARTICLE 06
        PINK THEME
        =========================================
        */

        :root {
            --theme-1: #F28FB3;
            --theme-2: #F6AFC7;
            --theme-3: #FAD1DF;
            --theme-4: #FFF1F6;
            --theme-5: #F6B7CD;
            --theme-6: #E8759F;
            --theme-accent: #D95786;
            --theme-dark: #A82D5B;
        }

        /*
        =========================================
        BASE
        =========================================
        */

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;

            color: #111;

            background: linear-gradient(
                180deg,
                var(--theme-1) 0%,
                var(--theme-2) 9%,
                var(--theme-3) 28%,
                var(--theme-4) 58%,
                var(--theme-5) 83%,
                var(--theme-6) 100%
            );

            font-family:
                'Plus Jakarta Sans',
                Arial,
                sans-serif;

            overflow-x: hidden;
        }

        img {
            display: block;
            max-width: 100%;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input {
            font: inherit;
        }

        /*
        =========================================
        HEADER
        =========================================
        */

        .site-header {
            position: sticky;
            top: 0;
            z-index: 100;

            height: 64px;

            border-bottom:
                1px solid rgba(17, 17, 17, .55);

            background:
                rgba(242, 143, 179, .92);

            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .nav {
            width:
                min(
                    1140px,
                    calc(100% - 48px)
                );

            height: 100%;

            margin: 0 auto;

            display: flex;

            align-items: center;

            justify-content: space-between;
        }

        .brand {
            display: inline-flex;

            align-items: center;

            min-width: 90px;
        }

        .brand img {
            width: 82px;
            height: 42px;

            object-fit: contain;
        }

        .brand-text {
            display: none;

            font-size: 26px;

            font-weight: 800;

            letter-spacing: -1px;
        }

        .nav-actions {
            display: flex;

            align-items: center;

            gap: 18px;
        }

        .nav-icon {
            width: 19px;
            height: 19px;

            display: block;

            stroke: #111;

            fill: none;

            stroke-width: 1.8;
        }

        .nav-button {
            border: 0;

            background: transparent;

            padding: 4px;

            cursor: pointer;
        }

        /*
        =========================================
        ARTICLE
        =========================================
        */

        .article-shell {
            width:
                min(
                    1140px,
                    calc(100% - 48px)
                );

            margin: 0 auto;

            padding:
                58px 0 70px;
        }

        .article-top {
            width:
                min(
                    760px,
                    100%
                );

            margin: 0 auto;
        }

        .back-link {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            margin-bottom: 34px;

            font-size: 12px;

            font-weight: 700;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .article-category {
            display: block;

            width: fit-content;

            margin-bottom: 10px;

            color: var(--theme-dark);

            font-size: 10px;

            line-height: 1;

            font-weight: 700;

            text-transform: lowercase;

            letter-spacing: .3px;
        }

        .article-title {
            max-width: 700px;

            margin:
                0 0 13px;

            font-size:
                clamp(
                    32px,
                    5vw,
                    54px
                );

            line-height: 1.02;

            letter-spacing: -2.2px;

            font-weight: 800;
        }

        .article-subtitle {
            margin:
                0 0 10px;

            color: #555;

            font-size: 12px;

            line-height: 1.5;
        }

        .article-meta {
            margin-bottom: 12px;

            color: #555;

            font-size: 11px;

            line-height: 1.5;
        }

        /*
        =========================================
        SAVE BUTTON
        =========================================
        */

        .save-row {
            display: flex;

            justify-content: flex-end;

            margin-bottom: 22px;
        }

        .save-button {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            min-height: 28px;

            padding:
                5px 10px;

            border:
                1px solid #111;

            border-radius: 0;

            background: transparent;

            color: #111;

            font-size: 9px;

            font-weight: 600;

            cursor: pointer;

            transition:
                .2s ease;
        }

        .save-button:hover,
        .save-button.saved {
            background: #111;

            color: #fff;
        }

        .save-button:disabled {
            opacity: .55;

            cursor: wait;
        }

        .save-icon {
            font-size: 12px;

            line-height: 1;
        }

        /*
        =========================================
        HERO IMAGE
        =========================================
        */

        .hero-image {
            width: 100%;

            height: 390px;

            object-fit: cover;

            border:
                1px solid rgba(
                    17,
                    17,
                    17,
                    .55
                );

            margin-bottom: 7px;
        }

        .image-credit {
            margin-bottom: 36px;

            text-align: center;

            color: #555;

            font-size: 7px;

            font-style: italic;
        }

        /*
        =========================================
        ARTICLE CONTENT
        =========================================
        */

        .article-content {
            width: 100%;

            color: #151515;

            font-size: 12px;

            line-height: 1.42;
        }

        .article-content p {
            margin:
                0 0 14px;
        }

        .article-content h2 {
            margin:
                24px 0 8px;

            font-size: 17px;

            line-height: 1.1;

            font-weight: 800;
        }

        .article-content h3 {
            margin:
                20px 0 8px;

            font-size: 14px;

            line-height: 1.15;

            font-weight: 800;
        }

        .article-content h2:first-child {
            margin-top: 0;
        }

        .article-content strong {
            font-weight: 800;
        }

        .article-content em {
            font-family: Georgia, serif;
        }

        .article-content .dropcap:first-letter {
            float: left;

            margin:
                2px 5px 0 0;

            font-size: 42px;

            line-height: .72;

            font-weight: 500;
        }

        /*
        =========================================
        QUOTE
        =========================================
        */

        .article-quote {
            margin:
                20px 0;

            padding:
                4px 0 4px 12px;

            border-left:
                2px solid #111;

            font-family:
                Georgia,
                serif;

            font-size: 14px;

            font-style: italic;
        }

        /*
        =========================================
        SHARE
        =========================================
        */

        .share-row {
            width: 100%;

            margin-top: 48px;

            padding:
                10px 0;

            border-top:
                1px solid rgba(
                    17,
                    17,
                    17,
                    .25
                );

            border-bottom:
                1px solid rgba(
                    17,
                    17,
                    17,
                    .25
                );

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 12px;
        }

        .share-label {
            font-size: 8px;

            font-weight: 800;

            text-transform: uppercase;
        }

        .share-buttons {
            display: flex;

            gap: 7px;
        }

        .share-button {
            padding:
                4px 8px;

            border:
                1px solid #111;

            background: transparent;

            font-size: 7px;

            cursor: pointer;
        }

        .share-button:hover {
            background: #111;

            color: #fff;
        }

        /*
        =========================================
        RECOMMENDATION
        =========================================
        */

        .recommendation-section {
            width:
                min(
                    1140px,
                    calc(100% - 48px)
                );

            margin:
                55px auto 0;
        }

        .section-title {
            margin-bottom: 14px;

            font-size: 10px;

            font-weight: 700;
        }

        .card-grid {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 22px;
        }

        .article-card {
            min-width: 0;
        }

        .card-image {
            width: 100%;

            aspect-ratio:
                1.45 / 1;

            object-fit: cover;

            border:
                1px solid #111;

            background: #fff;

            margin-bottom: 8px;
        }

        .card-category {
            margin-bottom: 3px;

            color:
                var(--theme-dark);

            font-size: 7px;
        }

        .card-title {
            font-size: 13px;

            line-height: 1.15;

            font-weight: 800;
        }

        .card-desc {
            margin-top: 5px;

            color: #555;

            font-size: 8px;

            line-height: 1.35;
        }

        /*
        =========================================
        LATEST
        =========================================
        */

        .latest-section {
            margin-top: 50px;

            padding:
                32px 0 60px;

            background:
                linear-gradient(
                    180deg,
                    rgba(255, 255, 255, .15),
                    rgba(242, 143, 179, .38)
                );
        }

        /*
        =========================================
        FOOTER
        =========================================
        */

        .site-footer {
            border-top:
                1px solid #111;

            background:
                var(--theme-1);
        }

        .footer-inner {
            width:
                min(
                    1140px,
                    calc(100% - 48px)
                );

            margin: 0 auto;

            padding:
                44px 0 20px;
        }

        .footer-grid {
            display: grid;

            grid-template-columns:
                1.4fr .7fr .8fr 1.4fr;

            gap: 45px;
        }

        .footer-logo {
            display: inline-block;

            margin-bottom: 10px;

            font-size: 26px;

            font-weight: 800;

            letter-spacing: -1px;
        }

        .footer-desc {
            max-width: 250px;

            font-size: 9px;

            line-height: 1.45;
        }

        .footer-col h4 {
            margin-bottom: 12px;

            font-size: 8px;

            letter-spacing: .5px;
        }

        .footer-col ul {
            list-style: none;

            padding: 0;

            margin: 0;
        }

        .footer-col li {
            margin-bottom: 6px;
        }

        .footer-col a {
            font-size: 8px;
        }

        .newsletter-text {
            margin-bottom: 10px;

            font-size: 8px;

            line-height: 1.4;
        }

        .newsletter {
            display: flex;

            border:
                1px solid #111;

            background:
                rgba(
                    255,
                    255,
                    255,
                    .35
                );
        }

        .newsletter input {
            min-width: 0;

            flex: 1;

            border: 0;

            outline: 0;

            background: transparent;

            padding: 8px;

            font-size: 8px;
        }

        .newsletter button {
            border: 0;

            background: #111;

            color: #fff;

            padding:
                8px 12px;

            font-size: 7px;

            cursor: pointer;
        }

        .copyright {
            margin-top: 35px;

            padding-top: 14px;

            border-top:
                1px solid rgba(
                    17,
                    17,
                    17,
                    .25
                );

            display: flex;

            justify-content: space-between;

            gap: 15px;

            font-size: 7px;
        }

        /*
        =========================================
        DESKTOP
        =========================================
        */

        @media (min-width: 900px) {

            .article-top {
                width: 760px;
            }

            .article-content {
                font-size: 14px;

                line-height: 1.65;
            }

            .article-content h2 {
                font-size: 22px;
            }

            .article-content h3 {
                font-size: 17px;
            }

            .hero-image {
                height: 430px;
            }

        }

        /*
        =========================================
        TABLET / MOBILE
        =========================================
        */

        @media (max-width: 700px) {

            .site-header {
                height: 54px;
            }

            .nav {
                width:
                    calc(100% - 28px);
            }

            .brand img {
                width: 54px;

                height: 32px;
            }

            .nav-actions {
                gap: 13px;
            }

            .nav-icon {
                width: 15px;

                height: 15px;
            }

            .article-shell {
                width:
                    calc(100% - 30px);

                padding:
                    32px 0 45px;
            }

            .back-link {
                margin-bottom: 25px;

                font-size: 10px;
            }

            .article-title {
                font-size: 29px;

                letter-spacing: -1.3px;
            }

            .article-subtitle {
                font-size: 9px;
            }

            .article-meta {
                font-size: 9px;
            }

            .save-row {
                margin-bottom: 15px;
            }

            .hero-image {
                height: auto;

                aspect-ratio:
                    1.9 / 1;
            }

            .image-credit {
                margin-bottom: 25px;
            }

            .article-content {
                font-size: 10px;

                line-height: 1.42;
            }

            .article-content h2 {
                font-size: 15px;
            }

            .article-content h3 {
                font-size: 13px;
            }

            .article-content
            .dropcap:first-letter {
                font-size: 36px;
            }

            .recommendation-section {
                width:
                    calc(100% - 30px);

                margin-top: 38px;
            }

            .card-grid {
                grid-template-columns:
                    repeat(3, 1fr);

                gap: 12px;
            }

            .card-title {
                font-size: 10px;
            }

            .card-desc {
                font-size: 6px;
            }

            .footer-inner {
                width:
                    calc(100% - 30px);

                padding-top: 32px;
            }

            .footer-grid {
                grid-template-columns:
                    1.2fr 1fr 1fr;

                gap: 18px;
            }

            .footer-grid > :last-child {
                grid-column:
                    1 / -1;
            }

            .copyright {
                flex-direction: column;
            }

        }

        @media (max-width: 430px) {

            .article-title {
                font-size: 27px;
            }

            .article-content {
                font-size: 9.5px;
            }

            .card-title {
                font-size: 9px;
            }

        }

    </style>

</head>

<body
    class="article-page theme-pink"
    style="
        --theme-1: #F28FB3;
        --theme-2: #F6AFC7;
        --theme-3: #FAD1DF;
        --theme-4: #FFF1F6;
        --theme-5: #F6B7CD;
        --theme-6: #E8759F;
        --theme-accent: #D95786;
        --theme-dark: #A82D5B;
    "
>

<!-- =========================
     HEADER
========================= -->

<header class="site-header">

    <nav class="nav">

        <a
            href="../index.php"
            class="brand"
        >

            <img
                src="../assets/images/logo-tulip.png"
                alt="TULIP."
                onerror="
                    this.style.display='none';
                    this.nextElementSibling.style.display='block';
                "
            >

            <span class="brand-text">
                TULIP.
            </span>

        </a>

        <div class="nav-actions">

            <button
                type="button"
                class="nav-button"
                aria-label="Cari"
                onclick="window.location.href='../search.php'"
            >

                <svg
                    class="nav-icon"
                    viewBox="0 0 24 24"
                >
                    <circle
                        cx="11"
                        cy="11"
                        r="6"
                    ></circle>

                    <path
                        d="M16 16l5 5"
                    ></path>
                </svg>

            </button>

            <button
                type="button"
                class="nav-button"
                aria-label="Artikel tersimpan"
                onclick="window.location.href='../saved.php'"
            >

                <svg
                    class="nav-icon"
                    viewBox="0 0 24 24"
                >

                    <path
                        d="M6 4.5A2.5 2.5 0 0 1 8.5 2h7A2.5 2.5 0 0 1 18 4.5V21l-6-3.7L6 21V4.5z"
                    ></path>

                </svg>

            </button>

            <button
                type="button"
                class="nav-button"
                aria-label="Akun"
                onclick="window.location.href='../auth/login.php'"
            >

                <svg
                    class="nav-icon"
                    viewBox="0 0 24 24"
                >

                    <circle
                        cx="12"
                        cy="8"
                        r="3.5"
                    ></circle>

                    <path
                        d="M5 21c.7-4 3-6 7-6s6.3 2 7 6"
                    ></path>

                </svg>

            </button>

            <button
                type="button"
                class="nav-button"
                aria-label="Menu"
                onclick="window.location.href='../index.php'"
            >

                <svg
                    class="nav-icon"
                    viewBox="0 0 24 24"
                >

                    <path
                        d="M4 7h16M4 12h16M4 17h16"
                    ></path>

                </svg>

            </button>

        </div>

    </nav>

</header>

<main>

    <!-- =========================
         ARTICLE
    ========================= -->

    <section class="article-shell">

        <div class="article-top">

            <a
                href="../category.php?slug=music"
                class="back-link"
            >
                ← Kembali ke MUSIC
            </a>

            <span class="article-category">
                music
            </span>

            <h1 class="article-title">
                <?php echo htmlspecialchars($articleTitle); ?>
            </h1>

            <p class="article-subtitle">
                When the Past Becomes the New Soundtrack
            </p>

            <p class="article-meta">

                Written by

                <strong>
                    <?php echo htmlspecialchars($articleAuthor); ?>
                </strong>

                <br>

                Selasa, 01 September 2026 14:20 WIB

            </p>

            <!-- SAVE BUTTON -->

            <div class="save-row">

                <button
                    type="button"
                    id="saveButton"
                    class="save-button <?php echo $isSaved ? 'saved' : ''; ?>"
                    onclick="toggleSaveArticle()"
                >

                    <span
                        class="save-icon"
                        id="saveIcon"
                    >
                        <?php echo $isSaved ? '♥' : '♡'; ?>
                    </span>

                    <span id="saveText">
                        <?php echo $isSaved ? 'Tersimpan' : 'Simpan'; ?>
                    </span>

                </button>

            </div>

            <!-- HERO IMAGE -->

            <img
                src="<?php echo htmlspecialchars($articleImage); ?>"
                alt="<?php echo htmlspecialchars($articleTitle); ?>"
                class="hero-image"
            >

            <div class="image-credit">
                sumber: foto
            </div>

            <!-- =========================
                 ARTICLE CONTENT
            ========================= -->

            <article class="article-content">

                <p class="dropcap">
                    “Wait... lagu ini bukannya dari tahun 2000-an?”
                </p>

                <p>
                    Kalimat seperti ini mungkin sudah nggak asing lagi ketika scrolling TikTok
                    atau Instagram Reels.
                </p>

                <p>
                    Kita sedang melihat video makeup, fit check, edit film, atau sekadar video
                    seseorang jalan-jalan lalu terdengar sebuah lagu yang rasanya familiar.
                </p>

                <p>
                    Kita cek judulnya.
                </p>

                <p>
                    2005.
                </p>

                <p>
                    Atau bahkan... 1985.
                </p>

                <p>
                    Lucunya, lagu yang dirilis ketika sebagian dari kita bahkan belum lahir
                    bisa tiba-tiba terasa seperti lagu paling <em>current</em> di internet.
                </p>

                <p>
                    Lalu muncul pertanyaan:
                </p>

                <p>
                    Kenapa lagu lama bisa viral?
                </p>

                <p>
                    Apakah karena kita sedang kehabisan lagu baru?
                </p>

                <p>
                    <strong>Not really.</strong>
                </p>

                <p>
                    Justru cara kita menemukan musik sudah berubah.
                </p>

                <h2>
                    THE INTERNET DOESN'T CARE HOW OLD A SONG IS.
                </h2>

                <p>
                    Dulu, sebuah lagu biasanya punya masa kejayaan yang cukup jelas.
                </p>

                <p>
                    Dirilis → masuk radio → masuk chart → populer → perlahan digantikan lagu baru.
                </p>

                <p>
                    Sekarang?
                </p>

                <p>
                    Sebuah lagu bisa menghilang selama puluhan tahun, lalu tiba-tiba muncul lagi
                    di jutaan layar hanya karena satu video.
                </p>

                <p>
                    TikTok dan platform video pendek telah mengubah cara orang menemukan musik.
                    Penelitian mengenai fenomena ini menunjukkan bahwa algoritma platform,
                    tren viral, serta konten buatan pengguna dapat membantu lagu lama kembali
                    mendapatkan perhatian dari audiens yang lebih muda.
                </p>

                <h2>
                    THE MUSIC REVIVAL FORMULA
                </h2>

                <div class="article-quote">
                    OLD SONG → VIRAL VIDEO → ALGORITHM → MORE PEOPLE HEAR IT
                    → NEW TREND → OLD SONG, NEW AUDIENCE
                </div>

                <h2>
                    01 — THANKS, TIKTOK
                </h2>

                <p>
                    Kalau ada satu platform yang paling sering dikaitkan dengan kebangkitan
                    lagu lama, jawabannya kemungkinan besar adalah TikTok.
                </p>

                <p>
                    Bukan karena TikTok “memilih” lagu mana yang akan populer secara manual.
                    Justru karena cara kerja platform membuat sebuah lagu bisa terus menyebar
                    ketika orang-orang menggunakannya dalam konten mereka.
                </p>

                <p>
                    Sebuah penelitian tahun 2025 yang mengamati kebangkitan lagu
                    “Happy House” milik Siouxsie and the Banshees menemukan bahwa tiga faktor
                    penting dalam kebangkitan lagu tersebut adalah fitur TikTok, tren viral,
                    dan user-generated content, termasuk remix dan cover.
                </p>

                <p>
                    Artinya, sebuah lagu lama nggak perlu mendapatkan promosi besar-besaran
                    terlebih dahulu.
                </p>

                <p>
                    Kadang cukup satu orang menggunakannya untuk video yang menarik.
                </p>

                <p>
                    Lalu orang lain ikut.
                </p>

                <p>
                    Kemudian orang lain lagi.
                </p>

                <p>
                    Sampai akhirnya... <em>everyone knows the sound.</em>
                </p>

                <h2>
                    02 — 40 YEAR OLD SONG CAN STILL FEEL NEW
                </h2>

                <p>
                    Ini mungkin bagian paling menarik dari fenomena tersebut.
                </p>

                <p>
                    Ketika Gen Z menemukan lagu dari tahun 1980-an atau 1990-an melalui TikTok,
                    mereka tidak selalu melihatnya sebagai “lagu lama.”
                </p>

                <p>
                    Mereka mengenalnya sebagai:
                </p>

                <div class="article-quote">
                    lagu dari video yang sedang viral.
                </div>

                <p>
                    Dan konteks tersebut mengubah cara lagu itu dipersepsikan.
                </p>

                <p>
                    Penelitian tentang kebangkitan “Running Up That Hill” milik Kate Bush
                    menunjukkan bagaimana penggunaan lagu tersebut dalam <em>Stranger Things</em>
                    pada 2022 membantu mendorongnya kembali ke chart.
                </p>

                <p>
                    Setelah itu, pengguna TikTok membuat cover, analisis, parodi, dan berbagai
                    konten lain menggunakan lagu tersebut.
                </p>

                <p>
                    Penelitian tersebut menemukan bahwa dinamika platform dan nostalgia menjadi
                    dua faktor penting dalam munculnya kembali makna serta popularitas lagu tersebut.
                </p>

                <p>
                    Lagu yang sama, Generasi berbeda, Cerita baru.
                </p>

                <h2>
                    03 — NOSTALGIA IS HAVING A MOMENT
                </h2>

                <p>
                    Ada satu kata yang nggak bisa dilepaskan dari tren ini:
                </p>

                <div class="article-quote">
                    <strong>NOSTALGIA</strong>
                </div>

                <p>
                    Menariknya, nostalgia nggak selalu berarti kita pernah mengalami masa tersebut.
                </p>

                <p>
                    Gen Z bisa merasa nostalgic terhadap era yang bahkan tidak mereka alami
                    secara langsung.
                </p>

                <p>
                    Bayangkan seseorang yang lahir tahun 2005 mendengarkan lagu tahun 1980-an.
                </p>

                <p>
                    Mereka mungkin tidak punya memori pribadi tentang tahun 1985.
                    Tapi mereka bisa mengenal aesthetic-nya dari film, fashion, video, meme,
                    atau konten internet.
                </p>

                <p>
                    Jadi yang dirasakan bukan selalu nostalgia terhadap pengalaman pribadi.
                    Kadang yang dirindukan adalah sebuah era yang kita bayangkan.
                </p>

                <p>
                    Penelitian mengenai nostalgia di media sosial menunjukkan bahwa platform
                    digital dapat menjadi ruang bagi orang untuk mengakses dan mengekspresikan
                    nostalgia melalui konten yang mereka konsumsi dan bagikan.
                </p>

                <p>
                    And the internet is really good at making the past look cool again.
                </p>

                <h2>
                    04 — SOMETIMES, A MOVIE DOES THE JOB
                </h2>

                <p>
                    TikTok memang punya pengaruh besar.
                    Tapi bukan satu-satunya jalan sebuah lagu lama bisa kembali populer.
                </p>

                <p>
                    Film dan serial TV juga bisa menghidupkan kembali lagu yang sudah lama
                    ditinggalkan.
                </p>

                <p>
                    Contoh paling terkenal adalah “Running Up That Hill” milik Kate Bush.
                </p>

                <p>
                    Lagu tersebut dirilis pada 1985.
                    Kemudian digunakan dalam <em>Stranger Things</em> pada 2022.
                </p>

                <p>
                    Hasilnya? Lagu tersebut kembali masuk ke jajaran atas chart Amerika Serikat
                    hampir 40 tahun setelah dirilis.
                </p>

                <p>
                    Setelah muncul di serial tersebut, lagu itu juga mendapatkan kehidupan kedua
                    di TikTok melalui berbagai video, cover, dan bentuk fan content lainnya.
                </p>

                <p>
                    Hal yang sama bisa terjadi ketika lagu lama muncul dalam:
                </p>

                <p>
                    • film,<br>
                    • serial Netflix,<br>
                    • trailer,<br>
                    • meme,<br>
                    • edit karakter,<br>
                    • atau scene yang kemudian viral.
                </p>

                <p>
                    Satu scene yang memorable bisa membuat jutaan orang mencari:
                    “What's this song?”
                </p>

                <p>
                    Dan tiba-tiba... <em>streaming numbers go up.</em>
                </p>

                <h2>
                    05 — THE SONG WASN'T VIRAL. THE MOMENT WAS.
                </h2>

                <p>
                    Ini mungkin cara paling sederhana untuk memahami fenomena lagu lama.
                </p>

                <p>
                    Sering kali bukan lagunya yang tiba-tiba berubah menjadi lebih bagus.
                    Yang berubah adalah konteksnya.
                </p>

                <p>
                    Sebuah lagu mungkin terdengar biasa ketika pertama kali dirilis.
                    Namun bertahun-tahun kemudian, lagu tersebut digunakan dalam video yang menarik.
                    Atau dipakai untuk edit breakup.
                    Atau menjadi soundtrack sebuah trend.
                    Atau muncul di sebuah scene film yang emosional.
                </p>

                <div class="article-quote">
                    Same song, New context.
                </div>

                <p>
                    Dan ketika konteksnya cocok dengan budaya internet saat ini,
                    lagu tersebut punya kesempatan untuk hidup kembali.
                </p>

                <h2>
                    06 — AND THEN EVERYONE STARTS USING IT
                </h2>

                <p>
                    Ada sesuatu yang menarik dari lagu viral:
                    kita tidak hanya mendengarkannya.
                    Kita menggunakannya.
                </p>

                <p>
                    Inilah yang membedakan era streaming dengan era media sosial.
                </p>

                <p>
                    Dulu kita mungkin menemukan lagu melalui radio atau televisi.
                    Sekarang kita bisa menemukan lagu melalui seseorang yang sedang:
                </p>

                <p>
                    • GRWM,<br>
                    • fit check,<br>
                    • membuat meme,<br>
                    • melakukan dance challenge,<br>
                    • membuat fan edit,<br>
                    • memasak,<br>
                    • travelling,<br>
                    • atau sekadar berjalan di depan kamera.
                </p>

                <p>
                    Lagu kemudian menjadi bagian dari konten.
                </p>

                <p>
                    Menurut penelitian mengenai “Happy House”, penggunaan ulang melalui remix,
                    cover, dan konten buatan pengguna membantu lagu lama menjangkau audiens baru.
                </p>

                <p>
                    Semakin banyak orang menggunakan sebuah lagu, semakin banyak orang yang
                    mendengarnya.
                </p>

                <p>
                    Dan semakin banyak orang yang mendengarnya...
                    semakin besar kemungkinan seseorang mencari versi lengkapnya.
                </p>

                <h2>
                    THE OLD SONG EFFECT
                </h2>

                <p>
                    Ada beberapa lagu yang bahkan lebih menarik.
                    Mereka bukan cuma kembali viral di TikTok.
                    Mereka benar-benar kembali ke chart dan streaming platform.
                </p>

                <p>
                    Salah satu contoh awal yang terkenal adalah “Dreams” — Fleetwood Mac.
                </p>

                <p>
                    Pada 2020, sebuah video Nathan Apodaca yang sedang skateboarding sambil
                    menikmati jus cranberry dengan lagu “Dreams” menjadi viral.
                    Video tersebut mendapatkan jutaan views dan membantu membawa kembali lagu
                    tahun 1977 tersebut ke Billboard chart, sekitar 43 tahun setelah pertama
                    kali dirilis.
                </p>

                <div class="article-quote">
                    VIRAL DI MEDIA SOSIAL → BISA BERUBAH → VIRAL DI DUNIA MUSIK.
                </div>

                <h2>
                    🧠 THE SCIENCE BITE
                </h2>

                <h3>
                    WHY DOES NOSTALGIA WORK SO WELL?
                </h3>

                <p>
                    Nostalgia bukan hanya tentang “kangen masa lalu”.
                    Dalam konteks media sosial, nostalgia dapat menjadi cara untuk terhubung
                    dengan pengalaman, identitas, dan emosi tertentu.
                </p>

                <p>
                    Ketika sebuah lagu lama muncul kembali, pendengar lama bisa merasakan
                    nostalgia karena pernah mengenalnya.
                    Sementara pendengar baru bisa menciptakan hubungan emosional yang baru
                    dengan lagu tersebut.
                </p>

                <p>
                    Itulah yang membuat satu lagu bisa memiliki dua kehidupan.
                </p>

                <div class="article-checklist">

                    <h3>
                        🎧 BEFORE YOU GO
                    </h3>

                    <p>
                        Coba buka playlist lama kamu.
                    </p>

                    <p>
                        Cari satu lagu yang sudah bertahun-tahun nggak kamu dengarkan.
                    </p>

                    <p>
                        Putar.
                    </p>

                    <p>
                        Lalu lihat apakah kamu masih ingat kenapa dulu menyukainya.
                    </p>

                    <p>
                        <em>
                            Because maybe... some songs never really disappear.
                            They just wait for their next moment.
                        </em>
                    </p>

                </div>

            </article>

            <!-- =========================
                 SHARE
            ========================= -->

            <div class="share-row">

                <span class="share-label">
                    Bagikan
                </span>

                <div class="share-buttons">

                    <button
                        type="button"
                        class="share-button"
                        onclick="copyArticleLink()"
                    >
                        Salin Tautan
                    </button>

                    <button
                        type="button"
                        class="share-button"
                        onclick="shareArticle()"
                    >
                        Bagikan
                    </button>

                </div>

                

            </div>

        </div>

        <!-- =========================
             RECOMMENDATIONS
        ========================= -->

        <section class="recommendation-section">

            <h2 class="section-title">
                Kamu juga mungkin suka
            </h2>

            <div class="card-grid">

                <?php foreach ($relatedArticles as $article): ?>

                    <a
                        href="<?php echo htmlspecialchars($article['link']); ?>"
                        class="article-card"
                    >

                        <img
                            src="<?php echo htmlspecialchars($article['image']); ?>"
                            alt="<?php echo htmlspecialchars($article['title']); ?>"
                            class="card-image"
                        >

                        <div class="card-category">
                            <?php echo htmlspecialchars($article['category']); ?>
                        </div>

                        <h3 class="card-title">
                            <?php echo htmlspecialchars($article['title']); ?>
                        </h3>

                        <p class="card-desc">
                            Baca artikel pilihan TULIP lainnya.
                        </p>

                    </a>

                <?php endforeach; ?>

            </div>

        </section>

        <!-- =========================
             LATEST ARTICLES
        ========================= -->

        <section class="latest-section">

            <div class="recommendation-section">

                <h2 class="section-title">
                    Artikel terbaru
                </h2>

                <div class="card-grid">

                    <?php foreach ($latestArticles as $article): ?>

                        <a
                            href="<?php echo htmlspecialchars($article['link']); ?>"
                            class="article-card"
                        >

                            <img
                                src="<?php echo htmlspecialchars($article['image']); ?>"
                                alt="<?php echo htmlspecialchars($article['title']); ?>"
                                class="card-image"
                            >

                            <div class="card-category">
                                <?php echo htmlspecialchars($article['category']); ?>
                            </div>

                            <h3 class="card-title">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </h3>

                            <p class="card-desc">
                                Cerita terbaru dari TULIP MAGAZINE.
                            </p>

                        </a>

                    <?php endforeach; ?>

                </div>

            </div>

        </section>

    </section>

</main>

<!-- =========================
     FOOTER
========================= -->

<footer class="site-footer">

    <div class="footer-inner">

        <div class="footer-grid">

            <div>

                <a
                    href="../index.php"
                    class="footer-logo"
                >
                    TULIP.
                </a>

                <p class="footer-desc">
                    Magazine for the new generation.
                    Tempat kamu menemukan cerita,
                    gaya, dan inspirasi autentik setiap hari.
                </p>

            </div>

            <div class="footer-col">

                <h4>
                    MAGAZINE
                </h4>

                <ul>

                    <li><a href="../about.php#about">About</a></li>

                    <li><a href="../about.php#masthead">Masthead</a></li>

                    <li><a href="../about.php#contact">Contact</a></li>

                    <li><a href="../about.php#advertise">Advertise</a></li>

                </ul>

            </div>

            <div class="footer-col">

                <h4>
                    CATEGORIES
                </h4>

                <ul>

                    <li>
                        <a href="../category.php?slug=fashion-beauty">
                            Fashion &amp; Beauty
                        </a>
                    </li>

                    <li>
                        <a href="../category.php?slug=art-photography">
                            Art &amp; Photography
                        </a>
                    </li>

                    <li>
                        <a href="../category.php?slug=music">
                            Music
                        </a>
                    </li>

                    <li>
                        <a href="../category.php?slug=film-tv">
                            Film &amp; TV
                        </a>
                    </li>

                    <li>
                        <a href="../category.php?slug=life-culture">
                            Life &amp; Culture
                        </a>
                    </li>

                </ul>

            </div>

            <div class="footer-col">

                <h4>
                    STAY IN THE LOOP
                </h4>

                <p class="newsletter-text">
                    Get the latest stories delivered to your inbox.
                </p>

                <form
                    class="newsletter"
                    onsubmit="event.preventDefault();"
                >

                    <input
                        type="email"
                        placeholder="your@email.com"
                        aria-label="Email newsletter"
                    >

                    <button type="submit">
                        SUBSCRIBE
                    </button>

                </form>

            </div>

        </div>

        <div class="copyright">

            <span>
                &copy;
                <?php echo date('Y'); ?>
                TULIP MAGAZINE. ALL RIGHTS RESERVED.
            </span>

            <span>
                PRIVACY POLICY
                &nbsp;
                TERMS OF USE
                &nbsp;
                COOKIE PREFERENCES
            </span>

        </div>

    </div>

</footer>

<!-- =========================
     JAVASCRIPT
========================= -->

<script>

async function toggleSaveArticle()
{
    const button =
        document.getElementById('saveButton');

    const icon =
        document.getElementById('saveIcon');

    const text =
        document.getElementById('saveText');

    if (!button) {
        return;
    }

    button.disabled = true;

    try {

        const response =
            await fetch(
                '../save_article.php',
                {
                    method: 'POST',

                    headers: {
                        'Content-Type':
                            'application/x-www-form-urlencoded'
                    },

                    body:
                        'article_id=<?php echo (int) $articleId; ?>'
                }
            );

        const data =
            await response.json();

        if (!data.success) {

            if (data.redirect) {

                window.location.href =
                    '../' + data.redirect;

                return;
            }

            alert(
                data.message ||
                'Gagal menyimpan artikel.'
            );

            return;
        }

        if (data.saved) {

            button.classList.add('saved');

            icon.textContent = '♥';

            text.textContent =
                'Tersimpan';

        } else {

            button.classList.remove('saved');

            icon.textContent = '♡';

            text.textContent =
                'Simpan';
        }

    } catch (error) {

        console.error(error);

        alert(
            'Terjadi kesalahan koneksi.'
        );

    } finally {

        button.disabled = false;

    }
}


async function copyArticleLink()
{
    try {

        await navigator.clipboard.writeText(
            window.location.href
        );

        alert(
            'Tautan artikel berhasil disalin.'
        );

    } catch (error) {

        alert(
            'Tidak bisa menyalin tautan.'
        );

    }
}


async function shareArticle()
{
    const data = {

        title:
            <?php echo json_encode($articleTitle); ?>,

        text:
            'Baca artikel ini di TULIP MAGAZINE.',

        url:
            window.location.href

    };

    if (navigator.share) {

        try {

            await navigator.share(data);

        } catch (error) {

            // User membatalkan share.

        }

    } else {

        copyArticleLink();

    }
}

</script>

<script>
async function handleSubscribeSubmit(e, form) {
    e.preventDefault();
    const btn = form.querySelector("button[type='submit']");
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = "Loading...";
    
    try {
        const formData = new FormData(form);
        const isIndex = window.location.pathname.endsWith('index.php') || window.location.pathname === '/tulip_magazinee/';
        const url = isIndex ? 'subscribe.php' : '../subscribe.php';
        
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const result = await response.json();
        alert(result.message);
        if (result.success) {
            form.reset();
        }
    } catch (err) {
        alert("Terjadi kesalahan. Silakan coba lagi.");
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
}
</script>
</body>
</html>