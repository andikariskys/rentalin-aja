<?php
include 'connection.php';
user_require_login();

include 'templates/template_user.php';
header_navbar('Riwayat');

$userId = $_SESSION['user_id'];
$query = "SELECT * FROM borrowings WHERE user_id = '$userId' ORDER BY rental_date DESC";
?>



<?php
footer();
?>