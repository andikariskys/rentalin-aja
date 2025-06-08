<?php

function header_navbar($active)
{
?>
    <!DOCTYPE html>
    <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= $active ?></title>
            <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
            <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
            <link rel="stylesheet" href="../assets/datatables/datatables.min.css">
        </head>

        <body>
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">rentalinAja</a>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav me-0 ms-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold <?php if ($active == 'Dashboard'){ echo 'active disabled'; } ?>" href="index.php" aria-current="page">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold <?php if ($active == 'Kategori'){ echo 'active disabled'; } ?>" href="categories.php">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold <?php if ($active == 'Admin'){ echo 'active disabled'; } ?>" href="admins.php">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="fw-semibold btn btn-danger" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container" style="min-height: calc(100dvh - 100px);">
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

        <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="../assets/bootstrap/js/jquery.min.js"></script>
        <script src="../assets/datatables/datatables.min.js"></script>
        <script>
            let table = new DataTable('#myTable');
        </script>
    </body>

    </html>
    <?php
}