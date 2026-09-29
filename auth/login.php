<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — TULIP MAGAZINE</title>
    
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
                #F871B5 0%, 
                #FFA2D3 18%, 
                #FFD6EB 32%, 
                #FFF0F7 42%, 
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
            color: #0F5327; /* Hijau Deep */
            max-width: 480px;
            text-transform: uppercase;
        }

        /* Sisi Kanan: Form Login */
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
            margin-bottom: 36px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.2px;
            color: #4A5568;
            text-transform: uppercase;
        }

        .form-group {
            margin-bottom: 26px;
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
            padding: 10px 0;
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

        .btn-submit {
            width: 100%;
            padding: 14px;
            margin-top: 36px;
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
            margin-top: 24px;
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
                    #F871B5 0%, 
                    #FFA2D3 30%, 
                    #FFD6EB 60%, 
                    #FFF0F7 80%, 
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
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <!-- Sisi Kiri (Title) -->
        <div class="auth-left">
            <h1 class="auth-hero-title">SELAMAT<br>DATANG<br>KEMBALI</h1>
        </div>

        <!-- Sisi Kanan (Form) -->
        <div class="auth-right">
            <div class="form-box">
                <div class="form-header-bar">
                    <span>Tulip Magazine</span>
                    <span>Est. 2026</span>
                </div>

                <form action="process-login.php" method="POST">
                    <div class="form-group">
                        <label class="form-label">Email / Username</label>
                        <input type="text" name="email_username" class="form-input" placeholder="Masukkan email / username anda" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kata Sandi</label>
                        <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn-submit">
                        Masuk &rarr;
                    </button>
                </form>

                <p class="auth-footer-text">
                    Belum punya akun? <a href="register.php">Daftar.</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>