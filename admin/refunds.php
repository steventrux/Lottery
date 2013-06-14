<?php 
include '../include/config.php';

$mysqli = new mysqli("$host", "$user", "$pass", "lottery");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$sql_refunds = "SELECT lottery.refunds.id, lottery.lotteries.name, lottery.refunds.ownerName, SUM(lottery.refunds.ticket) AS tickets, lottery.lotteries.ticketCost
FROM lottery.refunds
LEFT JOIN lottery.lotteries ON lottery.refunds.reason = lottery.lotteries.reason
WHERE lottery.refunds.refunded = 0
GROUP BY lottery.lotteries.name, lottery.refunds.ownerName";
$result_refunds = $mysqli->query($sql_refunds);
$refunds_cnt = $result_refunds->num_rows;

if ($refunds_cnt == 0){
echo '<center><h1>No pending refunds!</h1></center>';
} else {
echo '<form method="post" action="do_refunds.php">
</br><div><center><input type="submit" name="Submit" value="Refunds" ></center></div>

</br><table width="95%" align="center" border="1" bordercolor="white" cellpadding="3" cellspacing="0">
<tr>
  <th width="0%"></th>
  <th width="30%">Lottery Name</th>
  <th width="30%">Buyer</th>
  <th width="15%">Ticket Cost</th>
  <th width="10%">Ticket Q.ty</th>
  <th width="15%">Refund</th>
</tr>';

while ($row = $result_refunds->fetch_array(MYSQLI_ASSOC)){
$refunds = number_format($row['tickets'] * $row['ticketCost']);

echo "<tr><td style='text-align:center'><input type='radio' name='choice' value='".$row['id']."'></td>
<td style='text-align:center'>".$row['name']."</td>
<td style='text-align:center'>".$row['ownerName']."</td>
<td style='text-align:center'>".number_format($row['ticketCost'])."</td>
<td style='text-align:center'>".$row['tickets']."</td>
<td style='text-align:center'>".$refunds."</td></tr>";

}
echo '</table></form></br>';
}


?>