<?php 
include '../include/config.php';

$mysqli = new mysqli("$host", "$user", "$pass", "lottery");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$sql_lotto = "SELECT *
FROM lottery.lotteries
WHERE lottery.lotteries.winner = 0
ORDER BY lottery.lotteries.id ASC";
$result_lotto = $mysqli->query($sql_lotto);

echo '<form method="post" action="edit.php">';
while ($row = $result_lotto->fetch_array(MYSQLI_ASSOC)){
echo '<h2><input type="radio" name="choice" value="'.$row['id'].'">'.$row['name'].'</h2>';
}
echo '<div><input type="submit" name="Edit" value="Edit" ><input type="submit" name="Delete" value="Delete" ></div></form>';
?>