<?php
require_once('config.php'); 
$con = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
	if (!$con)
	{
	die('Could not connect: ' . mysql_error());
	}
	
mysql_select_db(DB_DATABASE, $con);
$result = mysql_query("SELECT * FROM test");
$row = mysql_fetch_assoc($result);

echo $row['text'];
?>