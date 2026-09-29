<?php

session_start();

require_once '../config/database.php';

$articleId = 2;
$articleTheme = 'green';

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

$articleTitle = 'Kita Butuh Baju Baru, atau Cuma Butuh Tren Baru?';

$articleAuthor = 'Farah Amelia';

$articleImage = '../assets/images/fashion-trend.png';


$relatedArticles = [

    [
        'category' => 'Life & Culture',
        'title' => 'The Offline Era: Ketika Gen Z Mulai Lelah Hidup di Balik Layar',
        'image' => '../assets/images/offline-era.jpg',
        'link' => 'artikel-10.php',
    ],

    [
        'category' => 'Life & Culture',
        'title' => 'Goth Girl Era: Ketika Gaya Gelap Menjadi Ekspresi Diri',
        'image' => '../assets/images/goth-girl.jpg',
        'link' => 'artikel-09.php',
    ],

    [
        'category' => 'Music',
        'title' => 'THAT ONE SONG — Kenapa Kita Bisa Begitu Terikat dengan Lagu Tertentu?',
        'image' => '../assets/images/that-one-song.jpg',
        'link' => 'artikel-05.php',
    ],

];


$latestArticles = [

    [
        'category' => 'Art & Photography',
        'title' => 'Pameran Art for Peace and a Better Future Hadirkan 150 Karya di TIM',
        'image' => '../assets/images/art-for-peace.jpeg',
        'link' => 'artikel-03.php',
    ],

    [
        'category' => 'Music',
        'title' => 'Kenapa Lagu-Lagu Lama Kembali Viral di Era Digital?',
        'image' => '../assets/images/old-songs-viral-1.jpg',
        'link' => 'artikel-06.php',
    ],

    [
        'category' => 'Film & TV',
        'title' => 'Coralie Fargeat: Mengklaim Ulang Horor demi Kritik Kecantikan',
        'image' => '../assets/images/coralie-fargeat.jpg',
        'link' => 'artikel-08.php',
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


    <style>

        /* =========================================
           ARTICLE 02
           GREEN THEME
           ========================================= */

        :root {

            --theme-1: #8FD19E;
            --theme-2: #B5E3BD;
            --theme-3: #D7F0DB;
            --theme-4: #F1FAF2;
            --theme-5: #BFE5C6;
            --theme-6: #6FBC7D;

            --theme-accent: #32934A;
            --theme-dark: #17652B;

        }


        /* =========================================
           BASE
           ========================================= */

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

            background:
                linear-gradient(
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


        /* =========================================
           HEADER
           ========================================= */

        .site-header {

            position: sticky;

            top: 0;

            z-index: 100;

            height: 64px;

            border-bottom:
                1px solid rgba(17, 17, 17, .55);

            background:
                rgba(143, 209, 158, .92);

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


        /* =========================================
           ARTICLE
           ========================================= */

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


        /* =========================================
           SAVE BUTTON
           ========================================= */

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


        /* =========================================
           HERO IMAGE
           ========================================= */

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


        /* =========================================
           ARTICLE CONTENT
           ========================================= */

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


        /* =========================================
           QUOTE
           ========================================= */

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


        /* =========================================
           CHECKLIST
           ========================================= */

        .article-checklist {

            margin:
                20px 0;

            padding:
                14px 16px;

            border:
                1px solid rgba(17,17,17,.3);

            background:
                rgba(255,255,255,.22);

        }


        .article-checklist p {

            margin:
                0 0 8px;

        }


        .article-checklist p:last-child {

            margin-bottom: 0;

        }


        /* =========================================
           SHARE
           ========================================= */

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


        /* =========================================
           RECOMMENDATION
           ========================================= */

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


        /* =========================================
           LATEST
           ========================================= */

        .latest-section {

            margin-top: 50px;

            padding:
                32px 0 60px;

            background:
                linear-gradient(
                    180deg,
                    rgba(255, 255, 255, .15),
                    rgba(111, 188, 125, .38)
                );

        }


        /* =========================================
           FOOTER
           ========================================= */

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

            margin:
                0 0 12px;

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


        /* =========================================
           DESKTOP
           ========================================= */

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


        /* =========================================
           TABLET / MOBILE
           ========================================= */

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


            .article-content .dropcap:first-letter {

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
    class="article-page theme-green"
    style="
        --theme-1: #8FD19E;
        --theme-2: #B5E3BD;
        --theme-3: #D7F0DB;
        --theme-4: #F1FAF2;
        --theme-5: #BFE5C6;
        --theme-6: #6FBC7D;
        --theme-accent: #32934A;
        --theme-dark: #17652B;
    "
>


<!-- =========================================
     HEADER
     ========================================= -->

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

            <!-- SEARCH -->

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


            <!-- SAVED -->

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


            <!-- ACCOUNT -->

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


            <!-- MENU -->

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


<!-- =========================================
     ARTICLE
     ========================================= -->

<section class="article-shell">

    <div class="article-top">


        <a
            href="../category.php?slug=fashion-beauty"
            class="back-link"
        >
            ← Kembali ke FASHION &amp; BEAUTY
        </a>


        <span class="article-category">
            fashion &amp; beauty
        </span>


        <h1 class="article-title">

            <?php echo htmlspecialchars($articleTitle); ?>

        </h1>


        <p class="article-subtitle">

            Ketika tren datang lebih cepat daripada kita sempat memakai
            baju yang sudah ada.

        </p>


        <p class="article-meta">

            Written by

            <strong>
                <?php echo htmlspecialchars($articleAuthor); ?>
            </strong>

        </p>


        <!-- SAVE -->

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


        <!-- =========================================
             ARTICLE CONTENT
             ========================================= -->

        <article class="article-content">


            <p class="dropcap">

                Coba buka lemari kamu. Berapa banyak baju yang sebenarnya masih
                layak dipakai, tetapi sudah jarang disentuh?

            </p>


            <p>

                Mungkin ada atasan yang dibeli karena sedang viral di TikTok,
                celana yang terlihat sempurna di foto produk, atau dress yang
                hanya pernah dipakai sekali karena setelah itu trennya sudah berganti.

            </p>


            <p>

                Lucunya, meskipun lemari semakin penuh, kita sering tetap merasa
                tidak punya apa-apa untuk dipakai.

            </p>


            <p>

                Fenomena ini bukan sekadar persoalan gaya hidup. Di balik kebiasaan
                membeli pakaian karena tren, ada persoalan yang lebih besar:
                <strong>overconsumption</strong>, atau konsumsi berlebihan dalam
                industri fashion.

            </p>


            <h2>
                Ketika Tren Bergerak Terlalu Cepat
            </h2>


            <p>

                Fashion memang selalu berubah. Namun, media sosial membuat perubahan
                itu terasa jauh lebih cepat.

            </p>


            <p>

                Satu minggu kita melihat satu aesthetic memenuhi TikTok. Minggu
                berikutnya muncul tren baru yang langsung masuk ke halaman For You.
                Belum sempat satu tren benar-benar hilang, tren lainnya sudah muncul.

            </p>


            <p>

                Algoritma media sosial membuat kita terus melihat produk baru,
                fashion haul, unboxing, dan rekomendasi berbagai pakaian yang
                dianggap sebagai “must-have item”.

            </p>


            <p>

                Akhirnya, pakaian bukan hanya menjadi kebutuhan atau bentuk ekspresi
                diri, tetapi juga sesuatu yang terasa harus terus diperbarui agar
                kita tidak ketinggalan.

            </p>


            <p>

                Di sinilah <strong>FOMO — fear of missing out</strong> — ikut bermain.

            </p>


            <p>

                Ketika sebuah pakaian terlihat dipakai banyak orang, muncul perasaan
                bahwa kita juga perlu memilikinya. Harga yang murah, diskon besar,
                dan promosi dengan kesan terbatas semakin membuat keputusan membeli
                terasa sulit ditolak.

            </p>


            <p>

                Padahal, pertanyaan sederhananya adalah:

            </p>


            <div class="article-quote">

                Apakah kita benar-benar membutuhkan pakaian itu,
                atau kita hanya takut ketinggalan tren?

            </div>


            <h2>
                Murah Saat Checkout, Mahal Setelahnya
            </h2>


            <p>

                Harga pakaian yang murah memang terasa menguntungkan bagi konsumen.
                Namun, angka yang terlihat saat checkout bukan satu-satunya
                “biaya” dari sebuah pakaian.

            </p>


            <p>

                Menurut United Nations Environment Programme (UNEP), sekitar
                92 juta ton limbah tekstil dihasilkan setiap tahun secara global.
                UNEP juga mencatat bahwa produksi tekstil meningkat dua kali lipat
                antara 2000 dan 2015, sementara lama penggunaan pakaian menurun
                sekitar 36 persen.

            </p>


            <p>

                Artinya, masalahnya bukan hanya tentang berapa banyak pakaian yang
                diproduksi, tetapi juga seberapa cepat pakaian tersebut dibeli,
                digunakan, lalu ditinggalkan.

            </p>


            <p>

                Dampaknya juga tidak berhenti pada tumpukan pakaian yang tidak
                terpakai. UNEP memperkirakan sektor fashion dan tekstil menyumbang
                sekitar 2–8 persen emisi gas rumah kaca global dan sekitar 9 persen
                polusi mikroplastik yang mencapai laut setiap tahunnya.

            </p>


            <p>

                Sektor ini juga menggunakan air dalam jumlah sangat besar untuk
                proses produksinya.

            </p>


            <p>

                Jadi, ketika sebuah pakaian hanya dipakai beberapa kali sebelum
                akhirnya terlupakan di lemari atau dibuang, persoalannya tidak
                berhenti pada “baju yang tidak terpakai”.

            </p>


            <p>

                Ada bahan baku, air, energi, proses produksi, transportasi,
                hingga limbah yang ikut berada di baliknya.

            </p>


            <h2>
                Bukan Berarti Kita Harus Berhenti Membeli Baju
            </h2>


            <p>

                Membicarakan sustainability dalam fashion bukan berarti semua orang
                harus berhenti membeli pakaian baru.

            </p>


            <p>

                Fashion tetap menyenangkan. Kita tetap boleh bereksperimen dengan
                gaya, mengikuti tren, dan membeli sesuatu yang memang kita sukai.

            </p>


            <p>

                Yang perlu dipertanyakan adalah pola konsumsinya.

            </p>


            <p>

                Data dari European Environment Agency (EEA) menunjukkan bahwa
                negara-negara Uni Eropa menghasilkan sekitar 6,94 juta ton limbah
                tekstil pada 2022, setara dengan sekitar 16 kilogram per orang.

            </p>


            <p>

                Angka tersebut menunjukkan bahwa persoalan limbah tekstil bukan
                sesuatu yang kecil. Karena itu, perubahan tidak hanya perlu
                dilakukan oleh konsumen, tetapi juga oleh industri.

            </p>


            <p>

                UNEP mendorong pergeseran menuju sistem fashion yang lebih
                <em>circular</em>, yaitu sistem yang berusaha memperpanjang masa
                pakai pakaian melalui penggunaan kembali, perbaikan, dan daur ulang,
                sekaligus mengurangi ketergantungan pada produksi baru.

            </p>


            <p>

                Dengan kata lain, sustainable fashion bukan sekadar tentang membeli
                produk dengan label “eco-friendly”.

            </p>


            <p>

                Cara kita menggunakan dan memperpanjang umur pakaian juga memiliki
                peran penting.

            </p>


            <h2>
                Dunia Mulai Mempertanyakan Ultra-Fast Fashion
            </h2>


            <p>

                Isu ini bahkan mulai masuk ke ranah kebijakan.

            </p>


            <p>

                Pada 1 September 2026, Prancis mulai memberlakukan penalti terhadap
                produk dari perusahaan ultra-fast fashion seperti Shein dan Temu.
                Menurut laporan Reuters, kebijakan tersebut merupakan bagian dari
                aturan yang ditujukan untuk mengatasi persoalan pakaian “sekali
                pakai”, overproduksi, dan dampak lingkungan dari model ultra-fast
                fashion.

            </p>


            <p>

                Besaran penalti dapat meningkat hingga 2030 dan sebagian dana yang
                terkumpul akan digunakan untuk mendukung sistem daur ulang tekstil.

            </p>


            <p>

                Langkah tersebut menunjukkan bahwa persoalan fashion kini tidak
                lagi hanya dilihat sebagai pilihan pribadi konsumen.

            </p>


            <p>

                Cara industri memproduksi, menjual, dan memasarkan pakaian juga
                mulai mendapat perhatian dari pemerintah.

            </p>


            <p>

                Dan mungkin, ini adalah tanda bahwa dunia fashion sedang memasuki
                pertanyaan baru:

            </p>


            <div class="article-quote">

                Seberapa banyak fashion yang sebenarnya kita butuhkan?

            </div>


            <h2>
                Before You Checkout
            </h2>


            <p>

                Kita memang tidak bisa mengontrol seluruh sistem industri fashion.
                Tetapi kita bisa mulai dari keputusan kecil sebelum menekan tombol
                checkout.

            </p>


            <p>

                Sebelum membeli sesuatu yang sedang viral, coba tanyakan:

            </p>


            <div class="article-quote">

                “Aku benar-benar butuh ini?”

            </div>


            <div class="article-checklist">

                <p>
                    • Apakah aku sudah punya pakaian yang mirip?
                </p>

                <p>
                    • Bisa nggak pakaian ini dipadukan dengan minimal tiga outfit
                    yang sudah aku punya?
                </p>

                <p>
                    • Apakah aku masih ingin memakainya ketika tren ini sudah selesai?
                </p>

                <p>
                    • Berapa kali kira-kira aku akan memakainya?
                </p>

                <p>
                    • Aku membeli karena memang suka, atau karena semua orang sedang
                    memilikinya?
                </p>

            </div>


            <p>

                Kalau jawabannya masih belum jelas, mungkin tidak ada salahnya
                menunggu beberapa hari sebelum membeli.

            </p>


            <p>

                Karena tidak semua tren harus masuk ke lemari kita.

            </p>


            <h2>
                Maybe, the New Trend Is Wearing What We Already Own
            </h2>


            <p>

                Fashion seharusnya menjadi ruang untuk berekspresi, bukan perlombaan
                untuk selalu memiliki sesuatu yang terbaru.

            </p>


            <p>

                Mungkin kita tidak perlu terus mengejar setiap tren yang muncul
                di layar. Pakaian yang sudah lama berada di lemari masih bisa
                terlihat baru ketika dipadukan dengan cara berbeda.

            </p>


            <p>

                Memakai kembali outfit yang sama bukan sesuatu yang membosankan,
                tetapi bisa menjadi bagian dari cara kita mengonsumsi fashion
                dengan lebih sadar.

            </p>


            <p>

                Pada akhirnya, pertanyaannya bukan tentang:

            </p>


            <div class="article-quote">

                “Berapa banyak baju yang kita punya?”

            </div>


            <p>

                Tetapi:

            </p>


            <div class="article-quote">

                “Seberapa sering kita benar-benar menghargai dan menggunakan
                apa yang sudah kita punya?”

            </div>


            <p>

                Karena bisa jadi, yang kita butuhkan bukan baju baru.

            </p>


            <p>

                <strong>
                    Kita cuma butuh cara baru untuk melihat lemari kita.
                </strong>

            </p>


        </article>


        <!-- =========================================
             SHARE
             ========================================= -->

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


    <!-- =========================================
         RECOMMENDATIONS
         ========================================= -->

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


    <!-- =========================================
         LATEST ARTICLES
         ========================================= -->

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


<!-- =========================================
     FOOTER
     ========================================= -->

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
                TULIP MAGAZINE.
                ALL RIGHTS RESERVED.

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


<!-- =========================================
     JAVASCRIPT
     ========================================= -->

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

    const shareData = {

        title:
            <?php echo json_encode($articleTitle); ?>,

        text:
            'Baca artikel ini di TULIP MAGAZINE.',

        url:
            window.location.href

    };


    if (navigator.share) {

        try {

            await navigator.share(
                shareData
            );

        } catch (error) {

            // User membatalkan share dialog.

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