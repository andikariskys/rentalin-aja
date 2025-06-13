<?php
include 'connection.php';

include 'templates/template_user.php';
header_navbar('Rentalin Aja', 'dashboard');
?>

<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="assets/images/cars-suv.jpg" class="d-block object-fit-cover w-100" style="max-height: 300px; filter: brightness(50%);" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>Ingin sewa barang? RentalinAja</h5>
                <p>RentalinAja, aplikasi untuk menyewa dan menyewakan berbagai barang dengan mudah, cepat, dan aman. Cocok untuk kebutuhan pribadi maupun usaha tanpa harus membeli barang baru.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets/images/tent-camp.jpg" class="d-block object-fit-cover w-100" style="max-height: 300px; filter: brightness(50%);" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>Peralatan Camping</h5>
                <p>Nikmati petualangan alam tanpa repot dengan perlengkapan camping lengkap dan praktis siap sewa.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets/images/noodle-maker.jpg" class="d-block object-fit-cover w-100" style="max-height: 300px; filter: brightness(50%);" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>Lainnya</h5>
                <p>Mau jalan-jalan, seru-seruan di alam, atau sekadar butuh alat bantu harian? Semua bisa disewa di sini, tinggal pilih!</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <?php
                $query = mysqli_query($conn, "SELECT c.*, COUNT(p.id) as count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id ORDER BY c.name ASC");
                while ($category = mysqli_fetch_array($query)) {
                    if ($category['count'] != 0) { ?>
                        <a class="nav-link" href="#<?= $category['id'] ?>"><?= $category['name'] ?></a>
                <?php }
                } ?>
            </div>
        </div>
    </div>
</nav>

<form id="form-cart" action="checkout.php" method="post">
    <?php
    $query = mysqli_query($conn, "SELECT c.*, COUNT(p.id) as count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id ORDER BY c.name ASC");
    while ($category = mysqli_fetch_array($query)) {
        if ($category['count'] != 0) { ?>
            <hr id="<?= $category['id'] ?>">
            <h4><?= $category['name'] ?></h4>
            <p><?= $category['description'] ?></p>

            <?php
            $query2 = mysqli_query($conn, "SELECT * FROM products WHERE category_id = '" . $category['id'] . "' ORDER BY name ASC");
            while ($product = mysqli_fetch_array($query2)) { ?>
                <div class="row">
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <div class="card" style="width: 100%;">
                            <img src="assets/product/<?= $product['image'] ?>" class="card-img-top object-fit-cover" alt="<?= $product['image'] ?>" style="width:100%; height:200px; object-fit:cover; object-position:center;">
                            <div class="card-body">
                                <h6 class="card-title"><?= $product['name'] ?></h6>
                                <p class="card-text fs-6"><?= $product['description'] ?></p>
                                <p class="card-text fst-italic"><span class="fw-bold">Rp. <?= number_format($product['price']) ?></span> | Stok: <?= $product['stock'] ?></p>
                                <input type="checkbox" class="btn btn-check" id="<?= $product['id'] ?>" name="products[]" value="<?= $product['id'] ?>" autocomplete="off">
                                <label class="btn btn-outline-primary <?= $product['stock'] == 0 || !isset($_SESSION['user_id']) ? 'disabled' : '' ?>" for="<?= $product['id'] ?>" style="width: 100%;"><i class="fa-solid fa-cart-plus"></i></label><br>
                            </div>
                        </div>
                    </div>
                </div>
    <?php }
        }
    }
    ?>
</form>

<script>
    btnCart = document.getElementById('btn-cart');
    formCart = document.getElementById('form-cart');

    btnCart.addEventListener('click', function() {
        formCart.submit();
    });
</script>

<?php
footer();
?>