<?php

session_start();

require_once '../config/database.php';

$articleId = 7;
$articleTheme = 'purple';

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
        mysqli_stmt_bind_param($stmt, 'ii', $userId, $articleId);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $isSaved = $result && mysqli_num_rows($result) > 0;

        mysqli_stmt_close($stmt);
    }
}

$articleTitle = 'Nico Robin: Membaca Kebenaran, Merayakan Perlawanan';
$articleAuthor = 'Mutya Naffisa Kusziana';
$articleImage = '../assets/images/nico-robin.jpg';

$relatedArticles = [
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
        'image' => '../assets/images/old-songs-viral-1.jpg',
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($articleTitle); ?> — TULIP MAGAZINE
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

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
            --purple-1: #CD68EC;
            --purple-2: #E39AF5;
            --purple-3: #F3CDFD;
            --purple-4: #FAF0FE;
            --purple-5: #E8A8F8;
            --purple-6: #BD4BE5;
            --purple-accent: #B83CE0;

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
                var(--purple-1) 0%,
                var(--purple-2) 9%,
                var(--purple-3) 28%,
                var(--purple-4) 58%,
                var(--purple-5) 83%,
                var(--purple-6) 100%
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
           HEADER & NAV EXPAND
        ========================= */

        .site-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(17, 17, 17, .18);
            background: rgba(205, 104, 236, .94);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            transition: all 0.3s ease;
        }

        .nav {
            width: min(var(--page-width), calc(100% - 48px));
            height: 64px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: inherit;
        }

        .brand img {
            width: auto;
            height: 38px;
            object-fit: contain;
        }

        .brand-text {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -1px;
            display: inline-block;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-button {
            border: 0;
            background: transparent;
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #111;
        }

        .nav-button:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: scale(1.08);
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: block;
            stroke: #111;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: transform 0.2s ease;
        }

        /* HEADER QUICK SEARCH BAR */
        .header-search-bar {
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            border-top: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            padding: 12px 24px;
            display: none;
            animation: slideDownSearch 0.25s ease forwards;
        }

        .header-search-bar.active {
            display: block;
        }

        @keyframes slideDownSearch {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .search-bar-form {
            width: min(var(--page-width), 100%);
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-bar-form input {
            flex: 1;
            padding: 10px 18px;
            border-radius: 999px;
            border: 1px solid #111;
            font-size: 13px;
            outline: none;
            background: #fff;
        }

        .search-bar-form input:focus {
            box-shadow: 0 0 0 3px rgba(189, 75, 229, 0.3);
        }

        .search-submit-btn {
            padding: 10px 22px;
            border-radius: 999px;
            background: #111;
            color: #fff;
            border: 0;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .search-submit-btn:hover {
            background: #333;
        }

        .search-close-btn {
            background: transparent;
            border: 0;
            font-size: 18px;
            cursor: pointer;
            padding: 4px 8px;
            font-weight: 700;
            color: #555;
        }

        .search-close-btn:hover {
            color: #111;
        }

        /* COLLAPSIBLE NAVBAR MENU EXPAND */
        .navbar-menu-expand {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
            opacity: 0;
            background: linear-gradient(180deg, rgba(205, 104, 236, 0.98) 0%, rgba(227, 154, 245, 0.98) 100%);
            border-bottom: 1px solid rgba(17, 17, 17, 0.2);
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.12);
        }

        .navbar-menu-expand.active {
            max-height: 600px;
            opacity: 1;
        }

        .menu-expand-inner {
            width: min(var(--page-width), calc(100% - 48px));
            margin: 0 auto;
            padding: 26px 0 32px;
        }

        .menu-links-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .menu-link-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(17, 17, 17, 0.12);
            border-radius: 12px;
            text-decoration: none;
            color: #111;
            font-weight: 700;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .menu-link-item:hover,
        .menu-link-item.active {
            background: #111;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .menu-link-item:hover .menu-num,
        .menu-link-item.active .menu-num {
            color: #F3CDFD;
        }

        .menu-num {
            font-size: 11px;
            font-weight: 800;
            color: #7E239A;
        }

        .menu-bottom-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 18px;
            border-top: 1px solid rgba(17, 17, 17, 0.15);
            flex-wrap: wrap;
            gap: 14px;
        }

        .menu-auth-status {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
        }

        .menu-pill-btn {
            padding: 6px 16px;
            border-radius: 999px;
            background: #111;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .menu-pill-btn:hover {
            background: #333;
        }

        .menu-quick-actions {
            display: flex;
            gap: 10px;
        }

        .menu-quick-btn {
            font-size: 12px;
            font-weight: 700;
            color: #111;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.4);
            transition: 0.2s ease;
        }

        .menu-quick-btn:hover {
            background: rgba(255, 255, 255, 0.85);
        }

        /* =========================
           ARTICLE
        ========================= */

        .article-shell {
            width: min(var(--page-width), calc(100% - 48px));
            margin: 0 auto;
            padding: 58px 0 70px;
        }

        .article-top {
            width: min(var(--article-width), 100%);
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

            color: #7E239A;

            font-size: 10px;
            line-height: 1;
            font-weight: 700;

            text-transform: lowercase;
            letter-spacing: .3px;
        }

        .article-title {
            max-width: 700px;
            margin-bottom: 13px;

            font-size: clamp(32px, 5vw, 54px);
            line-height: 1.02;
            letter-spacing: -2.2px;
            font-weight: 800;
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

        .article-checklist {
            margin: 20px 0;
            padding: 15px 18px;

            border: 1px solid #111;

            background: rgba(255, 255, 255, .35);
        }

        .article-checklist h3 {
            margin-bottom: 8px;
            font-size: 12px;
        }

        .article-checklist li {
            margin: 4px 0;

            list-style: none;

            font-size: 11px;
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
            width: min(var(--page-width), calc(100% - 48px));

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

            color: #7E239A;

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
                rgba(205, 104, 236, .38)
            );
        }

        /* =========================
           FOOTER
        ========================= */

        .site-footer {
            border-top: 1px solid rgba(17, 17, 17, 0.25);
            background: #CD68EC;
            color: #111;
            margin-top: 50px;
        }

        .footer-inner {
            width: min(var(--page-width), calc(100% - 48px));
            margin: 0 auto;
            padding: 56px 0 32px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr 0.8fr 1fr 1.3fr;
            gap: 40px;
        }

        .footer-brand-col {
            display: flex;
            flex-direction: column;
        }

        .footer-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
            text-decoration: none;
            color: inherit;
        }

        .footer-logo-img {
            width: 38px;
            height: 38px;
            object-fit: contain;
        }

        .footer-logo-text {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .footer-desc {
            max-width: 290px;
            font-size: 13px;
            line-height: 1.6;
            color: rgba(17, 17, 17, 0.85);
        }

        .footer-col h4 {
            margin-bottom: 18px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #111;
        }

        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-col a {
            font-size: 13px;
            font-weight: 600;
            color: #222;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-block;
        }

        .footer-col a:hover {
            color: #000;
            transform: translateX(3px);
            text-decoration: underline;
        }

        .newsletter-text {
            margin-bottom: 14px;
            font-size: 13px;
            line-height: 1.5;
            color: rgba(17, 17, 17, 0.85);
        }

        /* PILL SHAPED NEWSLETTER BOX (matching Screenshot 2) */
        .newsletter-pill {
            background: #ffffff;
            border: 1.5px solid #111;
            border-radius: 999px;
            padding: 4px 5px 4px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .newsletter-pill:focus-within {
            box-shadow: 0 0 0 3px rgba(189, 75, 229, 0.35);
        }

        .newsletter-pill input {
            flex: 1;
            min-width: 0;
            border: 0;
            outline: 0;
            background: transparent;
            font-size: 13px;
            font-weight: 500;
            color: #111;
            padding: 6px 0;
        }

        .newsletter-pill input::placeholder {
            color: #888;
        }

        .newsletter-pill button {
            border: 0;
            background: #111;
            color: #fff;
            padding: 10px 20px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.6px;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .newsletter-pill button:hover {
            background: #2a2a2a;
            transform: scale(1.02);
        }

        .newsletter-pill button.subscribed {
            background: #27ae60;
        }

        .copyright {
            margin-top: 48px;
            padding-top: 20px;
            border-top: 1px solid rgba(17, 17, 17, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            font-size: 11px;
            font-weight: 600;
            color: rgba(17, 17, 17, 0.85);
            flex-wrap: wrap;
        }

        .copyright-links {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .copyright-links a {
            color: #111;
            text-decoration: none;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: 0.2s ease;
        }

        .copyright-links a:hover {
            text-decoration: underline;
        }

        .copyright-links .sep {
            color: rgba(17, 17, 17, 0.4);
        }

        /* MODAL STYLES */
        .site-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(6px);
            z-index: 2000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .site-modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .site-modal-box {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -46%) scale(0.95);
            width: min(640px, calc(100% - 32px));
            max-height: 85vh;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.2);
            z-index: 2001;
            opacity: 0;
            pointer-events: none;
            overflow-y: auto;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
        }

        .site-modal-box.active {
            opacity: 1;
            pointer-events: auto;
            transform: translate(-50%, -50%) scale(1);
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 2;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 800;
            color: #111;
        }

        .modal-close-btn {
            background: #f1f2f6;
            border: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            color: #555;
            transition: 0.2s ease;
        }

        .modal-close-btn:hover {
            background: #111;
            color: #fff;
        }

        .modal-body {
            padding: 24px;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
        }

        .modal-body h4 {
            font-size: 15px;
            font-weight: 700;
            margin: 16px 0 6px;
            color: #111;
        }

        .modal-body h4:first-child {
            margin-top: 0;
        }

        .modal-body p {
            margin-bottom: 12px;
        }

        /* TOAST NOTIFICATION */
        .toast-notification {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #111111;
            color: #ffffff;
            padding: 14px 22px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            z-index: 3000;
            opacity: 0;
            transform: translateY(12px);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toast-notification.active {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 850px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 30px;
            }
        }

        @media (max-width: 550px) {
            .footer-grid {
                grid-template-columns: 1fr;
            }
            .copyright {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
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
                grid-template-columns: 1.2fr 1fr 1fr;
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

<body class="article-page theme-purple">

    <!-- =========================
         HEADER
    ========================= -->

    <header class="site-header">
        <nav class="nav">

            <a href="../index.php" class="brand" title="Ke Beranda Tulip Magazine">
                <img
                    src="../assets/images/logo-tulip.png"
                    alt="TULIP."
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                >
                <span class="brand-text">TULIP.</span>
            </a>

            <div class="nav-actions">

                <button
                    type="button"
                    class="nav-button"
                    aria-label="Cari artikel"
                    title="Cari Artikel"
                    onclick="toggleSearchBar()"
                >
                    <svg class="nav-icon" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="6"></circle>
                        <path d="M16 16l5 5"></path>
                    </svg>
                </button>

                <button
                    type="button"
                    class="nav-button"
                    aria-label="Artikel tersimpan"
                    title="Artikel Tersimpan"
                    onclick="window.location.href='../saved.php'"
                >
                    <svg class="nav-icon" viewBox="0 0 24 24">
                        <path d="M6 4.5A2.5 2.5 0 0 1 8.5 2h7A2.5 2.5 0 0 1 18 4.5V21l-6-3.7L6 21V4.5z"></path>
                    </svg>
                </button>

                <button
                    type="button"
                    class="nav-button"
                    aria-label="Akun"
                    title="<?php echo $userId > 0 ? 'Profil (' . htmlspecialchars($_SESSION['username'] ?? 'User') . ')' : 'Masuk / Login'; ?>"
                    onclick="window.location.href='<?php echo $userId > 0 ? '../profile.php' : '../auth/login.php'; ?>'"
                >
                    <svg class="nav-icon" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="3.5"></circle>
                        <path d="M5 21c.7-4 3-6 7-6s6.3 2 7 6"></path>
                    </svg>
                </button>

                <button
                    type="button"
                    class="nav-button"
                    id="menuToggleBtn"
                    aria-label="Toggle Menu Navigasi"
                    title="Menu Navigasi"
                    onclick="toggleNavbarMenu()"
                >
                    <svg class="nav-icon" id="menuIconSvg" viewBox="0 0 24 24">
                        <path d="M4 7h16M4 12h16M4 17h16"></path>
                    </svg>
                </button>

            </div>
        </nav>

        <!-- QUICK SEARCH BAR -->
        <div class="header-search-bar" id="headerSearchBar">
            <form action="../search.php" method="GET" class="search-bar-form">
                <input type="text" name="q" placeholder="Cari artikel, topik budaya, musik, film..." required>
                <button type="submit" class="search-submit-btn">Cari</button>
                <button type="button" class="search-close-btn" onclick="toggleSearchBar()" aria-label="Tutup pencarian">✕</button>
            </form>
        </div>

        <!-- COLLAPSIBLE NAVBAR MENU EXPAND -->
        <div class="navbar-menu-expand" id="navbarMenuExpand">
            <div class="menu-expand-inner">
                <div class="menu-links-grid">
                    <a href="../index.php" class="menu-link-item">
                        <span class="menu-num">01</span>
                        <span class="menu-name">Beranda</span>
                    </a>
                    <a href="../category.php?slug=fashion-beauty" class="menu-link-item">
                        <span class="menu-num">02</span>
                        <span class="menu-name">Fashion &amp; Beauty</span>
                    </a>
                    <a href="../category.php?slug=art-photography" class="menu-link-item">
                        <span class="menu-num">03</span>
                        <span class="menu-name">Art &amp; Photography</span>
                    </a>
                    <a href="../category.php?slug=music" class="menu-link-item">
                        <span class="menu-num">04</span>
                        <span class="menu-name">Music</span>
                    </a>
                    <a href="../category.php?slug=film-tv" class="menu-link-item active">
                        <span class="menu-num">05</span>
                        <span class="menu-name">Film &amp; TV</span>
                    </a>
                    <a href="../category.php?slug=life-culture" class="menu-link-item">
                        <span class="menu-num">06</span>
                        <span class="menu-name">Life &amp; Culture</span>
                    </a>
                </div>
                <div class="menu-bottom-row">
                    <div class="menu-auth-status">
                        <?php if ($userId > 0): ?>
                            <span>Halo, <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></strong></span>
                            <a href="../profile.php" class="menu-pill-btn">Profil Saya →</a>
                            <a href="../auth/logout.php" class="menu-pill-btn" style="background:rgba(255,255,255,0.4); color:#b71540;">Keluar</a>
                        <?php else: ?>
                            <span>Bergabung dengan TULIP:</span>
                            <a href="../auth/login.php" class="menu-pill-btn">Masuk / Daftar Akun →</a>
                        <?php endif; ?>
                    </div>
                    <div class="menu-quick-actions">
                        <a href="../saved.php" class="menu-quick-btn">🔖 Artikel Tersimpan</a>
                        <a href="../search.php" class="menu-quick-btn">🔍 Cari Cerita</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>

        <!-- =========================
             ARTICLE
        ========================= -->

        <section class="article-shell">

            <div class="article-top">

                <a
                    href="../category.php?slug=film-tv"
                    class="back-link"
                >
                    ← Kembali ke FILM &amp; TV
                </a>

                <span class="article-category">
                    film &amp; tv
                </span>

                <h1 class="article-title">
                    <?php echo htmlspecialchars($articleTitle); ?>
                </h1>

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
                        <span class="save-icon" id="saveIcon">
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
                        Dalam sejarah narasi fiksi—termasuk dunia anime dan manga mainstream—karakter wanita sering kali hanya ditempatkan di pinggiran. Peran mereka tak jarang sebatas pemanis layar, pelengkap estetika, atau objek yang harus diselamatkan demi menonjolkan superioritas sang pahlawan utama.
                    </p>

                    <p>
                        Namun, megakarya <em>One Piece</em> buatan Eiichiro Oda mendobrak stigmata tersebut. Di tengah petualangan mengarungi lautan yang penuh aksi keras, Oda menghadirkan salah satu penulisan karakter perempuan terbaik dalam industri media modern: Nico Robin.
                    </p>

                    <p>
                        Robin hadir bukan sekadar untuk memenuhi kuota anggota kelompok Bajak Laut Topi Jerami. Ia adalah sosok cendekiawan mandiri yang sejak kecil dipaksa bertahan hidup di tengah dunia yang menganggap kecerdasannya sebagai sebuah ancaman nasional.
                    </p>

                    <h2>
                        Pengetahuan sebagai Senjata Utama
                    </h2>

                    <p>
                        Robin lahir di Ohara, sebuah pulau yang dihuni oleh para arkeolog dan ilmuwan terkemuka. Keahlian langkanya dalam membaca Poneglyph—prasasti batu kuno yang menyimpan rahasia kelam sejarah dunia—membuat Pemerintah Dunia ketakutan.
                    </p>

                    <p>
                        Di usianya yang baru 8 tahun, ia sudah dilabeli sebagai “Demon Child” (Anak Iblis) dan dijatuhi nilai buronan fantastis.
                    </p>

                    <p>
                        Melalui arc Ohara, <em>One Piece</em> memperlihatkan kritik sosial yang tajam: bagaimana kecerdasan dan independensi seorang perempuan sering kali ditakuti oleh sistem kekuasaan yang dominan.
                    </p>

                    <p>
                        Robin membuktikan bahwa kekuatan terbesar seorang wanita tidak selalu terletak pada ketangkasan fisik belaka, melainkan pada ketajaman pikiran, wawasan, dan literasi.
                    </p>

                    <h2>
                        Merayakan Kerentanan: “Aku Ingin Hidup!”
                    </h2>

                    <p>
                        Setelah puluhan tahun hidup dalam mode bertahan hidup—berpindah dari satu kelompok ke kelompok lain dan terus dihantui rasa bersalah—titik balik terbesarnya terjadi di arc Enies Lobby.
                    </p>

                    <p>
                        Di hadapan rekan-rekannya yang rela menantang dunia demi membela dirinya, Robin akhirnya melepaskan stigma bahwa dirinya adalah “beban yang tak layak hidup”.
                    </p>

                    <div class="article-quote">
                        “Aku ingin hidup!”
                    </div>

                    <p>
                        Teriakan ikoniknya menjadi salah satu momen paling emosional dalam sejarah <em>One Piece</em>. Momen ini mengajarkan bahwa mengakui vulnerability (kerentanan) dan berani menyuarakan apa yang kita inginkan bukanlah tanda kelemahan, melainkan bentuk keberanian tertinggi seorang wanita.
                    </p>

                    <h2>
                        Light of the Revolution
                    </h2>

                    <p>
                        Tidak berhenti pada penyelamatan dirinya, Robin terus bertransformasi. Selama dua tahun masa latihan (timeskip), ia menjalin hubungan erat dengan Pasukan Revolusi pimpinan Monkey D. Dragon hingga dijuluki sebagai “Light of the Revolution” (Cahaya Revolusi).
                    </p>

                    <p>
                        Ia menjadi jembatan informasi dan simbol harapan bagi perlawanan terhadap ketidakadilan dunia.
                    </p>

                    <p>
                        Robin menunjukkan bahwa seorang perempuan bisa menjadi penggerak perubahan (agent of change) tanpa harus kehilangan keanggunan, ketenangan, dan rasa empatinya.
                    </p>

                    <h2>
                        Simbol Emansipasi dan Pembelajaran Hidup
                    </h2>

                    <p>
                        Nico Robin adalah bukti nyata bahwa seberat apa pun stigma dan trauma masa lalu yang dilekatkan lingkungan pada seorang wanita, ia selalu memiliki hak penuh untuk mendefinisikan ulang nilai (self-worth) serta jalan hidupnya sendiri.
                    </p>

                    <p>
                        Dari perjalanan Nico Robin di semesta <em>One Piece</em>, kita bisa memetik pelajaran berharga:
                    </p>

                    <div class="article-quote">
                        “Pendidikan, ketangguhan mental, dan keberanian untuk memeluk luka masa lalu adalah bentuk revolusi terbesar yang bisa dilakukan oleh seorang perempuan.”
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
                            title="Salin Tautan Artikel"
                        >
                            Salin Tautan
                        </button>

                        <button
                            type="button"
                            class="share-button"
                            onclick="openShareModal()"
                            title="Bagikan ke Media Sosial"
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

                <div class="footer-brand-col">

                    <a
                        href="../index.php"
                        class="footer-logo"
                        title="Kembali ke Beranda TULIP MAGAZINE"
                    >
                        <img
                            src="../assets/images/logo-tulip.png"
                            alt="TULIP Logo"
                            class="footer-logo-img"
                            onerror="this.style.display='none';"
                        >
                        <span class="footer-logo-text">TULIP.</span>
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
                        <li>
                            <a href="javascript:void(0)" onclick="openModal('about')">
                                About
                            </a>
                        </li>

                        <li>
                            <a href="javascript:void(0)" onclick="openModal('masthead')">
                                Masthead
                            </a>
                        </li>

                        <li>
                            <a href="javascript:void(0)" onclick="openModal('contact')">
                                Contact
                            </a>
                        </li>

                        <li>
                            <a href="javascript:void(0)" onclick="openModal('advertise')">
                                Advertise
                            </a>
                        </li>

                        <li>
                            <a href="javascript:void(0)" onclick="openModal('careers')">
                                Careers
                            </a>
                        </li>
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

                <div class="footer-col footer-newsletter-col">

                    <h4>
                        STAY IN THE LOOP
                    </h4>

                    <p class="newsletter-text">
                        Get the latest stories delivered to your inbox.
                    </p>

                    <form
                        class="newsletter-pill"
                        id="newsletterForm"
                        onsubmit="handleNewsletterSubmit(event)"
                    >

                        <input
                            type="email"
                            id="newsletterEmail"
                            placeholder="your@email.com"
                            aria-label="Email newsletter"
                            required
                        >

                        <button type="submit" id="newsletterBtn">
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

                <div class="copyright-links">
                    <a href="javascript:void(0)" onclick="openModal('privacy')">
                        PRIVACY POLICY
                    </a>
                    <span class="sep">&nbsp;&bull;&nbsp;</span>
                    <a href="javascript:void(0)" onclick="openModal('terms')">
                        TERMS OF USE
                    </a>
                    <span class="sep">&nbsp;&bull;&nbsp;</span>
                    <a href="javascript:void(0)" onclick="openModal('cookies')">
                        COOKIE PREFERENCES
                    </a>
                </div>

            </div>

        </div>

    </footer>

    <!-- =========================
         UNIFIED MODAL SYSTEM
    ========================= -->
    <div id="siteModalOverlay" class="site-modal-overlay" onclick="closeModal()"></div>
    <div id="siteModalBox" class="site-modal-box">
        <div class="modal-header">
            <h3 id="modalTitle" class="modal-title">Modal Title</h3>
            <button type="button" class="modal-close-btn" onclick="closeModal()" aria-label="Tutup">✕</button>
        </div>
        <div id="modalBody" class="modal-body">
            <!-- Dynamic Content -->
        </div>
    </div>

    <!-- =========================
         TOAST NOTIFICATION
    ========================= -->
    <div id="toastNotification" class="toast-notification"></div>

    <!-- =========================
         JAVASCRIPT
    ========================= -->

    <script>
        // Toggle Slide-down Navbar Menu
        function toggleNavbarMenu() {
            const menuExpand = document.getElementById('navbarMenuExpand');
            const menuIcon = document.getElementById('menuIconSvg');
            if (!menuExpand) return;

            menuExpand.classList.toggle('active');
            const isOpen = menuExpand.classList.contains('active');

            if (isOpen) {
                menuIcon.innerHTML = '<path d="M6 18L18 6M6 6l12 12"></path>';
                const searchBar = document.getElementById('headerSearchBar');
                if (searchBar) searchBar.classList.remove('active');
            } else {
                menuIcon.innerHTML = '<path d="M4 7h16M4 12h16M4 17h16"></path>';
            }
        }

        // Toggle Quick Search Bar
        function toggleSearchBar() {
            const searchBar = document.getElementById('headerSearchBar');
            if (!searchBar) return;
            searchBar.classList.toggle('active');
            if (searchBar.classList.contains('active')) {
                const input = searchBar.querySelector('input');
                if (input) setTimeout(() => input.focus(), 100);
                const menuExpand = document.getElementById('navbarMenuExpand');
                if (menuExpand && menuExpand.classList.contains('active')) {
                    toggleNavbarMenu();
                }
            }
        }

        // Toast notification
        let toastTimer = null;
        function showToast(msg) {
            const toast = document.getElementById('toastNotification');
            if (!toast) return;
            toast.textContent = msg;
            toast.classList.add('active');
            if (toastTimer) clearTimeout(toastTimer);
            toastTimer = setTimeout(() => {
                toast.classList.remove('active');
            }, 3200);
        }

        // Unified Modal Contents
        const modalContents = {
            about: {
                title: "Tentang TULIP MAGAZINE",
                content: `
                    <p><strong>TULIP MAGAZINE</strong> adalah publikasi independen terdepan yang didedikasikan untuk generasi baru. Tempat kamu menemukan cerita, kurasi estetika, gaya hidup, dan refleksi budaya yang autentik setiap hari.</p>
                    <h4>Visi Redaksi</h4>
                    <p>Kami percaya bahwa jurnalisme gaya hidup tidak seharusnya dangkal. Dari dekonstruksi fesyen kontemporer, liputan pameran seni, penjelajahan musik lintas genre, hingga kritik sinema yang tajam, TULIP menyajikan perspektif yang segar, cerdas, dan bermakna.</p>
                    <h4>Nilai Kami</h4>
                    <p>&bull; <strong>Autentisitas:</strong> Menolak klise dan tren sesaat.<br>
                    &bull; <strong>Estetika:</strong> Setiap cerita dirancang dengan kurasi visual tinggi.<br>
                    &bull; <strong>Kritis &amp; Inklusif:</strong> Merayakan keberagaman suara anak muda di seluruh nusantara.</p>
                `
            },
            masthead: {
                title: "Dewan Redaksi & Tim Kreatif",
                content: `
                    <h4>Pimpinan Redaksi (Editor-in-Chief)</h4>
                    <p><strong>Mutya Naffisa Kusziana</strong> — Menulis topik Sinema, TV, &amp; Kritik Media</p>
                    <h4>Redaktur Pelaksana (Managing Editor)</h4>
                    <p><strong>Farah Amelia</strong> — Memimpin liputan Fashion &amp; Beauty</p>
                    <h4>Pengarah Artistik (Art Director)</h4>
                    <p><strong>Sabrina Enejelika</strong> — Desain Visual &amp; Rubrik Life &amp; Culture</p>
                    <h4>Redaktur Rubrik &amp; Penulis Senior</h4>
                    <p><strong>Nadia Rahma</strong> — Kolumnis Musik &amp; Kurasi Audio<br>
                    <strong>Rawdiatul Jannah</strong> — Seni Visual &amp; Fotografi Kontemporer</p>
                    <h4>Penerbit</h4>
                    <p>&copy; 2026 TULIP Media Nusantara Corp.</p>
                `
            },
            contact: {
                title: "Hubungi Redaksi TULIP",
                content: `
                    <p>Punya gagasan liputan, siaran pers, kritik, atau saran untuk redaksi kami? Kirimkan pesanmu melalui formulir di bawah ini atau hubungi langsung kontak kami.</p>
                    <div style="background:#FAF0FE; border:1px solid #E39AF5; padding:12px 16px; border-radius:12px; margin-bottom:16px; font-size:13px;">
                        <strong>Email Redaksi:</strong> editorial@tulipmagazine.com<br>
                        <strong>Kerjasama &amp; Bisnis:</strong> partner@tulipmagazine.com<br>
                        <strong>Studio:</strong> TULIP Creative Studio, Jl. Senopati No. 45, Jakarta Selatan
                    </div>
                    <form onsubmit="handleContactSubmit(event)" style="display:flex; flex-direction:column; gap:10px;">
                        <input type="text" placeholder="Nama Lengkap" required style="padding:10px 14px; border:1px solid #ccc; border-radius:8px; font-size:13px;">
                        <input type="email" placeholder="Alamat Email" required style="padding:10px 14px; border:1px solid #ccc; border-radius:8px; font-size:13px;">
                        <textarea placeholder="Tulis pesan atau masukanmu..." rows="3" required style="padding:10px 14px; border:1px solid #ccc; border-radius:8px; font-size:13px; font-family:inherit;"></textarea>
                        <button type="submit" style="padding:10px 20px; background:#111; color:#fff; border:0; border-radius:8px; font-weight:700; cursor:pointer; font-size:13px;">Kirim Pesan</button>
                    </form>
                `
            },
            advertise: {
                title: "Pasang Iklan & Kemitraan Media",
                content: `
                    <p>Jangkau ratusan ribu pembaca Gen Z dan milenial kreatif yang peduli pada estetika, gaya hidup berkelanjutan, dan budaya pop.</p>
                    <h4>Peluang Kolaborasi</h4>
                    <p>&bull; <strong>Sponsored Articles &amp; Native Storytelling:</strong> Narasi organik yang menyatu dengan estetika TULIP.<br>
                    &bull; <strong>Digital Display &amp; Homepage Takeover:</strong> Penempatan visual premium di beranda dan halaman kategori.<br>
                    &bull; <strong>Newsletter Spotlight:</strong> Sorotan langsung ke ribuan kotak masuk pelanggan setia.<br>
                    &bull; <strong>Creative Campaign &amp; Event Media Partner:</strong> Dukungan penuh publikasi acara seni &amp; fesyen.</p>
                    <p>Minta Media Kit &amp; Rate Card lengkap di: <strong>advertise@tulipmagazine.com</strong></p>
                    <a href="mailto:advertise@tulipmagazine.com?subject=Inquiry%20Kerjasama%20Iklan%20TULIP" style="display:inline-block; margin-top:8px; padding:10px 20px; background:#111; color:#fff; border-radius:8px; text-decoration:none; font-weight:700; font-size:13px;">Hubungi Tim Kemitraan →</a>
                `
            },
            careers: {
                title: "Karir & Bergabung dengan TULIP",
                content: `
                    <p>Kami selalu membuka pintu bagi penulis lepas, fotografer muda, ilustrator, dan pengamat budaya yang memiliki kecintaan mendalam pada jurnalisme visual.</p>
                    <h4>Posisi Terbuka</h4>
                    <p>&bull; <strong>Contributing Culture Writer (Freelance):</strong> Menulis esai reflektif seputar film, musik, dan fenomena digital.<br>
                    &bull; <strong>Editorial Graphic &amp; UI Intern:</strong> Merancang aset visual dan tata letak artikel majalah digital.<br>
                    &bull; <strong>Social Media &amp; Content Strategist:</strong> Mengelola distribusi konten di Instagram, TikTok, dan X.</p>
                    <p>Kirimkan CV, portofolio karya, dan 2 ide tulisan ke: <strong>career@tulipmagazine.com</strong> dengan subjek <em>[Posisi] - Nama Kamu</em>.</p>
                `
            },
            privacy: {
                title: "Kebijakan Privasi (Privacy Policy)",
                content: `
                    <p>Privasi Anda adalah prioritas utama di TULIP MAGAZINE. Dokumen ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda.</p>
                    <h4>1. Data yang Dikumpulkan</h4>
                    <p>Kami hanya mengumpulkan data yang Anda berikan secara sukarela, seperti alamat email saat berlangganan newsletter atau data akun (nama pengguna &amp; email) saat membuat akun.</p>
                    <h4>2. Penggunaan Data</h4>
                    <p>Data digunakan semata-mata untuk mengelola akun Anda, menyimpan artikel favorit, serta mengirimkan kurasi newsletter mingguan jika Anda berlangganan.</p>
                    <h4>3. Keamanan Data</h4>
                    <p>Kami tidak pernah menjual, menyewakan, atau membagikan data pribadi Anda kepada pihak ketiga manapun untuk tujuan komersial.</p>
                `
            },
            terms: {
                title: "Syarat & Ketentuan (Terms of Use)",
                content: `
                    <h4>1. Hak Cipta &amp; Kepemilikan Konten</h4>
                    <p>Seluruh materi artikel, teks orisinal, tata letak grafis, dan identitas merek di TULIP MAGAZINE dilindungi oleh Undang-Undang Hak Cipta Republik Indonesia. Dilarang mereproduksi tulisan secara utuh tanpa mencantumkan tautan sumber dan izin tertulis dari redaksi.</p>
                    <h4>2. Perilaku Pengguna</h4>
                    <p>Pengguna dilarang menyalahgunakan fitur komentar, formulir kontak, atau mencoba merusak integritas sistem web TULIP MAGAZINE.</p>
                    <h4>3. Ketentuan Pembaruan</h4>
                    <p>Redaksi TULIP berhak memperbarui syarat penggunaan ini sewaktu-waktu demi menjaga kenyamanan bersama.</p>
                `
            },
            cookies: {
                title: "Pengaturan Cookie (Cookie Preferences)",
                content: `
                    <p>Kami menggunakan cookie untuk memastikan Anda mendapatkan pengalaman membaca terbaik dan menyimpan preferensi tema Anda.</p>
                    <div style="display:flex; flex-direction:column; gap:14px; margin:16px 0;">
                        <div style="display:flex; justify-content:space-between; align-items:center; padding:12px; background:#f9f9f9; border-radius:10px;">
                            <div>
                                <strong>Cookie Esensial</strong>
                                <div style="font-size:12px; color:#666;">Wajib agar website dan sesi login berfungsi normal.</div>
                            </div>
                            <span style="font-size:12px; font-weight:700; color:#27ae60;">Selalu Aktif</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; padding:12px; background:#f9f9f9; border-radius:10px;">
                            <div>
                                <strong>Cookie Analisis &amp; Kinerja</strong>
                                <div style="font-size:12px; color:#666;">Membantu kami mengetahui artikel mana yang paling disukai pembaca.</div>
                            </div>
                            <label style="cursor:pointer;"><input type="checkbox" id="cookieAnalytics" checked style="width:18px; height:18px;"></label>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; padding:12px; background:#f9f9f9; border-radius:10px;">
                            <div>
                                <strong>Cookie Personalisasi</strong>
                                <div style="font-size:12px; color:#666;">Mengingat preferensi bacaan dan rekomendasi artikel serupa.</div>
                            </div>
                            <label style="cursor:pointer;"><input type="checkbox" id="cookiePersonalization" checked style="width:18px; height:18px;"></label>
                        </div>
                    </div>
                    <button type="button" onclick="saveCookiePreferences()" style="width:100%; padding:12px; background:#111; color:#fff; border:0; border-radius:10px; font-weight:700; cursor:pointer;">Simpan Preferensi</button>
                `
            },
            share: {
                title: "Bagikan Artikel Ini",
                content: `
                    <p style="margin-bottom:16px;">Bagikan cerita ini ke teman-temanmu melalui media sosial favoritmu:</p>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:16px;">
                        <a href="javascript:void(0)" onclick="shareToPlatform('whatsapp')" style="display:flex; align-items:center; gap:8px; padding:12px; border-radius:10px; background:#25D366; color:#fff; text-decoration:none; font-weight:700; font-size:13px; justify-content:center;">💬 WhatsApp</a>
                        <a href="javascript:void(0)" onclick="shareToPlatform('twitter')" style="display:flex; align-items:center; gap:8px; padding:12px; border-radius:10px; background:#111; color:#fff; text-decoration:none; font-weight:700; font-size:13px; justify-content:center;">𝕏 Twitter / X</a>
                        <a href="javascript:void(0)" onclick="shareToPlatform('telegram')" style="display:flex; align-items:center; gap:8px; padding:12px; border-radius:10px; background:#0088cc; color:#fff; text-decoration:none; font-weight:700; font-size:13px; justify-content:center;">✈ Telegram</a>
                        <a href="javascript:void(0)" onclick="shareToPlatform('facebook')" style="display:flex; align-items:center; gap:8px; padding:12px; border-radius:10px; background:#1877f2; color:#fff; text-decoration:none; font-weight:700; font-size:13px; justify-content:center;">👥 Facebook</a>
                    </div>
                    <button type="button" onclick="copyArticleLink(); closeModal();" style="width:100%; padding:12px; background:#f1f2f6; color:#111; border:1px solid #ccc; border-radius:10px; font-weight:700; cursor:pointer;">📋 Salin Tautan Langsung</button>
                `
            },
            guestSavePrompt: {
                title: "Simpan Artikel ke Akun",
                content: `
                    <p>Kamu belum masuk ke akun TULIP. Masuk sekarang agar tersimpan secara permanen di akunmu, atau simpan di perangkat ini (mode Tamu).</p>
                    <div style="display:flex; flex-direction:column; gap:10px; margin-top:16px;">
                        <a href="../auth/login.php" style="text-align:center; padding:12px; background:#111; color:#fff; border-radius:10px; text-decoration:none; font-weight:700; font-size:14px;">Masuk / Daftar Akun →</a>
                        <button type="button" onclick="saveAsGuest(); closeModal();" style="padding:12px; background:#FAF0FE; color:#BD4BE5; border:1.5px solid #BD4BE5; border-radius:10px; font-weight:700; cursor:pointer; font-size:14px;">Simpan di Perangkat Ini (Tamu)</button>
                    </div>
                `
            }
        };

        function openModal(type) {
            const data = modalContents[type];
            if (!data) return;
            document.getElementById('modalTitle').textContent = data.title;
            document.getElementById('modalBody').innerHTML = data.content;
            document.getElementById('siteModalOverlay').classList.add('active');
            document.getElementById('siteModalBox').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const overlay = document.getElementById('siteModalOverlay');
            const box = document.getElementById('siteModalBox');
            if (overlay) overlay.classList.remove('active');
            if (box) box.classList.remove('active');
            document.body.style.overflow = '';
        }

        function openShareModal() {
            if (navigator.share) {
                navigator.share({
                    title: <?php echo json_encode($articleTitle); ?>,
                    text: 'Baca artikel ini di TULIP MAGAZINE.',
                    url: window.location.href
                }).catch(() => {});
            } else {
                openModal('share');
            }
        }

        function shareToPlatform(plat) {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(<?php echo json_encode($articleTitle); ?>);
            let shareUrl = '';
            if (plat === 'whatsapp') shareUrl = `https://api.whatsapp.com/send?text=${title}%20${url}`;
            if (plat === 'twitter') shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
            if (plat === 'telegram') shareUrl = `https://t.me/share/url?url=${url}&text=${title}`;
            if (plat === 'facebook') shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
            window.open(shareUrl, '_blank', 'noopener,noreferrer,width=600,height=500');
            closeModal();
        }

        function handleContactSubmit(e) {
            e.preventDefault();
            closeModal();
            showToast('✓ Pesanmu telah terkirim ke Redaksi TULIP. Terima kasih!');
        }

        function saveCookiePreferences() {
            closeModal();
            showToast('✓ Preferensi cookie kamu berhasil diperbarui.');
        }

        // Newsletter Subscription
        function handleNewsletterSubmit(e) {
            e.preventDefault();
            const input = document.getElementById('newsletterEmail');
            const btn = document.getElementById('newsletterBtn');
            if (!input || !input.value) return;

            const email = input.value.trim();
            let subscribers = JSON.parse(localStorage.getItem('tulip_subscribers') || '[]');
            if (!subscribers.includes(email)) {
                subscribers.push(email);
                localStorage.setItem('tulip_subscribers', JSON.stringify(subscribers));
            }

            btn.textContent = 'SUBSCRIBED ✓';
            btn.classList.add('subscribed');
            input.value = '';
            showToast('🎉 Terima kasih! Kamu telah berlangganan newsletter TULIP MAGAZINE.');
        }

        // Save Article Logic (logged in + guest fallback)
        const currentArticleId = <?php echo (int) $articleId; ?>;
        const isUserLoggedIn = <?php echo $userId > 0 ? 'true' : 'false'; ?>;

        function updateSaveUI(saved) {
            const topBtn = document.getElementById('saveButton');
            const topIcon = document.getElementById('saveIcon');
            const topText = document.getElementById('saveText');
            const botBtn = document.getElementById('bottomSaveButton');
            const botIcon = document.getElementById('bottomSaveIcon');
            const botText = document.getElementById('bottomSaveText');

            if (saved) {
                if (topBtn) topBtn.classList.add('saved');
                if (topIcon) topIcon.textContent = '♥';
                if (topText) topText.textContent = 'Tersimpan';
                if (botBtn) botBtn.classList.add('saved');
                if (botIcon) botIcon.textContent = '♥';
                if (botText) botText.textContent = 'Tersimpan';
            } else {
                if (topBtn) topBtn.classList.remove('saved');
                if (topIcon) topIcon.textContent = '♡';
                if (topText) topText.textContent = 'Simpan';
                if (botBtn) botBtn.classList.remove('saved');
                if (botIcon) botIcon.textContent = '♡';
                if (botText) botText.textContent = 'Simpan';
            }
        }

        // Check guest save on page load
        document.addEventListener('DOMContentLoaded', () => {
            if (!isUserLoggedIn) {
                const guestSaved = JSON.parse(localStorage.getItem('tulip_guest_saved') || '[]');
                if (guestSaved.includes(currentArticleId)) {
                    updateSaveUI(true);
                }
            }
        });

        function saveAsGuest() {
            let guestSaved = JSON.parse(localStorage.getItem('tulip_guest_saved') || '[]');
            if (guestSaved.includes(currentArticleId)) {
                guestSaved = guestSaved.filter(id => id !== currentArticleId);
                localStorage.setItem('tulip_guest_saved', JSON.stringify(guestSaved));
                updateSaveUI(false);
                showToast('♡ Artikel dihapus dari tersimpan di perangkat ini.');
            } else {
                guestSaved.push(currentArticleId);
                localStorage.setItem('tulip_guest_saved', JSON.stringify(guestSaved));
                updateSaveUI(true);
                showToast('♥ Artikel berhasil disimpan di perangkat ini.');
            }
        }

        async function toggleSaveArticle() {
            if (!isUserLoggedIn) {
                openModal('guestSavePrompt');
                return;
            }

            const topBtn = document.getElementById('saveButton');
            if (topBtn) topBtn.disabled = true;

            try {
                const response = await fetch('../save_article.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'article_id=' + currentArticleId
                });
                const data = await response.json();

                if (data.success) {
                    updateSaveUI(data.saved);
                    showToast(data.saved ? '♥ Artikel berhasil disimpan ke daftar bacaan kamu!' : '♡ Artikel dihapus dari daftar tersimpan.');
                } else {
                    alert(data.message || 'Gagal menyimpan artikel.');
                }
            } catch (err) {
                console.error(err);
                showToast('Terjadi kesalahan koneksi.');
            } finally {
                if (topBtn) topBtn.disabled = false;
            }
        }

        async function copyArticleLink() {
            try {
                await navigator.clipboard.writeText(window.location.href);
                showToast('✓ Tautan artikel berhasil disalin ke clipboard!');
            } catch (err) {
                showToast('Gagal menyalin tautan.');
            }
        }

        async function shareArticle() {
            openShareModal();
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