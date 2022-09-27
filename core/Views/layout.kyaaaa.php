<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="<?= url('assets/css') ?>/bootstrap.min.css" rel="stylesheet">
    <script src="<?= url('assets/js') ?>/turbolinks.min.js"></script>
</head>
<body class="bg-dark">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a href="/" class="navbar-brand h1">Kyaaaa</a>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="/docs" class="nav-link btn btn-light text-dark fw-bold px-4">Docs</a>
                </li>

            </ul>
        </div>
    </nav>
    <div class="container text-white">
        <div class="row">
            <?= $content; ?>
        </div>
    </div>
<script src="<?= url('assets/js') ?>/bootstrap.bundle.min.js"></script>
</body>
</html>