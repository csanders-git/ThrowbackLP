<?php
include("./includes/header.inc.php");

if (!file_exists('tb-config.php')) 
    die("There isn't a <code>tb-config.php</code> file. This is needed before we can start. You try to <a href='setup-config.php'>create a <code>tb-config.php</code> file through the web interface</a>, but this won't work if we can't write files. The safest way is to manually create the file.");
// This will grab our settings and load our DB
require_once('tb-config.php');

if(isset($_GET['step'])){
	$step = $_GET['step'];
}else{
	$step = "0";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
// Check to see if the DB has already been populated
$installed = $tbdb->query("SELECT * FROM users",Null);
if ($installed) die('<h1>Already Installed</h1><p>You appear to have already installed Throwback. To reinstall please clear your old database tables first</p><br><h3><a href="index.php">Proceed to Login</a></h3></body></html>');
?>

<?php
if($step === "0"){
	echo('Welcome to the next step of ThrowBack installation. We&#8217;re now going to go through the last few steps to get you up and running.');
	echo ("<h3><a href=\"LoadDB.php?step=1\">Proceed to First Step</a></h3>");
}
if($step === "1"){
	echo('
		<h1>Step One - User Setup</h1>
		<p>Before we begin we need a little bit of information. Don\'t worry, you can always change these later.</p>
		<form method="post" action="LoadDB.php?step=2">
		<table class="form-table">
			<tr>
				<th scope="row"><label for="uname"> Username: </label></th>
				<td><input  name="uname" id="uname" type="text" size="25" value="" /></td>
				<td> The username that will be used to login to ThrowBack</td>
			</tr>
			<tr>
				<th scope="row"><label for="passwd"> Password: </label></th>
				<td><input name="passwd" id="passwd" type="password" size="25" value="" /></td>
				<td> The password that will be used to login to ThrowBack </td>
			</tr>
		</table>
		<p class="step"><input name="submit" type="submit" value="Submit" class="button button-large" /></p>
		</form>
		');
}
if($step === "2"){

echo("Adding table: 'access':");
$a = $tbdb->query("CREATE TABLE IF NOT EXISTS `access` (
  `ipaddress` varchar(50) NOT NULL,
  `date` varchar(50) NOT NULL,
  `referrer` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", Null);
echo("<font color='green'> Done</font><br>");

echo("Adding table: 'parameters':");
$b = $tbdb->query("CREATE TABLE IF NOT EXISTS `parameters` (
  `id` varchar(255) NOT NULL,
  `cbperiod` varchar(50) NOT NULL,
  `lastupdate` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `privileges` varchar(25) NOT NULL DEFAULT '1',
  `version` varchar(25) NOT NULL DEFAULT '0',
  `ipaddress` varchar(50) NOT NULL,
  `proxyenabled` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", Null);
echo("<font color='green'> Done</font><br>");

echo("Adding table: 'targets':");
$c = $tbdb->query("CREATE TABLE IF NOT EXISTS `targets` (
  `id` varchar(255) NOT NULL,
  `externalip` varchar(50) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `lastupdate` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;",Null);
echo("<font color='green'> Done</font><br>");

echo("Adding table: 'tasks':");
$d = $tbdb->query("CREATE TABLE IF NOT EXISTS `tasks` (
  `type` varchar(10) NOT NULL,
  `id` varchar(255) NOT NULL,
  `command` varchar(500) NOT NULL,
  `arguments` varchar(500) NOT NULL,
  `runas` varchar(25) NOT NULL,
  `key` varchar(50) NOT NULL,
  `status` varchar(25) NOT NULL,
  `results` mediumtext NOT NULL,
  `opentime` varchar(50) NOT NULL,
  `closetime` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;", Null);
echo("<font color='green'> Done</font><br>");

echo("Adding table: 'users':");
$e = $tbdb->query("CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `lastlogin` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;",Null);
echo("<font color='green'> Done</font><br>");
echo("Adding user:");
$user = $tbdb->query("INSERT INTO `users` (`id`, `username`, `password`, `lastlogin`) VALUES
(0, ?, ?, ?);", array($_POST['uname'],sha1($_POST['passwd']),time()));
echo("<font color='green'> Done</font><br>");
echo ("<h2><a href=\"index.php\">Finished, Proceed to Login</a></h2>");
}




?>