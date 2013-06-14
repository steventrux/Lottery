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
error_reporting(E_ALL ^ E_NOTICE);
include '../include/config.php';

$mysqli = new mysqli("$host", "$user", "$pass", "lottery");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
function index() 
{ 
include 'attention.php';
}

$choice=$_GET['act']; 

switch($choice) 
{ 
case "new": 
include 'new.php';
break;

case "manage": 
include 'manage.php';
break;

case "refunds": 
include 'refunds.php';
break;

default: 
index();
}
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
