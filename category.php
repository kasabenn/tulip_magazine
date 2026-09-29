<?php

session_start();

require_once 'config/database.php';

/*
|--------------------------------------------------------------------------
| AMBIL SLUG KATEGORI DARI URL
|--------------------------------------------------------------------------
| Contoh:
| category.php?slug=fashion-beauty
|--------------------------------------------------------------------------
*/

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if ($slug === '') {
    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| DATA KATEGORI + ARTIKEL
|--------------------------------------------------------------------------
*/

$categories = [

    /*
    |--------------------------------------------------------------------------
    | FASHION & BEAUTY
    |--------------------------------------------------------------------------
    */

    'fashion-beauty' => [
        'name' => 'Fashion & Beauty',

        'articles' => [

            [
                'file' => 'artikel-01.php',
                'title' => 'Stop! Jangan Asal Ikutin Tren Skincare',
                'author' => 'Farah Amelia',
                'image' => 'skincare.png',
                'excerpt' => 'Viral belum tentu cocok. Sebelum ikut-ikutan skincare yang sedang ramai, kenali dulu apa yang sebenarnya dibutuhkan kulitmu.'
            ],

            [
                'file' => 'artikel-02.php',
                'title' => 'Kita Butuh Baju Baru, atau Cuma Butuh Tren Baru?',
                'author' => 'Farah Amelia',
                'image' => 'fashion-trend.png',
                'excerpt' => 'Ketika tren datang lebih cepat daripada kita sempat memakainya.'
            ]

        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | ART & PHOTOGRAPHY
    |--------------------------------------------------------------------------
    */

    'art-photography' => [
        'name' => 'Art & Photography',

        'articles' => [

            [
                'file' => 'artikel-03.php',
                'title' => 'Pameran Art for Peace and a Better Future Hadirkan...',
                'author' => 'Rawdiatul Jannah',
                'image' => 'art-for-peace.jpeg',
                'excerpt' => 'Pesan perdamaian dan masa depan yang disuarakan melalui pameran seni.'
            ],

            [
                'file' => 'artikel-04.php',
                'title' => 'Pameran A Portrait of Human Art Tampilkan 40 Karya...',
                'author' => 'Rawdiatul Jannah',
                'image' => 'darwis-triadi.jpeg',
                'excerpt' => 'Apresiasi seni potret manusia tingkat tinggi yang menghadirkan berbagai karya.'
            ]

        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | MUSIC
    |--------------------------------------------------------------------------
    */

    'music' => [
        'name' => 'Music',

        'articles' => [

            [
                'file' => 'artikel-05.php',
                'title' => 'That One Song: Kenapa Kita Bisa Begitu Terikat dengan...',
                'author' => 'Puja Adelia S',
                'image' => 'that-one-song.jpg',
                'excerpt' => 'Sains psikologi musik di balik lagu-lagu yang terasa begitu dekat dengan kita.'
            ],

            [
                'file' => 'artikel-06.php',
                'title' => 'Kenapa Lagu-Lagu Lama Kembali Viral? When the Past...',
                'author' => 'Puja Adelia S',
                'image' => 'old-songs-viral-1.jpg',
                'excerpt' => 'Fenomena kecanduan nostalgia dan alasan lagu lama kembali viral.'
            ]

        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | FILM & TV
    |--------------------------------------------------------------------------
    */

    'film-tv' => [
        'name' => 'Film & TV',

        'articles' => [

            [
                'file' => 'artikel-07.php',
                'title' => 'Nico Robin: Membaca Kebenaran, Merayakan Perlawanan...',
                'author' => 'Mutya Naffisa K',
                'image' => 'nico-robin.jpg',
                'excerpt' => 'Eksplorasi mendalam mengenai karakter Nico Robin dan kisah perjalanannya.'
            ],

            [
                'file' => 'artikel-08.php',
                'title' => 'Coralie Fargeat: Mengklaim Ulang Horor demi Kritik...',
                'author' => 'Mutya Naffisa K',
                'image' => 'coralie-fargeat.jpg',
                'excerpt' => 'Eksplorasi sinematik radikal dan bagaimana horor digunakan sebagai kritik.'
            ]

        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | LIFE & CULTURE
    |--------------------------------------------------------------------------
    */

    'life-culture' => [
        'name' => 'Life & Culture',

        'articles' => [

            [
                'file' => 'artikel-09.php',
                'title' => 'Goth Girl Era: Ketika Gaya Gelap Menjadi Bentuk Ekspresi',
                'author' => 'Sabrina Enejelika',
                'image' => 'goth-girl.jpg',
                'excerpt' => 'Subkultur goth yang kembali menguat di kalangan anak muda.'
            ],

            [
                'file' => 'artikel-10.php',
                'title' => 'The Offline Era: Ketika Gen Z Mulai Lelah Hidup di...',
                'author' => 'Sabrina Enejelika',
                'image' => 'offline-era.jpg',
                'excerpt' => 'Gaya hidup baru dengan mengurangi screen time dan kembali menikmati kehidupan offline.'
            ]

        ]
    ]

];


/*
|--------------------------------------------------------------------------
| CEK SLUG
|--------------------------------------------------------------------------
*/

if (!isset($categories[$slug])) {
    header("Location: index.php");
    exit;
}

$category = $categories[$slug];

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
        <?php echo htmlspecialchars($category['name']); ?> — TULIP MAGAZINE
    </title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;

            background:
                linear-gradient(
                    to bottom,
                    #9BE9B1 0%,
                    #F8FFF9 48%,
                    #8BE49E 100%
                );

            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #101010;
        }

        .category-container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 45px 25px 80px;
        }

        /* TOP BAR */

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 85px;
        }

        .logo {
            font-size: 32px;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .back {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            background: #ffffff;
            color: #111111;

            text-decoration: none;

            padding: 14px 23px;
            border-radius: 999px;

            font-size: 15px;
            font-weight: 700;

            transition: 0.2s ease;
        }

        .back:hover {
            transform: translateY(-2px);

            box-shadow:
                0 8px 20px rgba(0, 0, 0, 0.08);
        }

        /* CATEGORY HEADER */

        .category-label {
            color: #16804B;

            font-size: 14px;
            font-weight: 800;

            letter-spacing: 1.5px;

            margin-bottom: 12px;
        }

        h1 {
            margin: 0 0 14px;

            font-size: 58px;
            line-height: 1.05;

            font-weight: 900;
            letter-spacing: -2px;
        }

        .category-description {
            margin: 0 0 45px;

            color: #466052;

            font-size: 17px;
            line-height: 1.6;
        }

        /* ARTICLE WRAPPER */

        .articles-wrapper {
            width: 100%;

            background: rgba(255, 255, 255, 0.88);

            border-radius: 28px;

            padding: 28px;

            box-shadow:
                0 10px 35px rgba(0, 0, 0, 0.04);
        }

        /* ARTICLE CARD */

        .article-card {
            display: grid;

            grid-template-columns: 400px minmax(0, 1fr);

            align-items: center;

            gap: 30px;

            width: 100%;

            padding: 20px;

            margin-bottom: 22px;

            background: #ffffff;

            border-radius: 22px;

            color: inherit;
            text-decoration: none;

            overflow: hidden;

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .article-card:last-child {
            margin-bottom: 0;
        }

        .article-card:hover {
            transform: translateY(-4px);

            box-shadow:
                0 12px 30px rgba(0, 0, 0, 0.09);
        }

        /* ARTICLE IMAGE */

        .article-image {
            display: block;

            width: 100%;
            height: 255px;

            object-fit: cover;

            border-radius: 16px;

            background: #eeeeee;
        }

        /* ARTICLE INFO */

        .article-info {
            min-width: 0;

            padding: 8px 8px 8px 0;
        }

        .article-info h2 {
            margin: 0 0 15px;

            font-size: 30px;
            line-height: 1.2;

            font-weight: 800;

            letter-spacing: -0.5px;

            overflow-wrap: break-word;
        }

        .excerpt {
            margin: 0 0 18px;

            color: #59665D;

            font-size: 15px;
            line-height: 1.7;
        }

        .author {
            color: #16804B;

            font-size: 13px;
            line-height: 1.5;

            font-weight: 700;
        }

        /* TABLET */

        @media (max-width: 900px) {

            .article-card {
                grid-template-columns:
                    300px minmax(0, 1fr);

                gap: 22px;
            }

            .article-image {
                height: 230px;
            }

            .article-info h2 {
                font-size: 26px;
            }
        }

        /* MOBILE */

        @media (max-width: 700px) {

            .category-container {
                padding:
                    30px 16px 60px;
            }

            .top-bar {
                margin-bottom: 55px;
            }

            .logo {
                font-size: 27px;
            }

            .back {
                padding: 11px 16px;
                font-size: 13px;
            }

            h1 {
                font-size: 40px;
                letter-spacing: -1px;
            }

            .category-description {
                font-size: 15px;
                margin-bottom: 30px;
            }

            .articles-wrapper {
                padding: 15px;
                border-radius: 22px;
            }

            .article-card {
                grid-template-columns: 1fr;

                gap: 18px;

                padding: 15px;

                border-radius: 18px;
            }

            .article-image {
                width: 100%;
                height: 220px;

                border-radius: 14px;
            }

            .article-info {
                padding: 0 3px 5px;
            }

            .article-info h2 {
                font-size: 24px;
                line-height: 1.25;
            }

            .excerpt {
                font-size: 14px;
                line-height: 1.65;
            }
        }

        /* HP KECIL */

        @media (max-width: 480px) {

            .top-bar {
                align-items: flex-start;
                gap: 15px;
            }

            .back {
                white-space: nowrap;
            }

            h1 {
                font-size: 34px;
            }

            .article-image {
                height: 190px;
            }

            .article-info h2 {
                font-size: 22px;
            }
        }

    </style>

</head>


<body>

<div class="category-container">

    <!-- TOP BAR -->

    <div class="top-bar">

        <div class="logo">
            TULIP.
        </div>

        <a
            href="index.php"
            class="back"
        >
            ← Kembali ke Beranda
        </a>

    </div>


    <!-- CATEGORY HEADER -->

    <div class="category-label">
        TULIP MAGAZINE
    </div>

    <h1>
        <?php echo htmlspecialchars($category['name']); ?>
    </h1>

    <div class="category-description">

        Temukan berbagai cerita, inspirasi,
        dan artikel pilihan dari kategori
        <?php echo htmlspecialchars($category['name']); ?>.

    </div>


    <!-- ARTICLES -->

    <div class="articles-wrapper">

        <?php foreach ($category['articles'] as $article): ?>

            <a
                href="artikel/<?php echo htmlspecialchars($article['file']); ?>"
                class="article-card"
            >

                <img
                    src="assets/images/<?php echo htmlspecialchars($article['image']); ?>"
                    alt="<?php echo htmlspecialchars($article['title']); ?>"
                    class="article-image"
                >

                <div class="article-info">

                    <h2>
                        <?php echo htmlspecialchars($article['title']); ?>
                    </h2>

                    <div class="excerpt">
                        <?php echo htmlspecialchars($article['excerpt']); ?>
                    </div>

                    <div class="author">
                        Oleh
                        <?php echo htmlspecialchars($article['author']); ?>
                    </div>

                </div>

            </a>

        <?php endforeach; ?>

    </div>

</div>

</body>

</html>