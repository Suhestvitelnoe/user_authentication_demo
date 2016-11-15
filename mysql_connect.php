<?php

$mysql_connection = mysqli_connect( 'localhost', 'root', '', 'webproject', '3306');

if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>