<?php
include '../connection.php';
admin_require_login();

include '../templates/template_admin.php';
header_navbar('Admin');

$adminId = $_SESSION['admin_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE role = 'admin' OR id != '$adminId'");
?>

<center>
    <h1 class="mt-5">Dashboard Admin</h1>
</center>

<?php
footer();
?>