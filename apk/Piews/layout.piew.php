<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kyaaaa~</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a href="/" class="navbar-brand h1">Kyaaaa</a>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <button href="/docs" class="nav-link btn btn-light text-dark fw-bold px-4">Docs</button>
                </li>

            </ul>
        </div>
    </nav>
    <div class="container text-white">
        <div class="row">
            <?= $content; ?>
        </div>
    </div>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>