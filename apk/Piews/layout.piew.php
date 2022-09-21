<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kyaaaa~</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a href="/" class="navbar-brand h1">Kyaaaa</a>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="/docs" class="nav-link">Docs</a>
                </li>

            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <?= $content; ?>
        </div>
    </div>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>