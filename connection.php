<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'rentalin_aja';

$conn = mysqli_connect($host, $user, $password, $database);

function admin_require_login()
{
    if (!isset($_SESSION['admin_id'])) {
        echo "
        <script>
        alert('Untuk mengakses halaman ini, Anda harus login terlebih dahulu.');
        window.location.href = '../login.php';
        </script>
        ";
    }
}

function user_require_login()
{
    if (!isset($_SESSION['user_id'])) {
        echo "
        <script>
        alert('Untuk mengakses halaman ini, Anda harus login terlebih dahulu.');
        window.location.href = 'login.php';
        </script>
        ";
    }
}