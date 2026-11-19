<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Shop Online') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(base_url('assets/css/style.css')) ?>" rel="stylesheet">
</head>
<body>
<header class="bg-white border-bottom shadow-sm">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= e(base_url('product/search')) ?>">Shop<span class="text-primary">Online</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainMenu">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= e(base_url('product/search')) ?>">Sản phẩm</a></li>
                </ul>
                <form class="d-flex my-2 my-lg-0" method="GET" action="<?= e(base_url('product/search')) ?>">
                    <input class="form-control me-2" type="search" name="q"
                           placeholder="Tìm sản phẩm, mã SKU..." value="<?= e($_GET['q'] ?? '') ?>">
                    <button class="btn btn-primary" type="submit">Tìm</button>
                </form>
            </div>
        </div>
    </nav>
</header>

<main class="container py-4">
    <?php $content(); ?>
</main>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <div class="container small">ShopOnline - Bài thực hành Buổi 05 PHP MVC</div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
