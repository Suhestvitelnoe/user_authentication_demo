<?php

session_start();

require_once('mysql_connect.php');

if(isset($_SESSION['user'])!="")
{
	header("Location: index.php");
}

if(isset($_POST['login_btn']))
{
	$username_input = mysqli_real_escape_string($mysql_connection, $_POST['username_post']);
	$pswd_input = mysqli_real_escape_string($mysql_connection, $_POST['pswd_post']);

	$username_input = trim($username_input);
	$pswd_input = trim($pswd_input);
	
	$hashedsaltedpswd = hash("sha512", $username_input . $pswd_input); 

	$result = mysqli_query($mysql_connection, "SELECT userid, username, pswd FROM users WHERE username='$username_input'");
	$row =  mysqli_fetch_assoc($result);

	$count = mysqli_num_rows($result); // return value should be one if input was correct

	if($count == 1 && $row['pswd'] == $hashedsaltedpswd) // todo: salt passwd hash("sha512", $username_input $pswd_input))
	{
		$_SESSION['user'] = $row['userid'];
		header("Location: index.php");
	}
	else
	{
		?>
		<script>alert('Wrong username or password.');</script>
		<?php
	}

}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Samuel Gunadi">
		<title>Login</title>
		<link rel="stylesheet" href="style.css" type="text/css" />
	</head>
	<body>
		<center>
			<div id="login_form">
				<form method="post">
					<table align="center" width="40%" border="0">
						<tr>
							<td><input type="text" name="username_post" placeholder="Username" required /></td>
						</tr>
						<tr>
							<td><input type="password" name="pswd_post" placeholder="Password" required /></td>
						</tr>
						<tr>
							<td><button type="submit" name="login_btn">Log In</button></td>
						</tr>
						<tr>
							<td><a href="register.php">Register</a></td>
						</tr>
					</table>
				</form>
			</div>
		</center>
	</body>
</html>