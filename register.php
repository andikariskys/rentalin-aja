<?php
include 'connection.php';
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $check_query = "SELECT * FROM users WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['error'] = 'Username sudah terdaftar!';
    } else {
        $query = "INSERT INTO users VALUES (uuid(), '$username', '$password', '$name', '$gender', '$address', '$phone', 'user')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
        } else {
            $_SESSION['error'] = 'Terjadi kesalahan saat registrasi. Silakan coba lagi.';
        }
    }
}

include 'templates/template_auth.php';
header_navbar('Register');
?>

<div class="card mb-3 border-light" style="max-width: 720px;">
    <div class="row g-0">
        <div class="col-md-4 d-flex align-items-center justify-content-center">
            <img src="assets/images/logo-ums.png" class="img-fluid" alt="logo-ums">
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <center>
                    <h3 class="card-title">Register</h3>
                </center>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>';
                    unset($_SESSION['success']);
                }
                ?>
                <form action="" method="post">
                    <input type="text" name="username" class="form-control" placeholder="Username" onkeypress="remove_space(this)" required>
                    <input type="password" name="password" class="form-control mt-2" placeholder="Password" required>
                    <input type="text" name="name" class="form-control mt-2" placeholder="Nama Lengkap" required>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input" type="radio" name="gender" id="male" value="Laki-laki" required />
                        <label class="form-check-label" for="male">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input" type="radio" name="gender" id="female" value="Perempuan" />
                        <label class="form-check-label" for="female">Perempuan</label>
                    </div>
                    <textarea name="address" class="form-control mt-2" placeholder="Alamat" required></textarea>
                    <input type="text" name="phone" class="form-control mt-2" placeholder="Nomor Telepon" value="0" minlength="12" maxlength="14" onkeypress="number_only(this)" required>
                    <button name="submit" type="submit" class="btn btn-primary w-100 mt-3">Daftar</button>
                </form>
                <div class="text-center mt-3">
                    <p>Sudah punya akun? <a href="login.php">Masuk</a></p>
                </div>
            </div>
        </div>
    </div>
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