<?php
//select your database host, usually is "localhost"
$host = "localhost";
//select the database user and password
$user = "user";
$pass = "password";

$con = mysql_connect($host,$user,$pass);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  } 
  
 //api section: select the keyID and vCode
$keyID = 123456;
$vCode = 'fgfdgndfg bfd gjfdbgjfdghhhjhgjbdshfjgbergb';

//enter the mail for notifications
$email = 'test@test.com';
?>