<?php 
include '../include/config.php';

$mysqli = new mysqli("$host", "$user", "$pass", "lottery");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if(isset($_POST['Edit'])){
//function to protect from code injection during registration
 function protect($string){
	$string = trim(strip_tags(addslashes($string)));
	return $string;
}
//protect and then add the posted data to variables
			$lottoName = stripslashes(protect($_POST['lottoName']));
			$forumLink = stripslashes(protect($_POST['forumLink']));
			$diceLink = stripslashes(protect($_POST['diceLink']));
			$winner = stripslashes(protect($_POST['winner']));
	        //check to see if any of the boxes were not filled in
			if(!$lottoName || !$forumLink || !$diceLink){
				//if any weren't display the error message
				echo "</br><div><center><b>You need to fill in all of the required fields!</b></center></div>";
				echo ('<a href="javascript:history.go(-1);"></br><center>Back</center></a>');
				} else {

//modifica la lotteria
$id = $_POST['id'];
$edit = "UPDATE lottery.lotteries SET name =?, forumLink =?, diceLink =?, winner =? WHERE lottery.lotteries.id = ?";
$stmt = $mysqli->prepare($edit);
$stmt->bind_param("sssii", $lottoName, $forumLink, $diceLink, $winner, $id);
$stmt->execute();
header("Location: ../admin/index.php?act=manage");
}
}
?>