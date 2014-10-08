<?php
ob_start();
session_start();
session_destroy();
session_start();

//require_once('./includes/mysql.php');
require_once('./tb-config.php');
require_once('./includes/conf.php');

$error = '';

//DO LOGIN FORM
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$username = $_POST['username'];
	$password = sha1($_POST['password']);
	$count = 0;

	//FIND USERNAME AND PASSWORD
	$results = $tbdb->query("SELECT * FROM users WHERE username = ? and password = ?", array($username, $password));	
	$count = sizeof($count);


	//IF ONE RESULT IS FOUND, THEN USERNAME AND PASSWORD ARE CORRECT
	if ($count == 1) {
		$_SESSION["username"] = $_POST["username"];
		$_SESSION["loggedin"] = true;

		header("location: ./index.php");
	} else {
		$error = '<div class="alert alert-danger">Invalid Login!</div>';
	}
}
?>
<html>
    <head>
<?php include_once('./includes/header.inc.php') ?>
		<title>Throwback Control Panel</title>
    </head>
    <body>

		<div id='login' class="container" style="max-width: 400px; margin: auto; ">
			<form action='' class="form" method='post' style="margin-top: 20px;">
<?php echo $error; ?>
				<p>Username: <input class="form-control" type="text" name="username" size="25" /></p>
				<p>Password: <input class="form-control" type="password" name="password" size="25" /></p>
				<p><input type="submit" class="btn btn-primary" name="login" value="Go" /></p>
			</form>
				<?php
//LOG THE IP FOR EVEN COMING HERE!
				$ipaddress = $_SERVER['REMOTE_ADDR'];
				$date = date("M j, Y g:i a", time());
				$ref = 'unknown';

//CHECK IF THERE IS A REFERRER
				if (isset($_SERVER['HTTP_REFERER']))
					// HTTP_REFERER is user controlled and should be escaped
					$ref = htmlspecialchars($_SERVER['HTTP_REFERER']);

//INSERT IP ACCESSING THIS PAGE!
				$tbdb->query("INSERT INTO access (ipaddress,date,referrer) VALUES (?,?,?)", array($ipaddress, $date,$ref));	
				?>

		</div>
	</body>
</html>
<?php
ob_flush();
?>