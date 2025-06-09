<?php
include '../connection.php';
admin_require_login();

include '../templates/template_admin.php';
header_navbar('Produk');

$adminId = $_SESSION['admin_id'];
$query = mysqli_query($conn, "SELECT * FROM products");
?>

<center>
    <h1 class="mt-5">Data Produk</h1>
</center>

<?php
footer();
?>