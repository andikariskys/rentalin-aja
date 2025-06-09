<?php
include '../connection.php';
admin_require_login();

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $query = mysqli_query($conn, "INSERT INTO users VALUES (uuid(), '$username', '$password', '$name', '$gender', '$address', '$phone', 'admin')");
    if ($query) {
        $_SESSION['success'] = 'Admin berhasil ditambahkan!';
        header('Location: users.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal menambahkan admin: ' . mysqli_error($conn);
        header('Location: users.php');
        exit();
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");
    if ($query) {
        $_SESSION['success'] = 'Admin berhasil dihapus!';
        header('Location: users.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal menghapus admin: ' . mysqli_error($conn);
        header('Location: users.php');
        exit();
    }
}

$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
    if (mysqli_num_rows($query) > 0) {
        $editData = mysqli_fetch_array($query);
    } else {
        $_SESSION['error'] = 'Admin tidak ditemukan!';
        header('Location: users.php');
        exit();
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $query = mysqli_query($conn, "UPDATE users SET username = '$username', name = '$name', gender = '$gender', address = '$address', phone = '$phone' WHERE id = '$id'");
    if ($query) {
        $_SESSION['success'] = 'Admin berhasil diperbarui!';
        header('Location: users.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal memperbarui admin: ' . mysqli_error($conn);
        header('Location: users.php');
        exit();
    }
}

if (isset($_GET['reset_pass'])) {
    $id = $_GET['reset_pass'];
    $name = $_GET['name'];
    $query = mysqli_query($conn, "UPDATE users SET password = md5('12345678') WHERE id = '$id'");
    if ($query) {
        $_SESSION['success'] = 'Password <strong>' . $name. '</strong> berhasil direset!<br>Silakan login dengan password baru: <strong>12345678</strong>';
        header('Location: users.php');
        exit();
    } else {
        $_SESSION['error'] = 'Gagal mereset password admin: ' . mysqli_error($conn);
        header('Location: users.php');
        exit();
    }
}

include '../templates/template_admin.php';
header_navbar('Pengguna');

$adminId = $_SESSION['admin_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id != '$adminId' ORDER BY username ASC");
?>

<center>
    <h3 class="mt-5">Form Data Admin</h3>
</center>

<div class="d-flex justify-content-center">
    <div class="card border-light" style="width: 100%; max-width: 480px;">
        <div class="card-body">
            <?php
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $_SESSION['success'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                unset($_SESSION['success'], $_SESSION['error']);
            } elseif (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                unset($_SESSION['error']);
            }
            ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" onkeypress="remove_space(this)" value="<?= $editData['username'] ?? '' ?>" required>
                </div>
                <?php if (is_null($editData)) { ?>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                <?php } ?>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $editData['name'] ?? '' ?>" required>
                </div>
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input name="gender" class="form-check-input" type="radio" id="laki-laki" value="Laki-laki" <?php if(isset($editData)){ if($editData['gender'] == 'Laki-laki') { echo 'checked'; } } ?> required />
                        <label class="form-check-label" for="laki-laki">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input name="gender" class="form-check-input" type="radio" id="perempuan" value="Perempuan" <?php if(isset($editData)){ if($editData['gender'] == 'Perempuan') { echo 'checked'; } } ?> />
                        <label class="form-check-label" for="perempuan">Perempuan</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required><?= $editData['address'] ?? '' ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">No. Handphone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= $editData['phone'] ?? '0' ?>" minlength="12" maxlength="14" onkeypress="number_only(this)" required>
                </div>
                <?php if ($editData) { ?>
                    <input type="hidden" name="id" value="<?= $editData['id']; ?>">
                    <button name="update" type="submit" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i> Update Admin</button>
                    <a href="users.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Batalkan</a>
                <?php } else { ?>
                    <button name="submit" type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Admin</button>
                <?php } ?>
            </form>
        </div>
    </div>
</div>

<center>
    <h2 class="mt-5">Data Pengguna</h2>
</center>

<div class="table table-responsive">
    <table class="table table-striped table-hover table-borderless table-light align-middle" id="myTable">
        <thead>
            <tr>
                <th style="width: 10px;">No</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Jen. Kelamin</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php
            $no = 1;
            while ($data = mysqli_fetch_array($query)) { ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $data['username']; ?></td>
                    <td><?= $data['name']; ?></td>
                    <td><?= $data['gender']; ?></td>
                    <td><?= $data['role']; ?></td>
                    <td>
                        <a href="users.php?reset_pass=<?= $data['id'] ?>&name=<?= $data['name'] ?>" class="btn btn-success btn-sm"><i class="fa-solid fa-recycle"></i> Reset Password</a>
                        <a href="users.php?edit=<?= $data['id']; ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        <a href="users.php?delete=<?= $data['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pengguna ini?');"><i class="fa-solid fa-trash"></i> Hapus</a>
                    </td>
                </tr>
            <?php
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function remove_space(input) {
        input.value = input.value.replace(/[^a-zA-z0-9]/g, '');
        input.value = input.value.toLowerCase();
    }

    function number_only(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
    }
</script>

<?php
footer();
?>