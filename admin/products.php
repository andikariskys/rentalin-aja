<?php
include '../connection.php';
admin_require_login();

include '../templates/template_admin.php';
header_navbar('Produk');

if (isset($_GET['delete'])) {
    $productId = $_GET['delete'];
    
    $query = mysqli_query($conn, "DELETE FROM products WHERE id = '$productId'");
    $image = $_GET['image'];
    unlink("../assets/product/" . $image);

    if ($query) {
        $_SESSION['success'] = "Produk berhasil dihapus.";
        header("Location: products.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal menghapus produk.";
        header("Location: products.php");
        exit();
    }
}

$adminId = $_SESSION['admin_id'];
$query = mysqli_query($conn, "SELECT products.*, categories.name AS category FROM products INNER JOIN categories ON products.category_id = categories.id ORDER BY products.name ASC");
?>

<center>
    <h1 class="mt-5">Data Produk</h1>
</center>

<a href="add_edit_product.php" class="btn btn-primary mb-2"><i class="fa-solid fa-plus"></i> Tambah Produk</a>

<?php
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $_SESSION['success'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['error']);
}
?>

<script>
    var alertList = document.querySelectorAll(".alert");
    alertList.forEach(function (alert) {
        new bootstrap.Alert(alert);
    });
</script>

<div class="table-responsive">
    <table class="table table-striped table-hover table-borderless table-light align-middle" id="myTable">
        <thead class="table-light">
            <tr>
                <th style="width: 10px;">No</th>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga/hari</th>
                <th>Jml. Stok</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
        <?php
        $no = 1;
        while ($data = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
                <td><?= $no ?></td>
                <td>
                    <img src="../assets/product/<?= $data['image'] ?>" alt="<?= $data['name'] ?>" class="img-fluid img-thumbnail" style="max-width: 100px; max-height: 100px;">
                </td>
                <td><?= $data['name'] ?></td>
                <td><?= $data['description'] ?></td>
                <td>Rp. <?= number_format($data['price']) ?></td>
                <td><?= $data['stock'] ?></td>
                <td><?= $data['category'] ?></td>
                <td>
                    <a href="add_edit_product.php?id=<?= $data['id'] ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                    <a href="products.php?delete=<?= $data['id'] ?>&image=<?= $data['image'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')"><i class="fa-solid fa-trash"></i> Hapus</a>
                </td>
            </tr>
            <?php
            $no++;
        }
        ?>
        </tbody>
    </table>
</div>


<?php
footer();
?>