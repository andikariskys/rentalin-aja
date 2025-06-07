<?php
include 'connection.php';
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $role = $user['role'];

        if ($role == 'admin') {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            header('Location: admin/index.php');
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            header('Location: index.php');
        }
    } else {
        $_SESSION['error'] = 'Username atau password salah!';
    }
}

include 'templates/template_auth.php';
header_navbar('Login');
?>

<div class="card mb-3 border-light" style="max-width: 720px;">
    <div class="row g-0">
        <div class="col-md-4 d-flex align-items-center justify-content-center">
            <img src="assets/images/logo-ums.png" class="img-fluid" alt="logo-ums">
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <center>
                    <h3 class="card-title">Login</h3>
                </center>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                ?>
                <form action="" method="post">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                    <input type="password" name="password" class="form-control mt-2" placeholder="Password" required>
                    <button name="submit" type="submit" class="btn btn-primary w-100 mt-3">Masuk</button>
                </form>
                <div class="text-center mt-3">
                    <p>Belum punya akun? <a href="register.php">Daftar</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
footer();
?>