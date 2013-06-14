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
error_reporting(E_ALL ^ E_NOTICE);
function index() 
{ 
include 'open_lotto.php';
}
$choice=$_GET['act']; 
switch($choice) 
{ 
case "closed": 
include 'closed_lotto.php';
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
          <a href = "index.php"><button class="clean-gray">Current lotteries</button></a>
          <a href = "index.php?act=closed"><button class="clean-gray">Closed lotteries</button></a>
          <button class="clean-gray">FAQ</button>
        </div>

</div> 

<div id="footer"><?php include '/include/footer.php'; ?></div>

</div>
</body>
</html>
