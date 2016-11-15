<?php

session_start();

require_once('mysql_connect.php');

if(!isset($_SESSION['user']))
{
	header("Location: login.php");
}

$result = mysqli_query($mysql_connection, "SELECT * FROM users WHERE userid=".$_SESSION['user']);
$row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Samuel Gunadi">
		<title><?php echo $row['userid']; ?></title>
		<link rel="stylesheet" href="style.css" type="text/css" />
	</head>
	<body>
		<div id="content">
			Logged in as <?php echo $row['username']; ?>.<br/><a href="logout.php?logout">Log Out</a>
		</div>
	</body>
</html>