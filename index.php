<?php
session_start();
require_once 'config/database.php';

$userId = 0;
if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $userId = (int) $_SESSION['user_id'];
} elseif (isset($_SESSION['user']['id']) && is_numeric($_SESSION['user']['id'])) {
    $userId = (int) $_SESSION['user']['id'];
}

function getArticleId($conn, $keyword) {
    $keyword = mysqli_real_escape_string($conn, $keyword);

    $query = mysqli_query(
        $conn,
        "SELECT id FROM articles 
         WHERE title LIKE '%$keyword%' 
         LIMIT 1"
    );

    $article = mysqli_fetch_assoc($query);

    return $article ? $article['id'] : 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TULIP. — Magazine for the New Generation</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@1,400;1,600&display=swap" rel="stylesheet">

    <style>
        /* ==========================================
           1. RESET & Continuous Green Gradient Background
           ========================================== */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        :root {
            --text-dark: #121811;
            --text-muted: #526352;
            --pink-accent: #FF9EBB;
            --border-black: #121811;
            --transition-smooth: all 0.35s ease;
        }

        body {
            background: linear-gradient(180deg, 
                #A8F0BA 0%, 
                #D8FCDD 20%, 
                #FFFFFF 50%, 
                #C2FACE 75%, 
                #7BE392 100%
            );
            color: var(--text-dark);
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ==========================================
           2. HEADER NAVBAR
           ========================================== */
        header {
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(168, 240, 186, 0.4);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            transition: background 0.35s ease;
        }

        /* Saat menu terbuka, header atas menyatu dengan gradien kuning atas */
        header.menu-open {
            background: #A8F0BA 100%;
            border-bottom: none;
        }

        .nav-wrapper {
            padding: 16px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-brand img {
            height: 52px;
            width: auto;
            max-width: 220px;
            display: block;
            object-fit: contain;
            transition: var(--transition-smooth);
        }

        .logo-brand:hover img {
            transform: scale(1.05) rotate(-2deg);
        }

        .logo-fallback {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--text-dark);
        }

        .nav-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .icon-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.85);
            border: 1.5px solid rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-dark);
            transition: var(--transition-smooth);
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            cursor: pointer;
        }

        .icon-btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
            transition: var(--transition-smooth);
        }

        .icon-btn:hover {
            background: #FFFFFF;
            color: #FF5D8F;
            transform: scale(1.1) rotate(6deg);
        }

        /* ==========================================
           3. NAVBAR EXPANDABLE MENU (SMEARING KUNING - PUTIH - KUNING)
           ========================================== */
        .navbar-menu-expand {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height 0.45s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.35s ease, padding 0.35s ease;
            /* Gradien smearing: Kuning atas -> Putih tengah -> Kuning bawah */
            background: linear-gradient(180deg,
                #A8F0BA 0%, 
                #D8FCDD 25%, 
                #FFFFFF 50%, 
                #C2FACE 75%, 
                #7BE392 100% 
            );
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.06);
        }

        .navbar-menu-expand.active {
            max-height: 520px;
            opacity: 1;
            padding: 20px 0 32px;
        }

        .menu-expand-inner {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Font Rapi, Bersih, dan Enak Dibaca */
        .menu-expand-item {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-dark);
            width: fit-content;
            letter-spacing: -0.5px;
            transition: transform 0.3s ease, color 0.3s ease;
            display: inline-block;
        }

        .menu-expand-item:hover {
            transform: translateX(10px);
            color: #7BE392;
        }

        .menu-expand-footer {
            margin-top: 28px;
            padding-top: 16px;
            border-top: 1px solid rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: rgba(0, 0, 0, 0.55);
            font-weight: 500;
        }

        .menu-expand-footer-links {
            display: flex;
            gap: 16px;
        }

        .menu-expand-footer-links a:hover {
            color: var(--text-dark);
            text-decoration: underline;
        }

        /* ==========================================
           4. HERO SECTION
           ========================================== */
        .hero-section {
            padding: 30px 0 50px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 32px;
            padding: 40px;
            box-shadow: 0 10px 30px -10px rgba(56, 161, 105, 0.12);
        }

        .hero-content .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2F855A;
            background: rgba(56, 161, 105, 0.12);
            padding: 6px 14px;
            border-radius: 20px;
            margin-bottom: 16px;
        }

        .hero-content .tag svg { width: 14px; height: 14px; fill: #2F855A; }

        .hero-title {
            font-size: 38px;
            line-height: 1.15;
            font-weight: 800;
            margin-bottom: 16px;
            letter-spacing: -1px;
        }

        .hero-desc {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .hero-btns {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: var(--text-dark);
            color: #FFF;
            padding: 12px 26px;
            border-radius: 99px;
            font-weight: 600;
            font-size: 14px;
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary svg { width: 16px; height: 16px; fill: #FFF; }

        .btn-primary:hover {
            background: #FF7597;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 117, 151, 0.3);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.85);
            color: var(--text-dark);
            padding: 12px 24px;
            border-radius: 99px;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid rgba(0,0,0,0.08);
            transition: var(--transition-smooth);
        }

        .btn-secondary:hover { background: #FFF; transform: translateY(-2px); }

        .hero-image-wrapper {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.06);
            height: 360px;
        }

        .hero-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }

        .hero-image-wrapper:hover img { transform: scale(1.03); }

        /* ==========================================
           5. STATEMENT / MISSION SECTION
           ========================================== */
        .statement-section {
            padding: 40px 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: center;
            border-top: 1px solid rgba(0,0,0,0.08);
            border-bottom: 1px solid rgba(0,0,0,0.08);
            margin-bottom: 60px;
        }

        .statement-title {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.0;
            letter-spacing: -2px;
        }

        .statement-mission {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .mission-label {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #2F855A;
        }

        .mission-text {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 20px;
            color: var(--text-muted);
            line-height: 1.4;
        }

        /* ==========================================
           6. TRENDING SECTION
           ========================================== */
        .section-header { margin-bottom: 24px; }

        .section-title {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .section-title::after {
            content: "✦";
            color: var(--pink-accent);
            font-size: 22px;
        }

        .trending-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 30px;
            margin-bottom: 60px;
        }

        .main-trending-card {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            height: 400px;
            box-shadow: 0 10px 30px -10px rgba(56, 161, 105, 0.12);
        }

        .main-trending-card img { width: 100%; height: 100%; object-fit: cover; }

        .main-trending-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            padding: 28px;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.85) 100%);
            color: #FFF;
        }

        .main-trending-overlay h3 {
            font-size: 20px;
            font-weight: 700;
            line-height: 1.3;
            margin-top: 6px;
        }

        .trending-list { display: flex; flex-direction: column; gap: 16px; }

        .trending-item {
            display: flex;
            gap: 16px;
            align-items: center;
            background: rgba(255, 255, 255, 0.55);
            padding: 12px;
            border-radius: 18px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: var(--transition-smooth);
        }

        .trending-item:hover {
            background: #FFF;
            transform: translateX(6px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.04);
        }

        .num {
            font-size: 22px;
            font-weight: 800;
            color: rgba(0, 0, 0, 0.3);
            width: 28px;
            text-align: center;
        }

        .trending-thumb { width: 70px; height: 70px; border-radius: 14px; object-fit: cover; flex-shrink: 0; }

        .trending-info h4 { font-size: 13px; font-weight: 700; line-height: 1.35; margin-top: 2px; }

        .meta {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* ==========================================
           7. TERBARU SECTION
           ========================================== */
        .terbaru-section { margin-bottom: 60px; }

        .terbaru-top-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .terbaru-bottom-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .article-card {
            background: rgba(255, 255, 255, 0.6);
            border-radius: 20px;
            padding: 16px;
            border: 1px solid rgba(255, 255, 255, 0.85);
            transition: var(--transition-smooth);
            backdrop-filter: blur(10px);
        }

        .article-card:hover {
            transform: translateY(-4px);
            background: #FFFFFF;
            box-shadow: 0 12px 30px rgba(0,0,0,0.05);
        }

        .article-card img { width: 100%; height: 180px; object-fit: cover; border-radius: 14px; margin-bottom: 12px; }
        .article-card.large img { height: 220px; }

        .article-card h4 { font-size: 15px; font-weight: 700; margin: 6px 0 4px; line-height: 1.35; }
        .article-card p { font-size: 12px; color: var(--text-muted); line-height: 1.5; }

        /* ==========================================
           8. JELAJAH KATEGORI
           ========================================== */
        .kategori-section { margin-bottom: 80px; }

        .kategori-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
        }

        .kategori-card {
            position: relative;
            height: 280px;
            border-radius: 20px;
            overflow: hidden;
            border: 2px solid var(--border-black);
            box-shadow: 4px 4px 0px var(--border-black);
            transition: var(--transition-smooth);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .kategori-card img {
            position: absolute; top: 0; left: 0;
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.5s ease;
            z-index: 1;
        }

        .kategori-card::after {
            content: "";
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0) 35%, rgba(255, 255, 255, 0.95) 100%);
            z-index: 2;
        }

        .kategori-title {
            position: relative; z-index: 3;
            padding: 16px 12px;
            font-weight: 800; font-size: 13px;
            text-transform: lowercase;
            color: var(--text-dark);
            text-align: center;
        }

        .kategori-card:hover { transform: translateY(-6px) rotate(1deg); box-shadow: 6px 6px 0px var(--pink-accent); }
        .kategori-card:hover img { transform: scale(1.08); }

        /* ==========================================
           9. FOOTER
           ========================================== */
        footer { padding: 60px 0 40px; border-top: 1px solid rgba(0, 0, 0, 0.08); }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1.6fr;
            gap: 40px;
            margin-bottom: 50px;
        }

        .footer-logo img { height: 44px; width: auto; margin-bottom: 12px; }
        .footer-desc { font-size: 13px; color: var(--text-muted); line-height: 1.5; }

        .footer-col h5 {
            font-size: 12px; font-weight: 800;
            margin-bottom: 16px;
            text-transform: uppercase; letter-spacing: 1px;
        }

        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 10px; }
        .footer-col ul li a { font-size: 13px; color: var(--text-muted); transition: color 0.2s; }
        .footer-col ul li a:hover { color: var(--text-dark); font-weight: 600; }

        .newsletter-box {
            display: flex;
            background: rgba(255, 255, 255, 0.85);
            padding: 4px;
            border-radius: 99px;
            border: 1px solid rgba(0,0,0,0.12);
        }

        .newsletter-box input {
            border: none; outline: none;
            padding: 10px 16px; font-size: 12px;
            flex: 1; background: transparent; min-width: 0;
        }

        .newsletter-box button {
            background: var(--text-dark);
            color: #FFF; border: none;
            padding: 10px 20px; border-radius: 99px;
            font-size: 11px; font-weight: 700;
            letter-spacing: 0.5px; cursor: pointer;
            transition: var(--transition-smooth); flex-shrink: 0;
        }

        .newsletter-box button:hover { background: #FF7597; }

        .copyright-bar {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 11px; color: var(--text-muted);
            border-top: 1px solid rgba(0,0,0,0.06); padding-top: 24px;
        }

        .copyright-links { display: flex; gap: 16px; }

        /* RESPONSIVE MEDIA QUERIES */
        @media (max-width: 992px) {
            .hero-grid { grid-template-columns: 1fr; padding: 32px; gap: 24px; }
            .hero-image-wrapper { height: 300px; }
            .statement-section { grid-template-columns: 1fr; gap: 16px; }
            .statement-title { font-size: 38px; }
            .trending-grid { grid-template-columns: 1fr; }
            .terbaru-bottom-grid { grid-template-columns: repeat(2, 1fr); }
            .kategori-grid { grid-template-columns: repeat(3, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; }
            .menu-expand-item { font-size: 26px; }
        }

        @media (max-width: 600px) {
            .container { padding: 0 16px; }
            .logo-brand img { height: 42px; }
            .icon-btn { width: 38px; height: 38px; }
            .icon-btn svg { width: 16px; height: 16px; }
            .hero-title { font-size: 28px; }
            .hero-grid { padding: 24px; border-radius: 24px; }
            .hero-image-wrapper { height: 220px; }
            .main-trending-card { height: 320px; }
            .main-trending-overlay h3 { font-size: 16px; }
            .terbaru-top-grid, .terbaru-bottom-grid { grid-template-columns: 1fr; }
            .kategori-grid { grid-template-columns: repeat(2, 1fr); }
            .kategori-card { height: 220px; }
            .footer-grid { grid-template-columns: 1fr; gap: 28px; }
            .copyright-bar { flex-direction: column; gap: 12px; text-align: center; }
            .copyright-links { justify-content: center; flex-wrap: wrap; }
            .menu-expand-item { font-size: 22px; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR HEADER -->
    <header id="mainHeader">
        <div class="container nav-wrapper">
            <a href="index.php" class="logo-brand">
                <img src="assets/images/logo-tulip.png" alt="TULIP." onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <span class="logo-fallback" style="display:none;">TULIP.</span>
            </a>
            <div class="nav-actions">
                <a href="search.php" class="icon-btn" title="Cari Artikel">
                    <svg viewBox="0 0 24 24"><path d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z"/></svg>
                </a>
                <a href="saved.php" class="icon-btn" title="Artikel Tersimpan">
                    <svg viewBox="0 0 24 24"><path d="M5 2h14a1 1 0 0 1 1 1v19.143a.5.5 0 0 1-.766.424L12 18.03l-7.234 4.536A.5.5 0 0 1 4 22.143V3a1 1 0 0 1 1-1zm13 2H6v15.432l6-3.761 6 3.761V4z"/></svg>
                </a>
                <a href="<?= $userId > 0 ? 'profile.php' : 'auth/login.php'; ?>" class="icon-btn" title="<?= $userId > 0 ? 'Profil Saya' : 'Masuk / Login'; ?>">
                    <svg viewBox="0 0 24 24"><path d="M12 2c5.523 0 10 4.477 10 10a9.956 9.956 0 0 1-2.258 6.33l-2.112-2.112a5 5 0 0 0-7.26 0l-2.112 2.112A9.956 9.956 0 0 1 2 12C2 6.477 6.477 2 12 2zm0 2a8 8 0 0 0-6.85 12.122l1.282-1.282a7 7 0 0 1 9.136 0l1.282 1.282A8 8 0 0 0 12 4zm0 3a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm0 2a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/></svg>
                </a>
                <!-- Toggle Button Ikon Garis 3 Menu -->
                <button class="icon-btn" id="menuToggleBtn" title="Menu Navigasi" onclick="toggleNavbarMenu()" aria-expanded="false" aria-controls="navbarMenuExpand">
                    <svg id="menuIconSvg" viewBox="0 0 24 24"><path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/></svg>
                </button>
            </div>
        </div>

        <!-- COLLAPSIBLE NAVBAR MENU -->
        <div class="navbar-menu-expand" id="navbarMenuExpand">
            <div class="container">
               <div class="menu-expand-inner">
                    <a href="index.php" class="menu-expand-item">Home</a>
                    <a href="category.php?slug=fashion-beauty" class="menu-expand-item">Fashion &amp; Beauty</a>
                    <a href="category.php?slug=art-photography" class="menu-expand-item">Art &amp; Photography</a>
                    <a href="category.php?slug=music" class="menu-expand-item">Music</a>
                    <a href="category.php?slug=film-tv" class="menu-expand-item">Film &amp; TV</a>
                    <a href="category.php?slug=life-culture" class="menu-expand-item">Life &amp; Culture</a>
                    <a href="about.php" class="menu-expand-item" style="font-size:20px; opacity:0.8; margin-top:6px;">About Magazine →</a>
               </div>
                <div class="menu-expand-footer">
                    <span>&copy; <?php echo date("Y"); ?> Tulip Magazine. All rights reserved.</span>
                    <div class="menu-expand-footer-links">
                        <a href="about.php#about">About Us</a>
                        <a href="about.php#contact">Contact</a>
                        <a href="about.php#about">Privacy Policy</a>
                        <a href="about.php#about">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN KONTEN UTAMA WEBPAGE -->
    <main class="container">
        
        <!-- HERO SECTION -->
        <section class="hero-section">
            <div class="hero-grid">
                <div class="hero-content">
                    <span class="tag">
                        <svg viewBox="0 0 24 24"><path d="M12 2l2.4 7.2h7.6l-6 4.8 2.4 7.2-6-4.8-6 4.8 2.4-7.2-6-4.8h7.6z"/></svg>
                        Beauty & Skincare
                    </span>
                    <h1 class="hero-title">Stop! Jangan Asal Ikutin Tren Skincare</h1>
                    <p class="hero-desc">Kulit kamu itu unik. Sebelum ikut-ikutan mencoba produk yang lagi viral, pahami dulu apa yang sebenarnya dibutuhkan kulitmu.</p>
                    <div class="hero-btns">
                        <a href="artikel/artikel-01.php" class="btn-primary">
                            Baca Artikel 
                            <svg viewBox="0 0 24 24"><path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"/></svg>
                        </a>
                        <a href="javascript:void(0)" class="btn-secondary" onclick="toggleNavbarMenu()">Kategori</a>
                    </div>
                </div>
                <div class="hero-image-wrapper">
                    <img src="assets/images/skincare.png" alt="Stop Jangan Asal Ikutin Tren Skincare">
                </div>
            </div>
        </section>

        <!-- STATEMENT / MISSION SECTION -->
        <section class="statement-section">
            <h2 class="statement-title">Stories Beyond Trends</h2>
            <div class="statement-mission">
                <span class="mission-label">misi / tujuan</span>
                <p class="mission-text">Menjadi ruang inspirasi bagi generasi muda untuk mengekspresikan seni, gaya hidup, dan cerita autentik di luar batasan tren sementara.</p>
            </div>
        </section>

        <!-- TRENDING SECTION -->
<section style="margin-bottom: 60px;">

    <div class="section-header">
        <h3 class="section-title">trending</h3>
    </div>

    <div class="trending-grid">

        <!-- TRENDING 01 - NICO ROBIN -->
        <a href="artikel/artikel-07.php" class="article-click">
            <div class="main-trending-card">

                <img src="assets/images/nico-robin.jpg" alt="Nico Robin">

                <div class="main-trending-overlay">

                    <span class="meta" style="color: var(--pink-accent);">
                        Pop Culture ✦ Highlight
                    </span>

                    <h3>
                        Nico Robin: Membaca Kebenaran, Merayakan Perlawanan
                    </h3>

                </div>

            </div>
        </a>


        <!-- TRENDING LIST -->
        <div class="trending-list">

            <!-- TRENDING 02 -->
            <a href="artikel/artikel-05.php" class="article-click">
                <div class="trending-item">

                    <span class="num">02</span>

                    <img
                        src="assets/images/that-one-song.jpg"
                        class="trending-thumb"
                        alt="That One Song"
                    >

                    <div class="trending-info">

                        <span class="meta">
                            Music
                        </span>

                        <h4>
                            That One Song: Kenapa Kita Ketagihan Dengarnya?
                        </h4>

                    </div>

                </div>
            </a>


            <!-- TRENDING 03 -->
            <a href="artikel/artikel-02.php" class="article-click">
                <div class="trending-item">

                    <span class="num">03</span>

                    <img
                        src="assets/images/fashion-trend.png"
                        class="trending-thumb"
                        alt="Fashion Trend"
                    >

                    <div class="trending-info">

                        <span class="meta">
                            Fashion
                        </span>

                        <h4>
                            Kita Butuh Baju Baru, atau Cuma Butuh Tren Baru?
                        </h4>

                    </div>

                </div>
            </a>


            <!-- TRENDING 04 -->
            <a href="artikel/artikel-09.php" class="article-click">
                <div class="trending-item">

                    <span class="num">04</span>

                    <img
                        src="assets/images/goth-girl.jpg"
                        class="trending-thumb"
                        alt="Goth Girl Era"
                    >

                    <div class="trending-info">

                        <span class="meta">
                            Fashion
                        </span>

                        <h4>
                            Goth Girl Era: Ketika Gaya Gelap Menjadi Bentuk Ekspresi Diri
                        </h4>

                    </div>

                </div>
            </a>

        </div>

    </div>

</section>

        <!-- JELAJAH KATEGORI -->
        <section class="kategori-section">
            <div class="section-header">
                <h3 class="section-title">jelajahi kategori</h3>
            </div>

            <div class="kategori-grid">
                <div class="kategori-card" onclick="window.location.href='category.php?slug=fashion-beauty'">
                    <img src="assets/images/fashion-trend.png" alt="Fashion & Beauty">
                <div class="kategori-title">fashion & beauty</div>
            </div>

                <div class="kategori-card" onclick="window.location.href='category.php?slug=art-photography'">
                    <img src="assets/images/darwis-triadi2.jpeg" alt="Art & Photography">
                    <div class="kategori-title">art & photography</div>
                </div>

                <div class="kategori-card" onclick="window.location.href='category.php?slug=music'">
                    <img src="assets/images/that-one-song.jpg" alt="Music">
                    <div class="kategori-title">music</div>
                </div>

                <div class="kategori-card" onclick="window.location.href='category.php?slug=film-tv'">
                    <img src="assets/images/coralie-fargeat.jpg" alt="Film & TV">
                    <div class="kategori-title">film & tv</div>
                </div>

                <div class="kategori-card" onclick="window.location.href='category.php?slug=life-culture'">
                    <img src="assets/images/offline-era.jpg" alt="Life & Culture">
                    <div class="kategori-title">life & culture</div>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER UTAMA -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a href="index.php" class="footer-logo">
                        <img src="assets/images/logo-tulip.png" alt="Tulip Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span class="logo-fallback" style="display:none;">TULIP.</span>
                    </a>
                    <p class="footer-desc">Magazine for the new generation. Tempat kamu menemukan cerita, gaya, dan inspirasi autentik setiap hari.</p>
                </div>
                <div class="footer-col">
                    <h5>MAGAZINE</h5>
                    <ul>
                        <li><a href="about.php#about">About</a></li>
                        <li><a href="about.php#masthead">Masthead</a></li>
                        <li><a href="about.php#contact">Contact</a></li>
                        <li><a href="about.php#advertise">Advertise</a></li>
                        <li><a href="about.php#careers">Careers</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>CATEGORIES</h5>
                    <ul>
                        <li><a href="category.php?slug=fashion-beauty">Fashion &amp; Beauty</a></li>
                        <li><a href="category.php?slug=art-photography">Art &amp; Photography</a></li>
                        <li><a href="category.php?slug=film-tv">Film &amp; TV</a></li>
                        <li><a href="category.php?slug=music">Music</a></li>
                        <li><a href="category.php?slug=life-culture">Life &amp; Culture</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>STAY IN THE LOOP</h5>
                    <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">Get the latest stories delivered to your inbox.</p>
                    <form class="newsletter-box" onsubmit="handleSubscribeSubmit(event, this);">
                        <input type="email" name="email" placeholder="your@email.com" required>
                        <button type="submit">SUBSCRIBE</button>
                    </form>
                </div>
            </div>
            <div class="copyright-bar">
                <span>&copy; <?php echo date("Y"); ?> TULIP MAGAZINE. ALL RIGHTS RESERVED.</span>
                <div class="copyright-links">
                    <a href="#">PRIVACY POLICY</a>
                    <a href="#">TERMS OF USE</a>
                    <a href="#">COOKIE PREFERENCES</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT SLIDE-DOWN NAVBAR MENU -->
    <script>
        function toggleNavbarMenu() {
            const header = document.getElementById('mainHeader');
            const menuExpand = document.getElementById('navbarMenuExpand');
            const iconSvg = document.getElementById('menuIconSvg');

            menuExpand.classList.toggle('active');
            header.classList.toggle('menu-open');

            if (menuExpand.classList.contains('active')) {
                iconSvg.innerHTML = '<path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/>';
            } else {
                iconSvg.innerHTML = '<path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/>';
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
        const isIndex = window.location.pathname.endsWith('index.php') || window.location.pathname === '/tulip_magazinee/' || window.location.pathname === '/tulip_magazinee';
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