<?php
include 'connection.php';
user_require_login();

include 'templates/template_user.php';
header_navbar('Riwayat');

if (isset($_GET['cancel'])) {
    $id_borrowing = $_GET['cancel'];
    $query = mysqli_query($conn, "UPDATE borrowings SET status = 'canceled' WHERE id = '$id_borrowing'");
    if ($query) {
        $productQuery = mysqli_query($conn, "SELECT product_id FROM borrowing_details WHERE borrowing_id = '$id_borrowing'");
        while ($product = mysqli_fetch_assoc($productQuery)) {
            $productId = $product['product_id'];
            mysqli_query($conn, "UPDATE products SET stock = stock + 1 WHERE id = '$productId'");
        }

        echo "
        <script>
        alert('Peminjaman berhasil dibatalkan!');
        window.location.href='history.php';
        </script>";
    } else {
        echo "
        <script>
        alert('Gagal membatalkan peminjaman!');
        window.location.href='history.php';
        </script>";
    }
}

$userId = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM borrowings WHERE user_id = '$userId' ORDER BY borrow_date DESC");
?>

<center>
    <h2 class="my-3">Riwayat Peminjaman</h2>
</center>
<div class="table table-responsive">
    <table class="table table-striped table-hover table-borderless table-light align-middle" id="myTable">
        <thead class="table-light">
            <tr>
                <th style="width: 10px;">No</th>
                <th>Tgl. Peminjaman</th>
                <th>Tgl. Pengembalian</th>
                <th>Kode Pinjam</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php
            $no = 1;
            while ($data = mysqli_fetch_array($query)) { ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $data['borrow_date']; ?></td>
                    <td><?= $data['return_date']; ?></td>
                    <td>#<?= $data['code']; ?></td>
                    <td>
                        <?php
                        if ($data['status'] == 'pending') {
                            echo '<span class="badge bg-warning text-dark">Pending</span>';
                        } elseif ($data['status'] == 'returned') {
                            echo '<span class="badge bg-success">Returned</span>';
                        } elseif ($data['status'] == 'rejected') {
                            echo '<span class="badge bg-secondary">Rejected</span>';
                        } elseif ($data['status'] == 'approved') {
                            if ($data['return_date'] < date('Y-m-d H:i:s')) {
                                echo '<span class="badge bg-danger">Overdue</span>';
                            } else {
                                echo '<span class="badge bg-info">Approved</span>';
                            }
                        } else {
                            echo '<span class="badge bg-secondary">Canceled</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($data['status'] == 'pending') { ?>
                            <a href="history.php?cancel=<?= $data['id']; ?>" class="btn btn-danger btn-sm"><i class="fa-solid fa-ban"></i> Cancel</a>
                        <?php } ?>
                        <a href="detail.php?id=<?= $data['id']; ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-circle-info"></i> Detail</a>
                    </td>
                </tr>
            <?php
                $no++;
            }
            ?>
        </tbody>
        <tfoot>

        </tfoot>
    </table>
</div>

<?php
footer();
?>