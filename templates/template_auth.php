<?php

function header_navbar($title)
{
?>
    <!DOCTYPE html>
    <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= $title ?></title>
            <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
            <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
        </head>

        <body>
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">rentalinAja</a>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav me-0 ms-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="index.php">Dashboard</a>
                    </li>
                    <?php if ($title != 'Login') { ?>
                    <li class="nav-item">
                        <a class="fw-semibold btn btn-primary" href="login.php">Login</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container d-flex align-items-center justify-content-center" style="min-height: calc(100dvh - 100px);">
    <?php
}

function footer() {
    ?>
        </div>
        <footer>
            <div class="container text-center">
                <p class="text-muted">&copy; 2025 - Andika Risky Septiawan - <a class="text-decoration-none" target="_blank" href="https://andikariskys.my.id">andikariskys.my.id</a></p>
            </div>
        </footer>

        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    </body>

    </html>
    <?php
}