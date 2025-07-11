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
        <nav class="navbar navbar-expand-sm navbar-light bg-light sticky-top">
            <div class="container">
                <a class="navbar-brand fw-bold" href="#">RentalinAja</a>
                <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav me-0 ms-auto mt-2 mt-lg-0">
                        <?php if (isset($_SESSION['user_id'])) {
                            if ($title == 'Rentalin Aja') { ?>
                                <li class="nav-item">
                                    <button class="nav-link fw-semibold btn btn-light" id="btn-cart"><i class="fa-solid fa-cart-shopping"></i></button>
                                </li>
                            <?php } ?>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold <?php if ($title == 'Rentalin Aja') {
                                                                    echo 'active disabled';
                                                                } ?>" href="index.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold <?php if ($title == 'Riwayat') {
                                                                    echo 'active disabled';
                                                                } ?>" href="history.php">Riwayat</a>
                            </li>
                            <li class="nav-item">
                                <a class="fw-semibold btn btn-danger" href="logout.php">Logout</a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a class="fw-semibold btn btn-primary" href="login.php">Login</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container" style="min-height: calc(100dvh - 100px);">
        <?php
    }

    function footer()
    {
        ?>
        </div>
        <footer class="mt-3 py-3 bg-light">
            <div class="container text-center">
                <p class="text-muted">&copy; 2025 - Andika Risky Septiawan - <a class="text-decoration-none" target="_blank" href="https://andikariskys.my.id">andikariskys.my.id</a></p>
            </div>
        </footer>

        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    </body>

    </html>
<?php
    }
