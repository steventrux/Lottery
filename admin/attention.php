<?php 
include '../include/config.php';

$mysqli = new mysqli("$host", "$user", "$pass", "lottery");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$sql_att = "SELECT *
FROM lottery.lotteries
WHERE lottery.lotteries.open = 0 AND lottery.lotteries.winner = 0";
$result_att = $mysqli->query($sql_att);
$att_cnt = $result_att->num_rows;
error_reporting(E_ALL ^ E_NOTICE);

if ($att_cnt > 0){
while ($result_att1 = $result_att->fetch_array(MYSQLI_ASSOC)){
echo '<center><h1><a href="edit.php?id='.$result_att1['id'].'">'.$result_att1['name'].'</a> lottery needs your attention</h1></center>';
}
} else {
echo '<center><h1>No lotteries need your attention</h1></center>';
}

$sql_refunds = "SELECT lottery.refunds.id, lottery.lotteries.name, lottery.refunds.ownerName, SUM(lottery.refunds.ticket) AS tickets, lottery.lotteries.ticketCost
FROM lottery.refunds
LEFT JOIN lottery.lotteries ON lottery.refunds.reason = lottery.lotteries.reason
WHERE lottery.refunds.refunded = 0
GROUP BY lottery.lotteries.name, lottery.refunds.ownerName";
$result_refunds = $mysqli->query($sql_refunds);
$refunds_cnt = $result_refunds->num_rows;

if ($refunds_cnt > 0){
echo '</br><center><h1><a href="../admin/index.php?act=refunds">There are pending refunds</a></h1></center>';
}
?>