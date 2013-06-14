<?php 
include '../include/config.php';

$mysqli = new mysqli("$host", "$user", "$pass", "lottery");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if ((isset($_POST['Submit'])) and (isset($_POST['choice']))){

$id = $_POST['choice'];
$refunded = 1;

$edit = "UPDATE lottery.refunds SET refunded =? WHERE lottery.refunds.id = ?";
$stmt = $mysqli->prepare($edit);
$stmt->bind_param("ii", $refunded, $id);
$stmt->execute();
header("Location: ../admin/index.php?act=refunds");
}

?>