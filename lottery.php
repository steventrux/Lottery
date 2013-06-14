<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Test Lottery</title>
<link rel="stylesheet" type="text/css" href="lottery.css" media="screen" />
</head>
<body>
<div id="maincontainer">

<div id="topsection"><div class="innertube"><h1>My Lottery</h1></div></div>
<p id="layoutdims"><marquee behavior="scroll" direction="left"><?php include '/include/marquee.php'; ?></marquee></p>

<div id="contentwrapper">
<div id="contentcolumn">
<div class="innertube">
<?php
include '/include/config.php';

$mysqli = new mysqli("$host", "$user", "$pass", "lottery");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$id=$_GET['id'];

$sql_lottery = "SELECT *
FROM lottery.lotteries
WHERE lottery.lotteries.id = '$id'
ORDER BY lottery.lotteries.id ASC";
$result_lottery = $mysqli->query($sql_lottery);
$row = $result_lottery->fetch_array(MYSQLI_ASSOC);

$reason = $row['reason'];
$winner = $row['winner'];

echo '<center><div><h1>'.$row['name'].'</h1>';

$sql_winner = "SELECT lottery.tickets.ownerName
FROM lottery.lotteries
LEFT JOIN lottery.tickets ON lottery.lotteries.reason = lottery.tickets.reason
WHERE lottery.tickets.ticket = '$winner'
AND lottery.lotteries.id = '$id'";
$result_winner = $mysqli->query($sql_winner);
$result_winner1 = $result_winner->fetch_array(MYSQLI_ASSOC);

if ($winner > 0) {
echo '<center><div class="winner"><h1>Winner:</h1>
<h2>'.$result_winner1['ownerName'].', Ticket n° '.$winner.'</h2></div></center>';
}
echo 'Lottery Reason: '.$row['reason'].'</br>Ticket Price: '.number_format($row['ticketCost']).'</br>Tickets: '.number_format($row['ticketNum']).'</br>Tickets Left: '.number_format($row['ticketLeft']).'</br>';

$sql_refunds = "SELECT SUM(lottery.refunds.ticket) as refunds
FROM lottery.refunds
WHERE lottery.refunds.reason = '$reason'";
$result_refunds = $mysqli->query($sql_refunds);
$result_refunds1 = $result_refunds->fetch_array(MYSQLI_ASSOC);

if (isset($result_refunds1['refunds'])){
echo 'Tickets to refund: '.$result_refunds1['refunds'].'</br>';
}

echo '<a href="'.$row['forumLink'].'" target="_blank">EvE Forum Link</a></br><a href="'.$row['diceLink'].'" target="_blank">Dice Link</a></div></center></br>';

//SET GLOBAL group_concat_max_len = 10204;
$sql_ticket = "SELECT ownerName, ownerID, GROUP_CONCAT(ticket SEPARATOR ', ')
FROM lottery.tickets
WHERE lottery.tickets.reason = '$reason'
GROUP BY ownerName
ORDER BY lottery.tickets.ownerName ASC";
$result_ticket = $mysqli->query($sql_ticket);
$row_cnt = $result_ticket->num_rows;

if( $row_cnt > 0 ){
echo '<hr><center><h1>Tickets:</h1></center>';
while ($row = $result_ticket->fetch_array(MYSQLI_ASSOC)){
$owner = $row["ownerID"];

$sql_owned = "SELECT *
FROM lottery.tickets
WHERE lottery.tickets.reason = '$reason'
AND lottery.tickets.ownerID = '$owner'";
$result_owned = $mysqli->query($sql_owned);
$owned_cnt = $result_owned->num_rows;

echo '<p><h2>'.$row["ownerName"].' ('.$owned_cnt.')</h2><img src="http://image.eveonline.com/Character/'.$row["ownerID"].'_64.jpg" title='.$row["ownerName"].'></p><p>'.$row["GROUP_CONCAT(ticket SEPARATOR ', ')"].'</p>';
}

$sql_refunded = "SELECT lottery.refunds.ownerName, SUM(lottery.refunds.ticket) as to_refund
FROM lottery.refunds
WHERE lottery.refunds.reason = '$reason'
GROUP BY lottery.refunds.ownerName";
$result_refunded = $mysqli->query($sql_refunded);

if (isset($result_refunds1['refunds'])){
echo '<hr><center>Tickets to refund:</br></center>';
while ($result_refunded1 = $result_refunded->fetch_array(MYSQLI_ASSOC)){
echo '<center>'.$result_refunded1['ownerName'].' - '.$result_refunded1['to_refund'].' tickets</center>';
}
}

} else {
echo '<center><h1>No tickets sold at this moment!</h1></center>';
}
?>
</div>
</div>
</div>

<div id="leftcolumn">
<div class="innertube">
          <a href = "index.php"><button class="clean-gray">Current lotteries</button></a>
          <a href = "index.php?act=closed"><button class="clean-gray">Closed lotteries</button></a>
          <button class="clean-gray">FAQ</button>
        </div>

</div>

<div id="footer"><?php include '/include/footer.php'; ?></div>

</div>
</body>
</html>