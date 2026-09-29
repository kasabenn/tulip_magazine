<?php

session_start();

require_once '../config/database.php';

$articleId = 3;
$articleTheme = 'blue';

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

$articleTitle = 'Pameran Art for Peace and a Better Future Hadirkan 150 Karya di TIM';

$articleAuthor = 'Rawdiatul Jannah';

$articleImage = '../assets/images/art-for-peace.jpeg';

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
        'category' => 'Music',
        'title' => 'Kenapa Lagu-Lagu Lama Kembali Viral di Era Digital?',
        'image' => '../assets/images/old-songs-viral-1.jpg',
        'link' => 'artikel-06.php',
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

    <style>

        /*
        =========================================
        ARTICLE 03
        BLUE THEME
        =========================================
        */

        :root {
            --theme-1: #75BFE8;
            --theme-2: #A4D8F3;
            --theme-3: #CBEAF8;
            --theme-4: #EFF9FD;
            --theme-5: #ACDDF4;
            --theme-6: #4A9FD1;
            --theme-accent: #2584B8;
            --theme-dark: #17618D;
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
                rgba(117, 191, 232, .92);

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
                    rgba(117, 191, 232, .38)
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
    class="article-page theme-blue"
    style="
        --theme-1: #75BFE8;
        --theme-2: #A4D8F3;
        --theme-3: #CBEAF8;
        --theme-4: #EFF9FD;
        --theme-5: #ACDDF4;
        --theme-6: #4A9FD1;
        --theme-accent: #2584B8;
        --theme-dark: #17618D;
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
            href="../category.php?slug=art-photography"
            class="back-link"
        >
            ← Kembali ke ART & PHOTOGRAPHY
        </a>


        <span class="article-category">
            art & photography
        </span>


        <h1 class="article-title">

            <?php echo htmlspecialchars($articleTitle); ?>

        </h1>


        <p class="article-subtitle">
            Art for Peace and a Better Future: Collaboration
        </p>


        <p class="article-meta">

            Written by

            <strong>
                <?php echo htmlspecialchars($articleAuthor); ?>
            </strong>

            <br>

            Selasa, 18 Agustus 2026

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
                JAKARTA — SBY Art Community (SAC) menggelar pameran seni bertajuk
                <em>Art for Peace and a Better Future: Collaboration</em> di
                Taman Ismail Marzuki (TIM), Jakarta, pada 18–30 Agustus 2026.
                Pameran tersebut menghadirkan sekitar 150 karya dari 27 seniman
                Indonesia dan internasional dengan tema utama perdamaian serta masa depan.
            </p>


            <p>
                Pameran yang berlangsung di Galeri Emiria Soenassa dan Galeri Oesman Effendi
                tersebut menjadi bagian dari rangkaian kegiatan untuk memperingati HUT ke-81
                Kemerdekaan Republik Indonesia sekaligus HUT ke-499 DKI Jakarta.
                Selain menghadirkan karya dari seniman Indonesia, kegiatan ini juga melibatkan
                seniman asal Jerman, Christopher Lehmpfuhl.
            </p>


            <p>
                Salah satu hal yang menarik perhatian dalam pameran ini adalah keterlibatan
                Presiden ke-6 Republik Indonesia, Susilo Bambang Yudhoyono, sebagai salah satu
                seniman yang karyanya ditampilkan. Kehadiran karya tersebut menjadi bagian dari
                pameran yang mempertemukan seniman dari berbagai latar belakang dan generasi.
            </p>


            <p>
                Pameran dibuka secara resmi oleh Gubernur DKI Jakarta Pramono Anung pada
                Selasa, 18 Agustus 2026. Dalam pembukaan tersebut, Pramono turut memberikan
                goresan pada kanvas sebagai tanda dimulainya pameran.
            </p>


            <h2>
                MENGANGKAT PESAN PERDAMAIAN
            </h2>


            <p>
                Sesuai dengan judulnya, pameran ini membawa pesan mengenai perdamaian dan masa
                depan. Karya-karya yang ditampilkan tidak hanya menonjolkan aspek visual,
                tetapi juga menjadi media bagi para seniman untuk menyampaikan pandangan
                terhadap kondisi masyarakat dan dunia.
            </p>


            <p>
                Kementerian Ekonomi Kreatif menyebut pameran tersebut sebagai ruang bagi
                seniman untuk merespons berbagai dinamika global melalui karya seni.
                Menteri Ekonomi Kreatif/Kepala Badan Ekonomi Kreatif, Teuku Riefky Harsya,
                turut mengapresiasi penyelenggaraan tersebut.
            </p>


            <p>
                Menurut Kementerian Ekonomi Kreatif, kegiatan dalam rangkaian pameran juga
                mencakup workshop dan artist talk. Salah satu kegiatan tersebut menghadirkan
                Christopher Lehmpfuhl, seniman asal Berlin, untuk berbagi mengenai proses
                kreatif dan gagasan di balik karya seni.
            </p>


            <p>
                Kehadiran seniman dari luar negeri memberikan dimensi berbeda dalam pameran.
                Kolaborasi antara seniman Indonesia dan internasional menjadi salah satu bagian
                yang ditonjolkan oleh penyelenggara.
            </p>


            <div class="article-quote">
                SENI → PERDAMAIAN → KOLABORASI → MASA DEPAN
            </div>


            <h2>
                SENI DAN ISU LINGKUNGAN
            </h2>


            <p>
                Selain perdamaian, isu lingkungan juga menjadi salah satu perhatian dalam
                karya yang ditampilkan. Kementerian Ekonomi Kreatif dalam keterangannya pada
                2 September menyebut pameran tersebut sebagai ruang refleksi seniman dalam
                merespons berbagai kenyataan, termasuk persoalan bencana alam yang terjadi
                belakangan.
            </p>


            <p>
                Salah satu karya yang mendapat perhatian adalah
                <em>Greener World, No More Choice</em> karya Akbar Linggaprana.
                Karya berukuran 200 x 300 sentimeter tersebut menggunakan medium akrilik
                di atas kanvas dan menghadirkan lanskap surealis yang memadukan unsur
                hutan dan lautan.
            </p>


            <p>
                Karya tersebut menggunakan gambaran lingkungan dan ekosistem sebagai bagian
                dari pesan mengenai kondisi alam. Pendekatan seperti ini menunjukkan bagaimana
                seniman dapat menggunakan visual sebagai cara untuk membicarakan isu yang dekat
                dengan kehidupan masyarakat.
            </p>


            <p>
                Pameran kemudian menjadi ruang pertemuan antara karya seni dan isu sosial.
                Pengunjung tidak hanya melihat lukisan dari sisi estetika, tetapi juga dapat
                membaca pesan yang ingin disampaikan melalui objek, warna, komposisi, dan tema
                yang digunakan.
            </p>


            <h2>
                KOLABORASI LINTAS GENERASI
            </h2>


            <p>
                Salah satu karakteristik utama pameran ini adalah keberagaman seniman yang
                terlibat. Sebanyak 27 seniman berpartisipasi dengan menghadirkan karya mereka.
                Selain seniman Indonesia, terdapat pula seniman tamu dari Jerman serta
                keterlibatan seniman dari berbagai generasi.
            </p>


            <p>
                Konsep kolaborasi tersebut memberikan kesempatan bagi seniman dengan latar
                belakang berbeda untuk berada dalam satu ruang pamer. Pengunjung juga dapat
                melihat perbedaan pendekatan visual yang digunakan masing-masing seniman
                dalam menyampaikan gagasan.
            </p>


            <p>
                Bagi perkembangan ekosistem seni, pertemuan semacam ini dapat menjadi ruang
                pertukaran gagasan antara seniman, komunitas, kurator, dan masyarakat.
                Kegiatan artist talk dan workshop yang menjadi bagian dari rangkaian acara
                juga memberikan kesempatan kepada publik untuk mengenal proses kreatif
                di balik sebuah karya.
            </p>


            <p>
                Pameran <em>Art for Peace and a Better Future: Collaboration</em> akhirnya
                tidak hanya menjadi tempat untuk melihat karya seni, tetapi juga ruang dialog
                mengenai perdamaian, lingkungan, dan masa depan.
            </p>


            <p>
                Dengan melibatkan puluhan seniman dan menghadirkan sekitar 150 karya,
                kegiatan ini memperlihatkan bagaimana seni dapat digunakan sebagai medium
                untuk merespons berbagai persoalan yang berkembang di masyarakat.
            </p>


            <p>
                Pameran tersebut berlangsung selama 13 hari di Taman Ismail Marzuki dan
                berakhir pada 30 Agustus 2026.
            </p>


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