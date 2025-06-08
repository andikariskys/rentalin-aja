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
                <h5>Kendaraan</h5>
                <p>Sewa mobil dan motor berkualitas untuk perjalanan nyaman, fleksibel, dan siap pakai kapan pun Anda butuh.</p>
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

<?php
footer();
?>