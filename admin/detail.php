<?php
include '../connection.php';
admin_require_login();

include '../templates/template_admin.php';
header_navbar('Detail Peminjaman');

if (isset($_POST['submit'])) {
    $identity_type = $_POST['identity_type'];
    $identity_number = $_POST['identity_number'];
    $identity_name = $_POST['identity_name'];
    $id_borrowing = $_POST['id_borrowing'];


    $updateQuery = mysqli_query($conn, "UPDATE borrowings SET identity_type='$identity_type', identity_number='$identity_number', identity_name='$identity_name', status='approved' WHERE id='$id_borrowing'");
    if ($updateQuery) {
        echo '<script>alert("Data pengambilan berhasil disimpan!"); window.location.href="detail.php?id=' . $id_borrowing . '";</script>';
    } else {
        echo '<script>alert("Gagal menyimpan data pengambilan!");</script>';
    }
}

if (isset($_POST['returned'])) {
    $id_borrowing = $_POST['id_borrowing'];
    $overdue = $_POST['overdue'];

    if ($overdue == '1') {
        $updateQuery = mysqli_query($conn, "UPDATE borrowings SET status='overdue' WHERE id='$id_borrowing'");
    } else {
        $updateQuery = mysqli_query($conn, "UPDATE borrowings SET status='returned' WHERE id='$id_borrowing'");
    }

    $productQuery = mysqli_query($conn, "SELECT product_id FROM borrowing_details WHERE borrowing_id = '$id_borrowing'");
    while ($product = mysqli_fetch_assoc($productQuery)) {
        $productId = $product['product_id'];
        mysqli_query($conn, "UPDATE products SET stock = stock + 1 WHERE id = '$productId'");
    }

    if ($updateQuery) {
        echo '<script>window.location.href="detail.php?id=' . $id_borrowing . '";</script>';
    } else {
        echo '<script>alert("Gagal menandai peminjaman sebagai sudah dikembalikan!");</script>';
    }
}

$idBorrow = $_GET['id'];
if (!isset($idBorrow) || empty($idBorrow)) {
    header('Location: index.php');
    exit();
}

$query = mysqli_query($conn, "SELECT * FROM borrowings WHERE id = '$idBorrow'");
$borrowing = mysqli_fetch_assoc($query);

$query = mysqli_query($conn, "SELECT users.* FROM borrowings INNER JOIN users ON borrowings.user_id = users.id WHERE borrowings.id = '$idBorrow'");
$user = mysqli_fetch_assoc($query);

