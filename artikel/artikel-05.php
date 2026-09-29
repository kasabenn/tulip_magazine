<?php

session_start();

require_once '../config/database.php';

$articleId = 5;
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

$articleTitle = 'THAT ONE SONG — Kenapa Kita Bisa Begitu Terikat dengan Lagu Tertentu?';

$articleAuthor = 'Puja Adelia S';

$articleImage = '../assets/images/that-one-song.jpg';

$relatedArticles = [
    [
        'category' => 'Music',
        'title' => 'Kenapa Lagu-Lagu Lama Kembali Viral di Era Digital?',
        'image' => '../assets/images/old-songs-viral.jpg',
        'link' => 'artikel-06.php',
    ],
    [
        'category' => 'Film & TV',
        'title' => 'Nico Robin: Membaca Kebenaran, Merayakan Perlawanan',
        'image' => '../assets/images/nico-robin.jpg',
        'link' => 'artikel-07.php',
    ],
    [
        'category' => 'Life & Culture',
        'title' => 'The Offline Era: Ketika Gen Z Mulai Lelah Hidup di Balik Layar',
        'image' => '../assets/images/offline-era.jpg',
        'link' => 'artikel-10.php',
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
        'title' => 'Kenapa Lagu-Lagu Lama Kembali Viral di Era Digital?',
        'image' => '../assets/images/old-songs-viral.jpg',
        'link' => 'artikel-06.php',
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

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --pink-1: #F28FB3;
            --pink-2: #F6AFC7;
            --pink-3: #FAD1DF;
            --pink-4: #FFF1F6;
            --pink-5: #F6B7CD;
            --pink-6: #E8759F;
            --pink-accent: #D95786;

            --text: #111;
            --muted: #555;
            --line: #111;
            --white: #fff;

            --page-width: 1140px;
            --article-width: 760px;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;

            color: var(--text);

            background: linear-gradient(
                180deg,
                var(--pink-1) 0%,
                var(--pink-2) 9%,
                var(--pink-3) 28%,
                var(--pink-4) 58%,
                var(--pink-5) 83%,
                var(--pink-6) 100%
            );

            font-family: 'Plus Jakarta Sans', Arial, sans-serif;

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


        /* =========================
           HEADER
        ========================= */

        .site-header {
            position: sticky;
            top: 0;
            z-index: 100;

            height: 64px;

            border-bottom: 1px solid rgba(17, 17, 17, .55);

            background: rgba(242, 143, 179, .92);

            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .nav {
            width: min(
                var(--page-width),
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


        /* =========================
           ARTICLE
        ========================= */

        .article-shell {
            width: min(
                var(--page-width),
                calc(100% - 48px)
            );

            margin: 0 auto;

            padding: 58px 0 70px;
        }

        .article-top {
            width: min(
                var(--article-width),
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

            color: #A82D5B;

            font-size: 10px;
            line-height: 1;

            font-weight: 700;

            text-transform: lowercase;

            letter-spacing: .3px;
        }

        .article-title {
            max-width: 700px;

            margin-bottom: 13px;

            font-size: clamp(
                32px,
                5vw,
                54px
            );

            line-height: 1.02;

            letter-spacing: -2.2px;

            font-weight: 800;
        }

        .article-subtitle {
            max-width: 650px;

            margin-bottom: 13px;

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


        /* =========================
           SAVE BUTTON
        ========================= */

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

            padding: 5px 10px;

            border: 1px solid #111;
            border-radius: 0;

            background: transparent;

            color: #111;

            font-size: 9px;
            font-weight: 600;

            cursor: pointer;

            transition: .2s ease;
        }

        .save-button:hover,
        .save-button.saved {
            background: #111;
            color: #fff;
        }

        .save-icon {
            font-size: 12px;
            line-height: 1;
        }


        /* =========================
           HERO IMAGE
        ========================= */

        .hero-image {
            width: 100%;

            height: 390px;

            object-fit: cover;

            border: 1px solid rgba(17, 17, 17, .55);

            margin-bottom: 7px;
        }

        .image-credit {
            margin-bottom: 36px;

            text-align: center;

            color: #555;

            font-size: 7px;

            font-style: italic;
        }


        /* =========================
           ARTICLE CONTENT
        ========================= */

        .article-content {
            width: 100%;

            color: #151515;

            font-size: 12px;

            line-height: 1.42;
        }

        .article-content p {
            margin-bottom: 14px;
        }

        .article-content h2 {
            margin: 24px 0 8px;

            font-size: 17px;

            line-height: 1.1;

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

            margin: 2px 5px 0 0;

            font-size: 42px;

            line-height: .72;

            font-weight: 500;
        }

        .article-quote {
            margin: 20px 0;

            padding: 4px 0 4px 12px;

            border-left: 2px solid #111;

            font-family: Georgia, serif;

            font-size: 14px;

            font-style: italic;
        }


        /* =========================
           SHARE
        ========================= */

        .share-row {
            width: 100%;

            margin-top: 48px;

            padding: 10px 0;

            border-top: 1px solid rgba(17, 17, 17, .25);
            border-bottom: 1px solid rgba(17, 17, 17, .25);

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
            padding: 4px 8px;

            border: 1px solid #111;

            background: transparent;

            font-size: 7px;

            cursor: pointer;
        }


        /* =========================
           RECOMMENDATION
        ========================= */

        .recommendation-section {
            width: min(
                var(--page-width),
                calc(100% - 48px)
            );

            margin: 55px auto 0;
        }

        .section-title {
            margin-bottom: 14px;

            font-size: 10px;

            font-weight: 700;
        }

        .card-grid {
            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 22px;
        }

        .article-card {
            min-width: 0;
        }

        .card-image {
            width: 100%;

            aspect-ratio: 1.45 / 1;

            object-fit: cover;

            border: 1px solid #111;

            background: #fff;

            margin-bottom: 8px;
        }

        .card-category {
            margin-bottom: 3px;

            color: #A82D5B;

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


        /* =========================
           LATEST
        ========================= */

        .latest-section {
            margin-top: 50px;

            padding: 32px 0 60px;

            background: linear-gradient(
                180deg,
                rgba(255, 255, 255, .15),
                rgba(242, 143, 179, .38)
            );
        }


        /* =========================
           FOOTER
        ========================= */

        .site-footer {
            border-top: 1px solid #111;

            background: #F28FB3;
        }

        .footer-inner {
            width: min(
                var(--page-width),
                calc(100% - 48px)
            );

            margin: 0 auto;

            padding: 44px 0 20px;
        }

        .footer-grid {
            display: grid;

            grid-template-columns:
                1.4fr
                .7fr
                .8fr
                1.4fr;

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

            border: 1px solid #111;

            background: rgba(
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

            padding: 8px 12px;

            font-size: 7px;

            cursor: pointer;
        }

        .copyright {
            margin-top: 35px;

            padding-top: 14px;

            border-top: 1px solid rgba(
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


        /* =========================
           DESKTOP
        ========================= */

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

            .hero-image {
                height: 430px;
            }
        }


        /* =========================
           TABLET / MOBILE
        ========================= */

        @media (max-width: 700px) {

            .site-header {
                height: 54px;
            }

            .nav {
                width: calc(100% - 28px);
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
                width: calc(100% - 30px);

                padding: 32px 0 45px;
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

                aspect-ratio: 1.9 / 1;
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

            .article-content .dropcap:first-letter {
                font-size: 36px;
            }

            .recommendation-section {
                width: calc(100% - 30px);

                margin-top: 38px;
            }

            .card-grid {
                grid-template-columns: repeat(3, 1fr);

                gap: 12px;
            }

            .card-title {
                font-size: 10px;
            }

            .card-desc {
                font-size: 6px;
            }

            .footer-inner {
                width: calc(100% - 30px);

                padding-top: 32px;
            }

            .footer-grid {
                grid-template-columns:
                    1.2fr
                    1fr
                    1fr;

                gap: 18px;
            }

            .footer-grid > :last-child {
                grid-column: 1 / -1;
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


<body class="article-page theme-pink">


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
                    ← Kembali ke music
                </a>


                <span class="article-category">
                    music
                </span>


                <h1 class="article-title">
                    <?php echo htmlspecialchars($articleTitle); ?>
                </h1>


                <p class="article-subtitle">
                    Kenapa ada lagu tertentu yang bisa langsung membawa kita kembali
                    ke satu momen, satu tempat, atau seseorang?
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


                <!-- ARTICLE CONTENT -->

                <article class="article-content">


                    <p class="dropcap">
                        Ada satu lagu yang bahkan setelah bertahun-tahun nggak kamu dengar,
                        begitu intro-nya mulai... kamu langsung tahu.
                    </p>

                    <p>
                        Tiba-tiba kamu ingat seseorang.
                        Sebuah tempat.
                        Sebuah percakapan.
                        Bahkan versi dirimu yang sudah lama nggak kamu temui.
                    </p>

                    <p>
                        Padahal... cuma lagu.
                    </p>

                    <p>
                        Pernah mengalaminya?
                    </p>

                    <p>
                        Mungkin ada lagu yang selalu mengingatkanmu pada masa SMA.
                        Lagu yang dulu diputar sepanjang perjalanan bersama teman-teman.
                        Atau lagu yang pernah menjadi soundtrack dari sebuah hubungan
                        yang sekarang sudah selesai.
                    </p>

                    <p>
                        Aneh, ya? Kenapa dari ribuan lagu yang pernah kita dengarkan,
                        ada beberapa lagu yang terasa seperti milik kita sendiri?
                    </p>

                    <p>
                        Ternyata, otak kita punya alasannya.
                    </p>


                    <div class="article-quote">

                        <strong>
                            IT'S NOT JUST THE SONG.
                        </strong>

                        <br>

                        IT'S THE MEMORY.

                    </div>


                    <p>
                        Musik punya kemampuan unik untuk menghubungkan suara dengan pengalaman pribadi.
                        Ketika sebuah lagu sering kita dengarkan dalam situasi tertentu, otak bisa
                        menghubungkan lagu tersebut dengan orang, tempat, suasana, atau peristiwa
                        yang sedang terjadi pada saat itu.
                    </p>

                    <p>
                        Jadi ketika lagu yang sama terdengar lagi beberapa tahun kemudian,
                        yang kembali bukan cuma melodinya.
                        Memorinya ikut muncul.
                    </p>

                    <p>
                        Sebuah penelitian pada 84 orang menemukan bahwa potongan lagu mampu memicu
                        ingatan autobiografis sekitar 49% dari percobaan, dibandingkan sekitar 33%
                        ketika lirik yang sama hanya disampaikan tanpa melodi.
                    </p>


                    <h2>
                        THAT SONG YOU USED TO PLAY ON REPEAT
                    </h2>

                    <p>
                        Ada alasan kenapa lagu yang dulu sering kamu dengarkan terasa jauh lebih personal.
                    </p>

                    <p>
                        Semakin familiar sebuah lagu, semakin banyak kesempatan lagu tersebut hadir
                        bersamaan dengan pengalaman hidupmu.
                    </p>

                    <p>
                        Dalam sebuah penelitian terhadap 100 orang dewasa muda berusia 18–29 tahun,
                        musik yang familiar memunculkan sekitar tiga kali lebih banyak ingatan
                        autobiografis dibandingkan musik yang kurang familiar.
                    </p>

                    <p>
                        Bayangkan sebuah lagu yang kamu dengarkan hampir setiap hari selama satu tahun.
                    </p>

                    <p>
                        • berangkat kuliah,<br>
                        • perjalanan pulang,<br>
                        • nongkrong dengan teman,<br>
                        • mengerjakan tugas sampai malam,<br>
                        • jatuh cinta,<br>
                        • patah hati,<br>
                        • atau sekadar rebahan sambil scrolling TikTok.
                    </p>

                    <p>
                        Tanpa sadar, lagu tersebut mulai mengumpulkan cerita.
                    </p>

                    <p>
                        Satu lagu. Banyak memori.
                    </p>

                    <p>
                        Dan ketika lagu itu kembali diputar setelah sekian lama...
                        <em>you're back there.</em>
                    </p>


                    <h2>
                        WHEN A SONG BECOMES A PERSON
                    </h2>

                    <p>
                        Ini bagian yang agak menyebalkan.
                    </p>

                    <p>
                        Kadang kita nggak cuma terikat pada sebuah lagu.
                        Kita terikat pada orang yang kita asosiasikan dengan lagu tersebut.
                    </p>

                    <p>
                        Mungkin dulu kamu sering mendengarkannya bersama seseorang.
                        Mungkin seseorang pernah mengirimkan lagu itu kepadamu.
                        Atau mungkin lagu tersebut hanya kebetulan selalu terdengar pada masa
                        ketika orang itu ada dalam hidupmu.
                    </p>

                    <p>
                        Kemudian hubungan tersebut berakhir.
                        Orangnya pergi.
                        Tapi lagunya tetap ada.
                    </p>

                    <p>
                        Dan sekarang, setiap kali lagu itu muncul di playlist...
                        <em>you remember.</em>
                    </p>

                    <p>
                        Penelitian mengenai musik dan autobiographical memory menunjukkan bahwa
                        lagu yang memiliki hubungan personal yang kuat dengan pendengarnya cenderung
                        memunculkan ingatan yang lebih hidup dan emosional.
                    </p>

                    <p>
                        Musik juga berkaitan dengan aktivitas jaringan otak yang berperan dalam
                        autobiographical memory dan pemrosesan diri.
                    </p>

                    <p>
                        Jadi, kalau kamu pernah merasa,
                        “Aku nggak bisa dengar lagu ini tanpa ingat dia.”
                        Well... you're definitely not the only one.
                    </p>


                    <h2>
                        THE SONG DIDN'T CHANGE. YOU DID.
                    </h2>

                    <p>
                        Ini mungkin bagian paling menariknya.
                    </p>

                    <p>
                        Sebuah lagu bisa memiliki arti yang berbeda di setiap fase kehidupan.
                    </p>

                    <p>
                        Lagu yang dulu membuatmu bahagia mungkin sekarang terasa bittersweet.
                        Lagu yang dulu kamu anggap biasa saja bisa menjadi lagu favorit setelah
                        dikaitkan dengan seseorang.
                        Dan lagu yang dulu membuatmu menangis mungkin suatu hari bisa terdengar
                        biasa saja.
                    </p>

                    <p>
                        Karena sebenarnya... lagunya tidak berubah.
                        Kitalah yang berubah.
                    </p>

                    <p>
                        Pengalaman baru, hubungan baru, dan fase kehidupan baru bisa mengubah cara
                        kita memaknai sebuah lagu.
                    </p>

                    <p>
                        Penelitian menemukan bahwa karakter emosional musik dapat memengaruhi
                        karakter emosional dari ingatan yang muncul.
                        Musik dengan nuansa tertentu cenderung memunculkan kenangan dengan karakter
                        emosi yang sejalan.
                    </p>

                    <p>
                        Jadi mungkin bukan lagunya yang semakin sedih.
                        Kita hanya sedang mendengarnya dari versi diri yang berbeda.
                    </p>


                    <h2>
                        WHY OLD SONGS HIT DIFFERENT
                    </h2>

                    <p>
                        Pernah tiba-tiba mendengar lagu dari tahun 2019 dan berpikir:
                    </p>


                    <div class="article-quote">

                        “Wow... I miss that era.”

                    </div>


                    <p>
                        Padahal kamu mungkin bahkan nggak sadar kalau kamu merindukan masa tersebut.
                    </p>

                    <p>
                        Musik bisa menjadi semacam <em>time capsule</em>.
                    </p>

                    <p>
                        Sebuah penelitian mengenai musik dan autobiographical memory menemukan bahwa
                        musik yang familiar berkaitan dengan peningkatan detail dan kekhususan ingatan,
                        terutama pada orang dewasa muda.
                    </p>

                    <p>
                        Itulah mengapa satu lagu bisa membuat kita mengingat warna kamar,
                        jalan yang pernah dilewati, orang yang pernah bersama kita,
                        outfit yang pernah dipakai, bahkan perasaan yang sudah lama terlupakan.
                    </p>

                    <p>
                        Bukan karena kita sengaja mengingatnya.
                        Lagu itu yang membuka pintunya.
                    </p>


                    <h2>
                        YOUR “THAT ONE SONG”
                    </h2>

                    <p>
                        Sekarang coba pikirkan.
                    </p>

                    <p>
                        Bukan lagu favoritmu.
                        Bukan lagu yang paling sering kamu dengarkan.
                    </p>

                    <p>
                        Tapi...
                    </p>

                    <p>
                        Lagu apa yang kalau intro-nya dimainkan sekarang,
                        kamu langsung terlempar ke satu momen tertentu?
                    </p>

                    <p>
                        Mungkin lagu yang mengingatkanmu pada <em>your first love</em>.
                        Atau masa sekolah.
                        Atau sahabat yang sekarang sudah jarang bertemu.
                        Atau masa ketika hidup terasa jauh lebih sederhana.
                    </p>

                    <p>
                        Itulah <em>your That One Song.</em>
                    </p>

                    <p>
                        Dan menariknya, setiap orang punya versi yang berbeda.
                    </p>


                    <h2>
                        MAYBE THAT'S WHY WE LOVE MUSIC.
                    </h2>

                    <p>
                        Kita mungkin lupa tanggal sebuah kejadian.
                        Lupa percakapan yang pernah terjadi.
                        Lupa detail kecil dari sebuah fase kehidupan.
                    </p>

                    <p>
                        Tapi terkadang...
                    </p>

                    <p>
                        cukup satu intro.
                        Satu melodi.
                        Satu lirik.
                    </p>

                    <p>
                        Dan semuanya terasa dekat lagi.
                    </p>

                    <p>
                        Mungkin itulah kekuatan musik.
                    </p>

                    <p>
                        Ia tidak hanya menyimpan suara.
                        Ia menyimpan versi diri kita yang pernah mendengarkannya.
                    </p>


                    <div class="article-quote">

                        <strong>
                            ONE LAST QUESTION
                        </strong>

                        <br><br>

                        What's your “That One Song”?

                        <br>

                        <em>
                            The song that takes you somewhere.
                        </em>

                    </div>

                </article>


                <!-- SHARE -->

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

        async function toggleSaveArticle() {

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

                const response = await fetch(
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

                    text.textContent = 'Tersimpan';

                } else {

                    button.classList.remove('saved');

                    icon.textContent = '♡';

                    text.textContent = 'Simpan';

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


        async function copyArticleLink() {

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


        async function shareArticle() {

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