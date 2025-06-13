<?php
include '../connection.php';
admin_require_login();

include '../templates/template_admin.php';
header_navbar('Dashboard');

$query = mysqli_query($conn, "SELECT borrowings.*, users.name AS name FROM borrowings INNER JOIN users ON borrowings.user_id = users.id WHERE borrowings.status != 'canceled' ORDER BY borrowings.borrow_date DESC");
?>

<center>
    <h2 class="my-3">Data Peminjaman</h2>
</center>
<div class="table table-responsive">
    <table class="table table-striped table-hover table-borderless table-light align-middle" id="myTable">
        <thead class="table-light">
            <tr>
                <th style="width: 10px;">No</th>
                <th>Nama Peminjam</th>
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
                <td><?= $data['name']; ?></td>
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
                    }
                    ?>
                </td>
                <td>
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