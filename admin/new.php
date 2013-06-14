<?php
include '../include/config.php';

$mysqli = new mysqli("$host", "$user", "$pass", "lottery");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if(isset($_POST['Submit'])){
//function to protect from code injection during registration
 function protect($string){
	$string = trim(strip_tags(addslashes($string)));
	return $string;
}
//protect and then add the posted data to variables
			$lottoName = stripslashes(protect($_POST['lottoName']));
			$reason = stripslashes(protect($_POST['reason']));
			$ticketNum = stripslashes(protect($_POST['ticketNum']));
			$ticketPrice = stripslashes(protect($_POST['ticketPrice']));
			$forumLink = stripslashes(protect($_POST['forumLink']));
			$diceLink = stripslashes(protect($_POST['diceLink']));
	        //check to see if any of the boxes were not filled in
			if(!$lottoName || !$reason || !$ticketNum || !$ticketPrice || !$forumLink || !$diceLink){
				//if any weren't display the error message
				echo "</br><div><center><b>You need to fill in all of the required fields!</b></center></div>";
				echo ('<a href="javascript:history.go(-1);"></br><center>Back</center></a>');
				} else {

//controlla che la ragione non sia duplicata
$sql_reason = "SELECT *
FROM lottery.lotteries
WHERE lottery.lotteries.reason = '$reason'";
$result_reason = $mysqli->query($sql_reason);
$reason_cnt = $result_reason->num_rows;
//se è un duplicato blocca
if ($reason_cnt > 0){
echo "</br><div><center><b>The Lotto Reason you have chosen is already taken, change it</b></center></div>";
echo ('<a href="javascript:history.go(-1);"></br><center>Back</center></a>');
} else {
//registra la nuova lotteria
$open = 1;
$winner = 0;
$register = "INSERT INTO lottery.lotteries (name, reason, ticketNum, ticketLeft, ticketCost, forumLink, diceLink, open, winner) VALUES (?,?,?,?,?,?,?,?,?)";
$stmt = $mysqli->prepare($register);
$stmt->bind_param("ssiiissii", $lottoName, $reason, $ticketNum, $ticketNum, $ticketPrice, $forumLink, $diceLink, $open, $winner);
$stmt->execute();

echo '</br><center>Congrats, the '.$lottoName.' lottery has been created.</center>';
echo '</br><center><a href="../admin/">Back to the ACP</a></center>';
}
}
} else {
echo '</br><form align="center" name="new" type="text" id="new" method="post">
<div>Lottery Name:</br><input type="text" name="lottoName" value="" /></div>
<div>Lottery Reason:</br><input type="text" name="reason" value="" /></div>
<div>Tickets Number:</br><input type="text" name="ticketNum" value="" /></div>
<div>Ticket Price:</br><input type="text" name="ticketPrice" value="" /></div>
<div>Forum Link:</br><input type="text" name="forumLink" value="http://" /></div>
<div>Dice Link:</br><input type="text" name="diceLink" value="http://" /></div>
<div><input type="submit" name="Submit" value="Register"></div>
</form></br>';
}

?>