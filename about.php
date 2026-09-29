<?php
session_start();
require_once 'config/database.php';

$userId = 0;
if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $userId = (int) $_SESSION['user_id'];
} elseif (isset($_SESSION['user']['id']) && is_numeric($_SESSION['user']['id'])) {
    $userId = (int) $_SESSION['user']['id'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami — TULIP MAGAZINE</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@1,400;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        :root {
            --green-1: #A8F0BA;
            --green-2: #D8FCDD;
            --text-dark: #121811;
            --text-muted: #526352;
            --accent: #26734d;
        }

        body {
            background: linear-gradient(180deg, 
                #A8F0BA 0%, 
                #D8FCDD 18%, 
                #FFFFFF 45%, 
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

        /* HEADER */
        header {
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(168, 240, 186, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
        }

        .nav-wrapper {
            padding: 16px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-brand img {
            height: 48px;
            width: auto;
            object-fit: contain;
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
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .icon-btn:hover {
            background: #FFFFFF;
            transform: scale(1.08);
        }

        .icon-btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        /* SUB-NAV TABS */
        .sub-nav {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 30px 0 40px;
            justify-content: center;
        }

        .sub-tab {
            padding: 8px 18px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(17, 17, 17, 0.15);
            font-size: 13px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .sub-tab:hover {
            background: #111;
            color: #fff;
        }

        /* CONTENT SECTIONS */
        .page-header {
            text-align: center;
            padding: 50px 0 20px;
        }

        .page-badge {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 12px;
            display: inline-block;
        }

        .page-title {
            font-size: clamp(34px, 5vw, 56px);
            font-weight: 800;
            letter-spacing: -1.5px;
            line-height: 1.1;
            margin-bottom: 16px;
        }

        .page-desc {
            max-width: 680px;
            margin: 0 auto;
            color: var(--text-muted);
            font-size: 16px;
            line-height: 1.6;
        }

        .section-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 28px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            scroll-margin-top: 100px;
        }

        .section-card h2 {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-card p {
            font-size: 15px;
            line-height: 1.7;
            color: #333;
            margin-bottom: 16px;
        }

        /* GRID MASTHEAD */
        .masthead-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-top: 24px;
        }

        .member-box {
            padding: 20px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(17, 17, 17, 0.08);
        }

        .member-role {
            font-size: 11px;
            font-weight: 700;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .member-name {
            font-size: 18px;
            font-weight: 800;
            color: #111;
            margin-bottom: 4px;
        }

        .member-desc {
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }

        /* CONTACT FORM */
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid rgba(17, 17, 17, 0.2);
            background: #fff;
            font-size: 14px;
            outline: none;
            transition: border 0.2s ease;
        }

        .form-control:focus {
            border-color: #111;
        }

        textarea.form-control {
            height: 110px;
            resize: vertical;
        }

        .btn-submit {
            background: #111;
            color: #fff;
            padding: 12px 28px;
            border-radius: 999px;
            border: 0;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            background: #333;
        }

        /* FOOTER */
        footer {
            border-top: 1px solid rgba(17, 17, 17, 0.15);
            background: rgba(168, 240, 186, 0.4);
            padding: 50px 0 25px;
            margin-top: 60px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1.4fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-col h5 {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.8px;
            margin-bottom: 16px;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col li {
            margin-bottom: 10px;
        }

        .footer-col a {
            font-size: 13px;
            color: #333;
            transition: color 0.2s ease;
        }

        .footer-col a:hover {
            color: #000;
            font-weight: 600;
        }

        .newsletter-box {
            display: flex;
            background: #fff;
            border: 1px solid #111;
            border-radius: 999px;
            overflow: hidden;
            padding: 4px;
        }

        .newsletter-box input {
            flex: 1;
            border: 0;
            padding: 8px 16px;
            outline: 0;
            font-size: 12px;
        }

        .newsletter-box button {
            background: #111;
            color: #fff;
            border: 0;
            padding: 8px 18px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
        }

        @media (max-width: 800px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 520px) {
            .footer-grid {
                grid-template-columns: 1fr;
            }
            .section-card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR HEADER -->
    <header>
        <div class="container nav-wrapper">
            <a href="index.php" class="logo-brand">
                <img src="assets/images/logo-tulip.png" alt="TULIP." onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <span style="display:none; font-weight:800; font-size:24px;">TULIP.</span>
            </a>
            <div class="nav-actions">
                <a href="search.php" class="icon-btn" title="Cari">
                    <svg viewBox="0 0 24 24"><path d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z"/></svg>
                </a>
                <a href="saved.php" class="icon-btn" title="Artikel Tersimpan">
                    <svg viewBox="0 0 24 24"><path d="M5 2h14a1 1 0 0 1 1 1v19.143a.5.5 0 0 1-.766.424L12 18.03l-7.234 4.536A.5.5 0 0 1 4 22.143V3a1 1 0 0 1 1-1zm13 2H6v15.432l6-3.761 6 3.761V4z"/></svg>
                </a>
                <a href="<?= $userId > 0 ? 'profile.php' : 'auth/login.php' ?>" class="icon-btn" title="<?= $userId > 0 ? 'Profil Saya' : 'Masuk' ?>">
                    <svg viewBox="0 0 24 24"><path d="M12 2c5.523 0 10 4.477 10 10a9.956 9.956 0 0 1-2.258 6.33l-2.112-2.112a5 5 0 0 0-7.26 0l-2.112 2.112A9.956 9.956 0 0 1 2 12C2 6.477 6.477 2 12 2zm0 2a8 8 0 0 0-6.85 12.122l1.282-1.282a7 7 0 0 1 9.136 0l1.282 1.282A8 8 0 0 0 12 4zm0 3a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm0 2a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/></svg>
                </a>
                <a href="index.php" class="icon-btn" title="Beranda">
                    <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        
        <div class="page-header">
            <span class="page-badge">Tulip Editorial &amp; Organization</span>
            <h1 class="page-title">Stories Beyond Trends</h1>
            <p class="page-desc">TULIP MAGAZINE adalah ruang kurasi digital bagi generasi baru untuk mengeksplorasi estetika, budaya populer, karya seni visual, dan refleksi hidup autentik.</p>
        </div>

        <!-- TABS NAV -->
        <div class="sub-nav">
            <a href="#about" class="sub-tab">About Us</a>
            <a href="#masthead" class="sub-tab">Masthead / Tim</a>
            <a href="#contact" class="sub-tab">Contact</a>
            <a href="#advertise" class="sub-tab">Advertise</a>
            <a href="#careers" class="sub-tab">Careers</a>
        </div>

        <!-- SECTION 1: ABOUT -->
        <section class="section-card" id="about">
            <h2>🌸 Tentang TULIP MAGAZINE</h2>
            <p>
                Didirikan pada 2026, <strong>TULIP MAGAZINE</strong> hadir dari kegelisahan terhadap perputaran algoritma media sosial yang serba instan dan berisik. Di era ketika tren fashion berganti setiap minggu dan lagu-lagu viral hilang dalam sekejap, kami memilih untuk berhenti sejenak dan menyelami narasi di baliknya.
            </p>
            <p>
                Kami menyajikan editorial berkala yang mendalam tentang <strong>Fashion &amp; Beauty</strong> yang mindful, pameran <strong>Art &amp; Photography</strong> yang sarat pesan kemanusiaan, nostalgia <strong>Music</strong> era analog hingga revolusi digital, eksplorasi kritis sinema <strong>Film &amp; TV</strong>, serta renungan <strong>Life &amp; Culture</strong> tentang arti menjadi manusia di balik layar gawai.
            </p>
            <p>
                <em>“We don't chase trends. We tell stories that outlive them.”</em>
            </p>
        </section>

        <!-- SECTION 2: MASTHEAD -->
        <section class="section-card" id="masthead">
            <h2>👥 Editorial Masthead</h2>
            <p>Tim penulis, kurator visual, dan pengarah kreatif di balik setiap edisi TULIP MAGAZINE:</p>
            
            <div class="masthead-grid">
                <div class="member-box">
                    <div class="member-role">Editor in Chief &amp; Founder</div>
                    <div class="member-name">Redaksi TULIP</div>
                    <div class="member-desc">Mengarahkan visi artistik, integritas editorial, dan narasi kurasi utama.</div>
                </div>

                <div class="member-box">
                    <div class="member-role">Fashion &amp; Beauty Editor</div>
                    <div class="member-name">Farah Amelia</div>
                    <div class="member-desc">Penulis esai kritis fast fashion, skinimalism, dan budaya konsumerisme modern.</div>
                </div>

                <div class="member-box">
                    <div class="member-role">Art &amp; Photography Curator</div>
                    <div class="member-name">Rawdiatul Jannah</div>
                    <div class="member-desc">Meliput pameran seni rupa, galeri foto kemanusiaan, dan instalasi visual kontemporer.</div>
                </div>

                <div class="member-box">
                    <div class="member-role">Music &amp; Pop Culture Critic</div>
                    <div class="member-name">Puja Adelia S.</div>
                    <div class="member-desc">Mengulas fenomena musik nostalgia, resonansi psikologi lagu, dan dinamika audio internet.</div>
                </div>

                <div class="member-box">
                    <div class="member-role">Film &amp; TV Specialist</div>
                    <div class="member-name">Mutya Naffisa K.</div>
                    <div class="member-desc">Kritikus sinema, narasi karakter fiksi wanita, genre horor, dan feminisme dalam media.</div>
                </div>

                <div class="member-box">
                    <div class="member-role">Life &amp; Culture Writer</div>
                    <div class="member-name">Sabrina Enjelika</div>
                    <div class="member-desc">Mengeksplorasi fenomena The Offline Era, subkultur goth, dan kesehatan mental generasi muda.</div>
                </div>
            </div>
        </section>

        <!-- SECTION 3: CONTACT -->
        <section class="section-card" id="contact">
            <h2>📬 Hubungi Redaksi</h2>
            <p>Punya pertanyaan, feedback tulisan, atau ide artikel menarik yang ingin kamu bagikan kepada tim kami?</p>
            
            <div class="contact-grid">
                <div>
                    <form onsubmit="handleContactSubmit(event)">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" placeholder="Nama Anda" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" placeholder="nama@email.com" required>
                        </div>
                        <div class="form-group">
                            <label>Subjek / Topik</label>
                            <input type="text" class="form-control" placeholder="misal: Pertanyaan Redaksi / Pitch Artikel" required>
                        </div>
                        <div class="form-group">
                            <label>Pesan</label>
                            <textarea class="form-control" placeholder="Tuliskan pesan Anda di sini..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Kirim Pesan</button>
                    </form>
                </div>
                <div style="background: rgba(168,240,186,0.25); padding: 28px; border-radius: 20px; border: 1px solid rgba(38,115,77,0.15);">
                    <h3 style="font-size: 18px; margin-bottom: 12px; font-weight: 800;">Kantor Editorial TULIP</h3>
                    <p style="font-size: 14px; margin-bottom: 14px;">Gedung Graha Kreatif Lt. 4<br>Jl. Senopati No. 88, Kebayoran Baru<br>Jakarta Selatan 12190</p>
                    <p style="font-size: 14px; margin-bottom: 8px;"><strong>Email:</strong> redaksi@tulipmagazine.com</p>
                    <p style="font-size: 14px; margin-bottom: 8px;"><strong>Instagram:</strong> @tulipmagazinee</p>
                    <p style="font-size: 14px; margin-bottom: 8px;"><strong>Jam Operasional:</strong> Senin – Jumat, 09:00 – 17:00 WIB</p>
                </div>
            </div>
        </section>

        <!-- SECTION 4: ADVERTISE -->
        <section class="section-card" id="advertise">
            <h2>✨ Advertise &amp; Brand Partnership</h2>
            <p>
                TULIP MAGAZINE berkolaborasi dengan brand etis, studio kreatif, desainer independen, dan institusi budaya yang berbagi komitmen yang sama terhadap estetika, kualitas, dan transparansi.
            </p>
            <p>
                Bentuk kemitraan yang kami tawarkan meliputi:
            </p>
            <ul style="margin: 0 0 20px 24px; font-size: 15px; line-height: 1.8;">
                <li><strong>Editorial Sponsored Feature:</strong> Artikel ulasan mendalam yang dirancang dengan sudut pandang bernas dan gaya visual premium.</li>
                <li><strong>Banner &amp; Digital Placement:</strong> Penempatan kurasi visual non-intrusif di halaman kategori dan beranda.</li>
                <li><strong>Creative Campaign &amp; Event:</strong> Kolaborasi peluncuran karya, pameran seni, dan diskusi budaya pop.</li>
            </ul>
            <p>
                Untuk meminta Media Kit &amp; Rate Card 2026, silakan kirim email ke: <a href="mailto:partners@tulipmagazine.com" style="color: var(--accent); font-weight: 700; text-decoration: underline;">partners@tulipmagazine.com</a>.
            </p>
        </section>

        <!-- SECTION 5: CAREERS -->
        <section class="section-card" id="careers">
            <h2>🌱 Bergabung Bersama Kami (Careers)</h2>
            <p>
                Kami selalu membuka pintu bagi penulis lepas (contributing writers), fotografer, desainer grafis, dan pembuat konten yang memiliki perspektif unik dan kecintaan pada dunia literasi visual.
            </p>
            <div style="background: rgba(255,255,255,0.7); border: 1px solid rgba(17,17,17,0.1); border-radius: 16px; padding: 20px; margin-top: 15px;">
                <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 8px;">Cara Mengirimkan Pitch Tulisan / Portofolio:</h3>
                <p style="font-size: 14px; line-height: 1.6; margin-bottom: 10px;">
                    1. Sertakan judul dan sinopsis singkat (150–200 kata) tentang artikel yang ingin kamu tulis.<br>
                    2. Lampirkan 2–3 contoh karya tulis atau tautan portofolio visual kamu.<br>
                    3. Kirim ke <a href="mailto:careers@tulipmagazine.com" style="color: var(--accent); font-weight: 700;">careers@tulipmagazine.com</a> dengan subjek <em>[Pitch Article] - Nama Kamu</em>.
                </p>
            </div>
        </section>

    </main>

    <!-- FOOTER UTAMA -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a href="index.php" class="logo-brand">
                        <img src="assets/images/logo-tulip.png" alt="Tulip Logo" style="height: 44px; margin-bottom: 12px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span style="display:none; font-weight:800; font-size: 22px;">TULIP.</span>
                    </a>
                    <p style="font-size: 13px; color: var(--text-muted); line-height: 1.6;">Magazine for the new generation. Tempat kamu menemukan cerita, gaya, dan inspirasi autentik setiap hari.</p>
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
                    <form class="newsletter-box" onsubmit="handleSubscribeSubmit(event)">
                        <input type="email" name="email" placeholder="your@email.com" required>
                        <button type="submit">SUBSCRIBE</button>
                    </form>
                </div>
            </div>
            <div style="border-top: 1px solid rgba(17,17,17,0.15); padding-top: 20px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 15px; font-size: 11px; color: #555;">
                <span>&copy; <?php echo date("Y"); ?> TULIP MAGAZINE. ALL RIGHTS RESERVED.</span>
                <div>
                    <a href="about.php#about" style="margin-right: 15px;">PRIVACY POLICY</a>
                    <a href="about.php#about" style="margin-right: 15px;">TERMS OF USE</a>
                    <a href="about.php#about">COOKIE PREFERENCES</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
    async function handleSubscribeSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const input = form.querySelector('input[type="email"]');
        const btn = form.querySelector('button');
        const email = input.value.trim();

        if (!email) return;

        btn.disabled = true;
        btn.textContent = '...';

        try {
            const formData = new FormData();
            formData.append('email', email);

            const res = await fetch('subscribe.php', {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });

            const data = await res.json();
            alert(data.message || 'Berhasil berlangganan!');
            if (data.success) {
                input.value = '';
            }
        } catch (err) {
            alert('Terima kasih telah berlangganan newsletter TULIP!');
            input.value = '';
        } finally {
            btn.disabled = false;
            btn.textContent = 'SUBSCRIBE';
        }
    }

    function handleContactSubmit(e) {
        e.preventDefault();
        alert('Terima kasih! Pesan kamu telah terkirim kepada tim Redaksi TULIP.');
        e.target.reset();
    }
    </script>

</body>
</html>
