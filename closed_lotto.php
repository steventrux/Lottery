<?php 
include '/include/config.php';

$mysqli = new mysqli("$host", "$user", "$pass", "lottery");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$sql_open = "SELECT *
FROM lottery.lotteries
WHERE lottery.lotteries.open = 0
ORDER BY lottery.lotteries.id ASC";
$result_open = $mysqli->query($sql_open);

$row_cnt = $result_open->num_rows;


if( $row_cnt > 0 ){
while ($row = $result_open->fetch_array(MYSQLI_ASSOC)){
echo '<h1><a href = "lottery.php?id='.$row['id'].'">'.$row['name'].'</a></h1>';
echo 'Lottery Reason: '.$row['reason'].'</br>Ticket Price: '.number_format($row['ticketCost']).'</br>Tickets: '.number_format($row['ticketNum']).'</br>Tickets Left: '.number_format($row['ticketLeft']).'</br>';

if ($row['winner'] > 0) {
echo 'Winner: Announced</br>';
} else {
echo 'Winner: Pending....</br>';
}

echo '<a href="'.$row['forumLink'].'" target="_blank">EvE Forum Link</a></br><a href="'.$row['diceLink'].'" target="_blank">Dice Link</a></br>';
}
} else {
echo '<center><h1>No Closed Lotteries!</h1></center>';
}



?>