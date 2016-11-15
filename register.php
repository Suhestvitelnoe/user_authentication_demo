<?php

session_start();

if(isset($_SESSION['user'])!="")
{
	header("Location: index.php");
}

require_once('mysql_connect.php');

if(isset($_POST['register_btn']))
{

	if(!isset($_POST['username_post']) || !isset($_POST['pswd_post']))
	{
		?>
		<script>alert('Please enter username and password.');</script>
		<?php
		exit;
	}
	if(isset($_POST['g-recaptcha-response']))
	{
		$captcha=$_POST['g-recaptcha-response'];
	}
	if(!$captcha)
	{
		?>
		<script>alert('Please enter CAPTCHA.');</script>
		<?php
		exit;
	}
	// my secret key is 6Lfy2SITAAAAAOlVqtVdAjLLhqKrFnT884wgH_2g
	$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lfy2SITAAAAAOlVqtVdAjLLhqKrFnT884wgH_2g&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
	if($response['success'] == false)
	{
		?>
		<script>alert('Incorrect CAPTCHA.');</script>
		<?php
		exit;
	}
	$username_input = mysqli_real_escape_string($mysql_connection, $_POST['username_post']);
	$pswd_input = mysqli_real_escape_string($mysql_connection, $_POST['pswd_post']);

	$username_input = trim($username_input);
	$pswd_input = trim($pswd_input);

	if(strlen($username_input) < 4 || strlen($username_input) > 32 || strlen($pswd_input) < 4 || strlen($pswd_input) > 32)
	{
		?>
		<script>alert('Username and password length must be 4 to 32 characters.');</script>
		<?php
		exit;
	}
	// salt password with username, and then hash with sha512
	$hashedsaltedpswd = hash("sha512", $username_input . $pswd_input);

	// check if username already exist
	$query = "SELECT username FROM users WHERE username='$username_input'";
	$result = mysqli_query($mysql_connection, $query);

	$count = mysqli_num_rows($result);

	if($count == 0)
	{
		if(mysqli_query($mysql_connection, "INSERT INTO users(username, pswd) VALUES('$username_input', '$hashedsaltedpswd')"))
		{
			?>
			<script>alert('Registration completed successfully.');</script>
			<?php
		}
		else
		{
			?>
			<script>alert('Registration failed.');</script>
			<?php
		}
	}
	else
	{
		?>
		<script>alert('That username is already taken.');</script>
		<?php
	}

}
?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Samuel Gunadi">
		<title>Registration Form</title>
		<link rel="stylesheet" href="style.css" type="text/css" />
		<script src='https://www.google.com/recaptcha/api.js'></script>
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
							<!-- my public key is 6Lfy2SITAAAAAITUR0E0qCRyMtJidlzfygHdVdvy -->
							<td align="center"><div class="g-recaptcha" data-sitekey="6Lfy2SITAAAAAITUR0E0qCRyMtJidlzfygHdVdvy"></div></td>
						</tr>
						<tr>
							<td><button type="submit" name="register_btn">Register</button></td>
						</tr>
						<tr>
							<td><a href="index.php">Login</a></td>
						</tr>
					</table>
				</form>
			</div>
		</center>
	</body>
</html>