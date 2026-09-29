<?php

session_start();

require_once 'config/database.php';

/*
|--------------------------------------------------------------------------
| Ambil ID artikel dari URL
| Contoh:
| detail.php?id=1
|--------------------------------------------------------------------------
*/

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


/*
|--------------------------------------------------------------------------
| Ambil data artikel dari database
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $conn,
    "SELECT 
        articles.*,
        categories.name AS cat_name,
        categories.slug AS cat_slug
     FROM articles
     JOIN categories 
        ON articles.category_id = categories.id
     WHERE articles.id = $id
     LIMIT 1"
);

$article = mysqli_fetch_assoc($query);


/*
|--------------------------------------------------------------------------
| Kalau artikel tidak ditemukan
|--------------------------------------------------------------------------
*/

if (!$article) {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($article['title']); ?>
        — TULIP MAGAZINE
    </title>


    <!-- Google Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap"
        rel="stylesheet"
    >


    <style>

        /* =====================================================
           RESET
        ===================================================== */

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }


        /* =====================================================
           BODY
        ===================================================== */

        body {
            background: #FAFAFA;
            color: #1A202C;
            padding: 50px 20px;
        }


        /* =====================================================
           CONTAINER
        ===================================================== */

        .article-container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;

            background: #FFFFFF;

            padding: 60px;

            border-radius: 16px;

            border: 1px solid #E2E8F0;

            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }


        /* =====================================================
           BACK BUTTON
        ===================================================== */

        .back-link {
            display: inline-block;

            text-decoration: none;

            font-size: 13px;

            font-weight: 700;

            color: #718096;

            margin-bottom: 30px;

            text-transform: uppercase;

            letter-spacing: 1px;

            transition: 0.2s ease;
        }

        .back-link:hover {
            color: #0F5327;
        }


        /* =====================================================
           CATEGORY BADGE
        ===================================================== */

        .badge {
            display: inline-block;

            background: #FFD6EB;

            color: #0F5327;

            padding: 7px 13px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: 800;

            text-transform: uppercase;

            margin-bottom: 16px;
        }


        /* =====================================================
           TITLE
        ===================================================== */

        h1.article-title {
            font-family: 'Syne', sans-serif;

            font-size: 42px;

            line-height: 1.2;

            font-weight: 800;

            color: #0F5327;

            margin-bottom: 18px;
        }


        /* =====================================================
           META / AUTHOR / DATE
        ===================================================== */

        .article-meta {
            font-size: 13px;

            color: #718096;

            margin-bottom: 30px;

            padding-bottom: 20px;

            border-bottom: 1px solid #E2E8F0;
        }

        .article-meta strong {
            color: #2D3748;
        }


        /* =====================================================
           ARTICLE IMAGE
        ===================================================== */

        .article-image {
            width: 100%;

            max-height: 520px;

            object-fit: cover;

            display: block;

            border-radius: 12px;

            margin-bottom: 35px;
        }


        /* =====================================================
           ARTICLE CONTENT
        ===================================================== */

        .article-content {
            font-size: 16px;

            line-height: 1.9;

            color: #2D3748;

            white-space: pre-line;
        }


        .article-content p {
            margin-bottom: 20px;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 768px) {

            body {
                padding: 20px 12px;
            }

            .article-container {
                padding: 30px 22px;
                border-radius: 12px;
            }

            h1.article-title {
                font-size: 31px;
            }

            .article-content {
                font-size: 15px;
                line-height: 1.8;
            }

            .article-image {
                max-height: 400px;
            }

        }


        @media (max-width: 480px) {

            .article-container {
                padding: 25px 18px;
            }

            h1.article-title {
                font-size: 27px;
            }

            .article-meta {
                font-size: 12px;
            }

        }

    </style>

</head>


<body>

    <main class="article-container">


        <!-- =================================================
             KEMBALI
        ================================================== -->

        <a
            href="category.php?slug=<?php echo urlencode($article['cat_slug']); ?>"
            class="back-link"
        >
            ← Kembali ke <?php echo htmlspecialchars($article['cat_name']); ?>
        </a>


        <!-- =================================================
             KATEGORI
        ================================================== -->

        <div class="badge">

            <?php echo htmlspecialchars($article['cat_name']); ?>

        </div>


        <!-- =================================================
             JUDUL
        ================================================== -->

        <h1 class="article-title">

            <?php echo htmlspecialchars($article['title']); ?>

        </h1>


        <!-- =================================================
             PENULIS + TANGGAL
        ================================================== -->

        <div class="article-meta">

            Oleh
            <strong>
                <?php echo htmlspecialchars($article['author']); ?>
            </strong>

            &nbsp; • &nbsp;

            Dipublikasikan pada

            <?php
            echo date(
                'd M Y',
                strtotime($article['created_at'])
            );
            ?>

        </div>


        <!-- =================================================
             FOTO ARTIKEL
        ================================================== -->

        <?php if (!empty($article['image'])): ?>

            <img
                src="assets/images/<?php echo htmlspecialchars($article['image']); ?>"
                alt="<?php echo htmlspecialchars($article['title']); ?>"
                class="article-image"
            >

        <?php endif; ?>


        <!-- =================================================
             ISI ARTIKEL
        ================================================== -->

        <article class="article-content">

            <?php
            echo nl2br(
                htmlspecialchars($article['content'])
            );
            ?>

        </article>


    </main>

</body>

</html>