$query = mysqli_query($conn, "SELECT products.*, categories.name AS category FROM borrowing_details
        INNER JOIN products ON borrowing_details.product_id = products.id 
        INNER JOIN categories ON products.category_id = categories.id
        WHERE borrowing_id = '$idBorrow'");

$total_price = 0;
$duration = floor((strtotime($borrowing['return_date']) - strtotime($borrowing['borrow_date'])) / (60 * 60 * 24));
?>

<center>
    <h2 class="mt-5">Detail Peminjaman</h2>
</center>

<table>
    <tr>
        <th>ID Peminjaman</th>
        <td>:</td>
        <td>#<?= $borrowing['code']; ?></td>
    </tr>
    <tr>
        <th>Tanggal Peminjaman</th>
        <td>:</td>
        <td><?= date('d F Y H:i:s', strtotime($borrowing['borrow_date'])); ?></td>
    </tr>
    <tr>
        <th>Estimasi Tanggal Pengembalian</th>
        <td>:</td>
        <td><?= date('d F Y H:i:s', strtotime($borrowing['return_date'])); ?></td>
    </tr>
    <tr>
        <th>Status</th>
        <td>:</td>
        <td>
            <?php
            if ($borrowing['status'] == 'pending') {
                echo '<span class="badge bg-warning text-dark">Pending</span>';
            } elseif ($borrowing['status'] == 'returned') {
                echo '<span class="badge bg-success">Returned</span>';
            } elseif ($borrowing['status'] == 'rejected') {
                echo '<span class="badge bg-secondary">Rejected</span>';
            } elseif ($borrowing['status'] == 'approved') {
                echo '<span class="badge bg-info">Approved</span>';
            } else {
                echo '<span class="badge bg-danger">Overdue</span>';
            }
            ?>
        </td>
    </tr>
</table>

<h4 class="mt-3">Detail Produk/Barang</h4>
<div class="table-responsive">
    <table class="table table-striped table-hover table-borderless table-light align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Harga/hari</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php
            $no = 1;
            while ($data = mysqli_fetch_array($query)) { ?>
                <tr>
                    <td><?= $no ?></td>
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
            <tr>
                <td colspan="3" class="text-end"><strong>Total Harga (<?= $duration ?> hari)</strong></td>
                <td><strong>Rp. <?= $total_price * $duration ?></strong></td>
            </tr>
        </tbody>
        <tfoot>

        </tfoot>
    </table>
</div>

<h4 class="mt-3">Identitas Peminjam</h4>
<table>
    <tr>
        <th>Nama Peminjam</th>
        <td>:</td>
        <td><?= $user['name']; ?></td>
    </tr>
    <tr>
        <th>Jenis Kelamin</th>
        <td>:</td>
        <td><?= $user['gender'] ?></td>
    </tr>
    <tr>
        <th>Alamat</th>
        <td>:</td>
        <td><?= $user['address'] ?></td>
    </tr>
    <tr>
        <th>No. Telepon</th>
        <td>:</td>
        <td><?= $user['phone'] ?></td>
    </tr>
</table>

<?php
$status = $borrowing['status'];
if ($status == 'pending') { ?>
    <h4 class="mt-3">Form Pengambilan</h4>
    <form method="post">
        <div class="row">
            <div class="col-md-4">
                <p class="fw-bold">Jenis identitas</p>
            </div>
            <div class="col-md-8">
                <select name="identity_type" class="form-select" required>
                    <option value="" disabled selected>Pilih jenis identitas</option>
                    <option value="KTP">KTP</option>
                    <option value="SIM">SIM</option>
                    <option value="KK">Kartu Keluarga</option>
                    <option value="Ijazah">Ijazah</option>
                </select>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-4">
                <p class="fw-bold">Nomor identitas</p>
            </div>
            <div class="col-md-8">
                <input type="number" name="identity_number" class="form-control" min="100000000000000" max="9999999999999999" placeholder="Masukkan nomor identitas" required>
                <p class="text-disabled">Note. Untuk tipe <span class="fw-bold">Kartu Keluarga</span> bisa diisi dengan No. KK.</p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-4">
                <p class="fw-bold">Nama identitas</p>
            </div>
            <div class="col-md-8">
                <input type="text" name="identity_name" class="form-control" placeholder="Masukkan nama identitas" required>
                <p class="text-disabled">Note. Untuk tipe <span class="fw-bold">Kartu Keluarga</span> bisa diisi dengan nama kepala keluarga.</p>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-md-4"></div>
            <div class="col-md-8">
                <input type="hidden" name="id_borrowing" value="<?= $borrowing['id'] ?>">
                <button name="submit" type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Pengambilan</button>
            </div>
    </form>
<?php } elseif ($status == 'approved' || $status == 'returned' || $status == 'overdue') { ?>
    <h4 class="mt-3">Informasi Pengambilan</h4>
    <table>
        <tr>
            <th>Jenis identitas</th>
            <td>:</td>
            <td><?= $borrowing['identity_type'] ?></td>
        </tr>
        <tr>
            <th>Nomor identitas</th>
            <td>:</td>
            <td><?= $borrowing['identity_number'] ?></td>
        </tr>
        <tr>
            <th>Nama identitas</th>
            <td>:</td>
            <td><?= $borrowing['identity_name'] ?></td>
        </tr>
    </table>
    <?php if ($status == 'approved') { ?>
        <form method="post" class="mt-3">
            <input type="hidden" name="id_borrowing" value="<?= $borrowing['id'] ?>">
            <?php if ($borrowing['return_date'] < date('Y-m-d H:i:s')) { ?>
                <input type="hidden" name="overdue" value="1">
            <?php } else { ?>
                <input type="hidden" name="overdue" value="0">
            <?php } ?>
            <button name="returned" type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Tandai Sudah Dikembalikan</button>
        </form>
<?php   }
} ?>

<?php
footer();
?>