<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — TULIP MAGAZINE</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            width: 100vw;
            background: linear-gradient(90deg, 
                #3B9EFF 0%, 
                #7BC0FF 18%, 
                #BDDCFF 32%, 
                #EFF6FF 42%, 
                #FAFAFA 52%, 
                #FAFAFA 100%
            );
            color: #121811;
            display: flex;
            align-items: center;
        }

        .auth-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Sisi Kiri: Teks Heading */
        .auth-left {
            flex: 1.1;
            display: flex;
            align-items: flex-end;
            padding: 80px 60px;
        }

        .auth-hero-title {
            font-family: 'Syne', sans-serif;
            font-size: 54px;
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -2px;
            color: #8C3A00; /* Cokelat Warm */
            max-width: 480px;
            text-transform: uppercase;
        }

        /* Sisi Kanan: Form Register */
        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
        }

        .form-box {
            width: 100%;
            max-width: 420px;
        }

        .form-header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 12px;
            border-bottom: 1px solid #E2E8F0;
            margin-bottom: 32px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.2px;
            color: #4A5568;
            text-transform: uppercase;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #4A5568;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            border: none;
            border-bottom: 1px solid #CBD5E0;
            background: transparent;
            padding: 8px 0;
            font-size: 14px;
            font-weight: 500;
            color: #1A202C;
            outline: none;
            transition: border-color 0.25s ease;
        }

        .form-input::placeholder {
            color: #CBD5E0;
        }

        .form-input:focus {
            border-bottom-color: #1A202C;
        }

        /* Minat Chips */
        .chips-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .chip-item {
            font-size: 12px;
            font-weight: 600;
            color: #4A5568;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #E2E8F0;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
        }

        .chip-item input {
            display: none;
        }

        .chip-item:hover {
            border-color: #CBD5E0;
            background: #FFFFFF;
        }

        .chip-item.active {
            background: #1A202C;
            color: #FFFFFF;
            border-color: #1A202C;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            margin-top: 32px;
            background: transparent;
            border: 1.5px solid #1A202C;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #1A202C;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s ease;
            text-transform: uppercase;
        }

        .btn-submit:hover {
            background: #1A202C;
            color: #FFFFFF;
        }

        .auth-footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #718096;
            font-weight: 500;
        }

        .auth-footer-text a {
            color: #1A202C;
            font-weight: 700;
            text-decoration: underline;
        }

        /* ==========================================
           RWD MEDIA QUERIES (TABLET & MOBILE)
           ========================================== */
        @media (max-width: 992px) {
            .auth-left {
                padding: 60px 40px;
            }
            .auth-hero-title {
                font-size: 42px;
            }
        }

        @media (max-width: 768px) {
            body { 
                background: #FAFAFA; 
            }
            .auth-container { 
                flex-direction: column; 
            }
            .auth-left { 
                background: linear-gradient(180deg, 
                    #3B9EFF 0%, 
                    #7BC0FF 30%, 
                    #BDDCFF 60%, 
                    #EFF6FF 80%, 
                    #FAFAFA 100%
                );
                min-height: 280px;
                padding: 50px 24px 30px; 
                align-items: flex-end;
            }
            .auth-hero-title { 
                font-size: 34px; 
                letter-spacing: -1px;
            }
            .auth-right { 
                padding: 30px 20px 50px; 
            }
            .form-header-bar {
                margin-bottom: 28px;
            }
            .chip-item {
                font-size: 11px;
                padding: 6px 12px;
            }
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <!-- Sisi Kiri (Title) -->
        <div class="auth-left">
            <h1 class="auth-hero-title">BERGABUNG<br>BERSAMA<br>KAMI</h1>
        </div>

        <!-- Sisi Kanan (Form) -->
        <div class="auth-right">
            <div class="form-box">
                <div class="form-header-bar">
                    <span>Tulip Magazine</span>
                    <span>Est. 2026</span>
                </div>

                <form action="process-reg.php" method="POST">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap / Username</label>
                        <input type="text" name="username" class="form-input" placeholder="Jane Doe" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="form-input" placeholder="jane@example.com" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kata Sandi</label>
                        <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pilih Minatmu</label>
                        <div class="chips-grid">
                            <label class="chip-item">
                                <input type="checkbox" name="interests[]" value="Fashion & Beauty" onclick="this.parentElement.classList.toggle('active')">
                                Fashion & Beauty
                            </label>
                            <label class="chip-item">
                                <input type="checkbox" name="interests[]" value="Art & Photography" onclick="this.parentElement.classList.toggle('active')">
                                Art & Photography
                            </label>
                            <label class="chip-item">
                                <input type="checkbox" name="interests[]" value="Film & TV" onclick="this.parentElement.classList.toggle('active')">
                                Film & TV
                            </label>
                            <label class="chip-item">
                                <input type="checkbox" name="interests[]" value="Music" onclick="this.parentElement.classList.toggle('active')">
                                Music
                            </label>
                            <label class="chip-item">
                                <input type="checkbox" name="interests[]" value="Life & Culture" onclick="this.parentElement.classList.toggle('active')">
                                Life & Culture
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Buat Akun &rarr;
                    </button>
                </form>

                <p class="auth-footer-text">
                    Sudah punya akun? <a href="login.php">Masuk</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>