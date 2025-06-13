<?php
include 'connection.php';
user_require_login();

include 'templates/template_user.php';
header_navbar('Keranjang');

if (isset($_POST['submit'])) {
    $return_date = $_POST['return_date'];
    $products = $_POST['products'];

    $user_id = $_SESSION['user_id'];
    $code = 'PIN' . date('Ymd-His');
    
    $borrowing_id = mysqli_query($conn, "SELECT uuid() AS id");
    $borrowing_id = mysqli_fetch_assoc($borrowing_id)['id'];

    $insertQuery = mysqli_query($conn, "INSERT INTO borrowings (id, user_id, return_date, code, status) VALUES ('$borrowing_id', '$user_id', '$return_date', '$code', 'pending')");
    if ($insertQuery) {

        foreach ($products as $product_id) {
            mysqli_query($conn, "INSERT INTO borrowing_details (id, borrowing_id, product_id) VALUES (uuid(), '$borrowing_id', '$product_id')");
            mysqli_query($conn, "UPDATE products SET stock = stock - 1 WHERE id = '$product_id'");
        }
        header('Location: history.php');
        exit();
    } else {
        echo '<script>alert("Gagal membuat peminjaman!");</script>';
    }
}

$products = $_POST['products'];
if (!isset($products) || empty($products)) {
    echo "<script>
        alert('Keranjang peminjaman kosong!');
        window.location.href = 'index.php';
    </script>";
    exit();
}

$id_products = implode("','", $products);

$query = mysqli_query($conn, "SELECT p.*, c.name AS category FROM products p INNER JOIN categories c ON p.category_id = c.id WHERE p.id IN ('$id_products') ORDER BY p.name ASC");
?>

<center>
    <h4 class="my-4">Keranjang Peminjaman</h4>
</center>
<form method="post">
    <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless table-light align-middle" id="myTable">
            <thead class="table-light">
                <tr>
                    <th style="width: 10px;">No</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga/hari</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php
                $no = 1;
                $total_price = 0;
                while ($data = mysqli_fetch_assoc($query)) {
                ?>
                    <tr>
                        <td>
                            <input type="hidden" name="products[]" value="<?= $data['id'] ?>">
                            <?= $no ?>
                        </td>
                        <td><?= $data['name'] ?></td>
                        <td><?= $data['category'] ?></td>
                        <td>Rp. <?= $data['price'] ?></td>
                    </tr>
                <?php
                    $total_price += $data['price'];
                    $no++;
                }
                ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Jumlah</strong></td>
                    <td><strong>Rp. <?= $total_price ?></strong></td>
                </tr>
            </tbody>
        </table>

    </div>

    <h4 class="mt-3">Form Pengajuan</h4>
    <div class="mb-3">
        <label for="return_date" class="form-label">Perkiraan tanggal pegembalian</label>
        <?php
        $todayPlusOneHour = date('Y-m-d H:i', strtotime('+1 day +7 hour'));
        ?>
        <input type="datetime-local" class="form-control" id="return_date" min="<?= $todayPlusOneHour ?>" name="return_date" required>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Lanjutkan ke Checkout</button>
    <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
</form>

<?php
footer();
?>