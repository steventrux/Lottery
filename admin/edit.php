<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Admin Lottery</title>
<link rel="stylesheet" type="text/css" href="../lottery.css" media="screen" />
</head>
<body>
<div id="maincontainer">

<div id="topsection"><div class="innertube"><h1>My Lottery</h1></div></div>
<p id="layoutdims"><marquee behavior="scroll" direction="left"><?php include '../include/marquee.php'; ?></marquee></p>

<div id="contentwrapper">
<div id="contentcolumn">
<div class="innertube">
<?php
include '../include/config.php';

$mysqli = new mysqli("$host", "$user", "$pass", "lottery");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if ((isset($_POST['Delete'])) and (isset($_POST['choice']))){
$id = $_POST['choice'];

$delete = "DELETE FROM lottery.lotteries WHERE lottery.lotteries.id = ?";
$stmt = $mysqli->prepare($delete);
$stmt->bind_param('i', $id);
$stmt->execute();
header("Location: ../admin/index.php?act=manage");
}

if ((isset($_POST['Edit'])) and (isset($_POST['choice']))){
$id = $_POST['choice'];
} else {
$id=$_GET['id'];
}

$sql_lottery = "SELECT *
FROM lottery.lotteries
WHERE lottery.lotteries.id = '$id'";
$result_lottery = $mysqli->query($sql_lottery);
$row = $result_lottery->fetch_array(MYSQLI_ASSOC);

echo '<form align="center" name="new" type="text" id="new" method="post" action="do_edit.php">';

if ($row['open'] == 0){
echo '<div class="winner"><h1>Winner:</h1><input type="text" name="winner" value="'.$row['winner'].'" />
<input type="hidden" name="id" value="'.$row['id'].'" />
<input type="hidden" name="lottoName" value="'.$row['name'].'" />
<input type="hidden" name="ticketNum" value="'.$row['ticketNum'].'" />
<input type="hidden" name="forumLink" value="'.$row['forumLink'].'" />
<input type="hidden" name="diceLink" value="'.$row['diceLink'].'" /></div>';
} else {
echo '<center><h2>Lottery Reason and Tickets details can\'t be edited</h2></center>
<input type="hidden" name="id" value="'.$row['id'].'" />
<div>Lottery Name:</br><input type="text" name="lottoName" value="'.$row['name'].'" /></div>
<div>Forum Link:</br><input type="text" name="forumLink" value="'.$row['forumLink'].'" /></div>
<div>Dice Link:</br><input type="text" name="diceLink" value="'.$row['diceLink'].'" /></div>
<input type="hidden" name="ticketNum" value="'.$row['ticketNum'].'" />
<input type="hidden" name="winner" value="0" />';
}
echo '<div><input type="submit" name="Edit" value="Edit"></div>
</form></br>';



?>
</div>
</div>
</div>

<div id="leftcolumn">
<div class="innertube">
<a href = "../admin"><button class="clean-gray">ACP</button></a>
<a href = "../admin/index.php?act=new"><button class="clean-gray">New Lottery</button></a>
<a href = "../admin/index.php?act=manage"><button class="clean-gray">Manage Lotteries</button></a>
<a href = "../admin/index.php?act=refunds"><button class="clean-gray">Manage Refunds</button></a>
<a href = "../"><button class="clean-gray">Lotto Home</button></a>
          </div>

</div> 

<div id="footer"><?php include '../include/footer.php'; ?></div>

</div>
</body>
</html>
