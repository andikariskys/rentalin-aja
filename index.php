<?php
include 'connection.php';

include 'templates/template_user.php';
header_navbar('Rentalin Aja', 'dashboard');
?>

<section class="text-white d-flex align-items-center justify-content-center px-2" style="background-image: url('assets/images/tent-camp.jpg'); height: 400px; background-position: center; background-repeat: no-repeat; background-size: cover; position: relative; box-shadow: inset 0 0 0 2000px rgba(0, 0, 0, 0.5); text-shadow: 1px 1px #000;">
    <div class="text-center">
        <h1 class="display-5">Solusi Kebutuhan Anda</h1>
        <p>
            RentalinAja, aplikasi untuk menyewa dan menyewakan berbagai barang dengan mudah, cepat, dan aman. <br>Cocok untuk kebutuhan pribadi maupun usaha tanpa harus membeli barang baru.
        </p>
    </div>
</section>

<div class="row text-center py-4">
    <div class="col-12">
        <h2 class="display-5">Mengapa Memilih Kami?</h2>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 py-3">
        <span class="fa-stack fa-2x">
            <i class="fas fa-circle fa-stack-2x"></i>
            <i class="fas fa-bolt fa-stack-1x text-white"></i>
        </span>
        <h3 class="mt-4">Mudah & Cepat</h3>
        <p>Sewa barang dalam hitungan menit, tanpa ribet dan bisa langsung dari genggaman Anda.</p>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 py-3">
        <span class="fa-stack fa-2x">
            <i class="fas fa-circle fa-stack-2x"></i>
            <i class="fas fa-star fa-stack-1x text-white"></i>
        </span>
        <h3 class="mt-4">Barang Berkualitas</h3>
        <p>Semua barang dicek sebelum dikirim, bersih, terawat, dan siap digunakan kapan saja.</p>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 py-3">
        <span class="fa-stack fa-2x">
            <i class="fas fa-circle fa-stack-2x"></i>
            <i class="fas fa-tags fa-stack-1x text-white"></i>
        </span>
        <h3 class="mt-4">Harga Terjangkau</h3>
        <p>Harga sewa jelas, transparan, tanpa biaya tambahan, dan tanpa biaya tersembunyi.</p>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 py-3">
        <span class="fa-stack fa-2x">
            <i class="fas fa-circle fa-stack-2x"></i>
            <i class="fas fa-headset fa-stack-1x text-white"></i>
        </span>
        <h3 class="mt-4">Operator Responsive</h3>
        <p>Tim support siap membantu setiap hari kerja - Ada kendala? Tanya-tanya? atau yang lain? hub. 0881xxxx806</p>
    </div>
</div>

<h2 class="display-5 text-center">Daftar Produk</h2>
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

            <div class="row text-center">
                <?php
                $query2 = mysqli_query($conn, "SELECT * FROM products WHERE category_id = '" . $category['id'] . "' ORDER BY name ASC");
                while ($product = mysqli_fetch_array($query2)) { ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 d-flex">
                        <div class="card w-100 h-100 d-flex flex-column">
                            <!-- Gambar selalu di atas -->
                            <img src="assets/product/<?= $product['image'] ?>" class="card-img-top object-fit-cover" alt="<?= $product['image'] ?>" style="height: 200px; object-fit: cover; object-position: center;">

                            <!-- Konten -->
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title fs-5 fw-bold"><?= $product['name'] ?></h6>
                                <p class="card-text fs-6"><?= $product['description'] ?></p>
                                <p class="card-text"><span class="fw-bold">Rp. <?= number_format($product['price']) ?></span> | Stok: <span class="fw-bold <?php if($product['stock'] <= 3) {echo 'text-danger'; } ?>"><?= $product['stock'] ?></span></p>

                                <!-- Tombol dan login pesan di bagian bawah -->
                                <div class="mt-auto">
                                    <hr>
                                    <input type="checkbox" class="btn btn-check" id="<?= $product['id'] ?>" name="products[]" value="<?= $product['id'] ?>" autocomplete="off">
                                    <label class="btn btn-outline-primary <?= $product['stock'] == 0 || !isset($_SESSION['user_id']) ? 'disabled' : '' ?>" for="<?= $product['id'] ?>"><i class="fa-solid fa-cart-plus"></i> Tambah Produk</label><br>
                                    <?php if (!isset($_SESSION['user_id'])) { ?>
                                        <p class="text-danger mb-0 fst-italic">*login first</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

    <?php }
    }
    ?>
</form>

<?php if (isset($_SESSION['user_id'])) { ?>
    <script>
        btnCart = document.getElementById('btn-cart');
        formCart = document.getElementById('form-cart');

        btnCart.addEventListener('click', function() {
            formCart.submit();
        });
    </script>
<?php } ?>

<?php
footer();
?>