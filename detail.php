<?php
include 'connection.php';
user_require_login();

include 'templates/template_user.php';
header_navbar('Detail Peminjaman');

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
$duration = (strtotime($borrowing['return_date']) - strtotime($borrowing['borrow_date'])) / (60 * 60 * 24);
$duration = ceil($duration * 2) / 2;
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
                    <td>Rp. <?= number_format($data['price']) ?></td>
                </tr>
            <?php
                $total_price += $data['price'];
                $no++;
            }
            ?>
            <tr>
                <td colspan="3" class="text-end"><strong>Jumlah</strong></td>
                <td><strong>Rp. <?= number_format($total_price) ?></strong></td>
            </tr>
            <tr>
                <td colspan="3" class="text-end"><strong>Total Harga (<?= $duration ?> hari)</strong></td>
                <td><strong>Rp. <?= number_format($total_price * $duration) ?></strong></td>
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
if ($status == 'approved' || $status == 'returned' || $status == 'overdue') { ?>
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
<?php } ?>

<?php
footer();
?>