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
        header('Location: ../login.php');
    }
}

function user_require_login()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
    }
}