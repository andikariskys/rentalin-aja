<?php
include '../connection.php';
admin_require_login();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $query = mysqli_query($conn, "INSERT INTO categories VALUES (uuid(), '$name', '$description')");
    if ($query) {
        $_SESSION['success'] = 'Kategori berhasil ditambahkan!';
        header('Location: categories.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal menambahkan kategori: ' . mysqli_error($conn);
        header('Location: categories.php');
        exit();
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = mysqli_query($conn, "DELETE FROM categories WHERE id = '$id'");
    if ($query) {
        $_SESSION['success'] = 'Kategori berhasil dihapus!';
        header('Location: categories.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal menghapus kategori: ' . mysqli_error($conn);
        header('Location: categories.php');
        exit();
    }
}

$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editQuery = mysqli_query($conn, "SELECT * FROM categories WHERE id = '$id'");
    if ($editQuery) {
        $editData = mysqli_fetch_array($editQuery);
    } else {
        $_SESSION['error'] = 'Gagal mengambil data kategori: ' . mysqli_error($conn);
        header('Location: categories.php');
        exit();
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $query = mysqli_query($conn, "UPDATE categories SET name = '$name', description = '$description' WHERE id = '$id'");
    if ($query) {
        $_SESSION['success'] = 'Kategori berhasil diperbarui!';
        header('Location: categories.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal memperbarui kategori: ' . mysqli_error($conn);
        header('Location: categories.php');
        exit();
    }
}

include '../templates/template_admin.php';
header_navbar('Kategori');

$query = mysqli_query($conn, "SELECT c.*, COUNT(p.id) as count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id ORDER BY c.name ASC");
?>

<center>
    <h3>Form Data Kategori</h3>
</center>

<div class="d-flex justify-content-center">
    <div class="card border-light" style="width: 100%; max-width: 480px;">
        <div class="card-body">
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
            <form method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kategori</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $editData['name'] ?? '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3"> <?= $editData['description'] ?? '' ?></textarea>
                </div>
                <?php if ($editData) { ?>
                    <input type="hidden" name="id" value="<?= $editData['id']; ?>">
                    <button type="submit" name="update" class="btn btn-warning"><i class="fa-solid fa-floppy-disk"></i> Update Kategori</button>
                    <a href="categories.php" class="btn btn-secondary">Batalkan</a>
                <?php } else { ?>
                    <button type="submit" name="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Kategori</button>
                <?php } ?>
            </form>
        </div>
    </div>
</div>

<center>
    <h2 class="my-3">Data Kategori</h2>
</center>

<div class="table table-responsive">
    <table class="table table-striped table-hover table-borderless table-light align-middle" id="myTable">
        <thead>
            <tr>
                <th style="width: 10px;">No</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Jml. Produk</th>
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
                    <td><?= $data['description']; ?></td>
                    <td><?= $data['count']; ?></td>
                    <td>
                        <a href="categories.php?edit=<?= $data['id']; ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        <a href="categories.php?delete=<?= $data['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kategori ini?');"><i class="fa-solid fa-trash"></i> Hapus</a>
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