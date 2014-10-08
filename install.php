<?php
// Disable magic quotes at runtime. Magic quotes are added using wpdb later in wp-settings.php.
@ini_set( 'magic_quotes_runtime', 0 );
@ini_set( 'magic_quotes_sybase',  0 );
?>

<?php include("./cp/includes/header.inc.php") ?>
    
<?php
// Check if we have a step, otherwise default
if(isset($_GET['step'])){
	$step = $_GET['step'];
}else{
	$step = "0";
}
if($step === "0"){

	// Check if tb-config has beeen created in a race with the install file
	if (file_exists('./tb-config.php'))
		die("<p>The file 'tb-config.php' already exists. If you need to reset any of the configuration items in this file,  delete it first and then run through the <a href='install.php'>installation again</a>.</p></body></html>");

	echo('
	<p>Welcome to Throwback. Before getting started, we need some information on the database. You will need to know the following items before proceeding.</p>
	<ol>
		<li>Database name</li>
		<li>Database username</li>
		<li>Database password</li>
		<li>Database host</li>
		<li>Table prefix (if you want to run more than one Throwback from a single database)</li>
	</ol>

	<p>
		We&#8217;re going to use this information to create a <code>tb-config.php</code> file.
		<strong>
	</p>
	<p>In all likelihood, these items were supplied to you by your Web Host. If you do not have this information, then you will need to contact them before you can continue. If you&#8217;re all ready&hellip;</p>
	</p>
	<p class="step"><a href="?step=1" class="button button-large">Let&#8217;s go!</a></p>
	' ); 
}elseif($step === "1"){ 
	echo '
	<script>
	$(function() {
		$("#uname").focus(function() {
		  $(this).val("");
		  });
		$("#pwd").focus(function() {
		  $(this).val("");
		  });
	 });
	 </script>
	<form method="post" action="?step=2">
		<p>Below you should enter your database connection details. If you&#8217;re not sure about these, you probably shouldn&#8217;t be running this.</p>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="dbname"> Database Name </label></th>
				<td><input name="dbname" id="dbname" type="text" size="25" value="ThrowBack" /></td>
				<td> The name of the database you want to run throwback to run from. </td>
			</tr>
			<tr>
				<th scope="row"><label for="uname"> User Name </label></th>
				<td><input name="uname" id="uname" type="text" size="25" value="username" /></td>
				<td> Your database username </td>
			</tr>
			<tr>
				<th scope="row"><label for="pwd"> Password </label></th>
				<td><input name="pwd" id="pwd" type="password" size="25" value="password" autocomplete="off" /></td>
				<td> Your database password.</td>
			</tr>
			<tr>
				<th scope="row"><label for="dbhost"> Database Host</label></th>
				<td><input name="dbhost" id="dbhost" type="text" size="25" value="localhost" /></td>
				<td> This is usually <code>localhost</code>, otherwise check with your web host</td>
			</tr>
			<tr>
				<th scope="row"><label for="prefix"> Table Prefix </label></th>
				<td><input name="prefix" id="prefix" type="text" value="tb_" size="25" /></td>
				<td> Specify the prefix for the tables. </td>
			</tr>
		</table>
		<p class="step"><input name="submit" type="submit" value="Submit" class="button button-large" /></p>

	</form>';
}elseif($step === "2"){ 
	$failure = false;
	echo '';
	$dbname = trim( ( $_POST[ 'dbname' ] ) ); // HTML PURIFY
	$user = trim( ( $_POST[ 'uname' ] ) ); // HTML PURIFY
	$pass = trim( ( $_POST[ 'pwd' ] ) ); // HTML PURIFY
	$dbhost = trim( ( $_POST[ 'dbhost' ] ) ); // HTML PURIFY
	$prefix = trim( ( $_POST[ 'prefix' ] ) ); // HTML PURIFY
	try {
		$DBH = new PDO("mysql:host=$dbhost;dbname=$dbname", $user, $pass);
		$DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch(PDOException $e) {
		echo $e->getMessage();
		echo ("<h3><a href=\"install.php?step=1\">Please go back and correct the above error</a></h2>");
		$failure = true;
	}
	if($failure === false){
		$DBH = null;
		$config_file = array(
		"<?php\n",
		"define('DB_NAME', '".$dbname."');\n",
		"define('DB_USER', '".$user."');\n",
		"define('DB_PASSWORD', '".$pass."');\n",
		"define('DB_HOST', '".$dbhost."');\n",
		"define( 'DB_CHARSET', 'utf8' );\n",
		"define('AUTH_KEY','}~JPTz(GT<9cEas4BtGbQd[h^Ia4=eyN~T-WGB&+#|7r-YK3+x9`@zR=kf-:t:1');\n",
		"define('SECURE_AUTH_KEY',  'AyZgQQxOQYU 2@g`}6jjeCp>|+i-[s|Mvh)gf+j)R`6|l(AP83Dt@27AU^D:G!+5');\n",
		"define('LOGGED_IN_KEY',    'E)n@)&DR<aJ1%d,+MZAY~tnvHBzwgp9Tb-!c-.=-=R0p.ZT;T.Rh0s:g,w{2u4(5');\n",
		"define('NONCE_KEY','j-Rl+Mjm_pz<Gpg*H3O>!b<EQ-XncbJ-/xU%U+|s-!XbpCRS`g i`nALHPveR~N*');\n",
		"define('AUTH_SALT','2>+|HIA?z!_aU$8h u-k&RCxWyZx]q(Pi4[&o=Oke#M, 3GTzL3ofT@`&Gk5F~]-');\n",
		"define('SECURE_AUTH_SALT', 'vx;2GYA73+1{yt$!TANdt1?C<I3; h<eJ|sbn8%f|_SRH:P7Y:ZU3imrX[uFg_D3');\n",
		"define('LOGGED_IN_SALT',   'i;lR}JB+rLGjaR~V^GEFE32VLNz?IM>b`SiQ0-~3YWA8oc/2WO-1:/]Zc=O|)yfJ');\n",
		"define('NONCE_SALT',       '|>>9![[3z{Bh^ rr.JdsgWDlLuKmkO<7D/BBKTb3J%yw`LR=z]; +x ClNYg6*=0');\n",
		"require_once('includes/pdo.php');\n"
		);
		$path_to_tb_config = 'tb-config.php';
		// Check if our current folder is writable
		if (!is_writable('./')) die("Sorry, we can't write to the directory. You'll have to either change the permissions on your ThrowBack directory or create your tb-config.php manually.");
		// We silence errors because it might fail
		$handle = @fopen( $path_to_tb_config, 'w' );
		if($handle === false){
			echo("The config file could not be opened for writing. Please adjust permissions and rerun setup or copy the configuration file below to ".$path_to_tb_config);
			echo("<br><br>Configuration File</br>");
			
			echo("<b><table border=\"3\"><tr><th>");
			foreach( $config_file as $line ) {
				// This data should only be used in HTML context
				echo(htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "<br>");
				//echo($line."<br>"); // NEEDS ESCAPING
			}
			echo("</th></tr></table></b>");
		}else{
			foreach( $config_file as $line ) {
				fwrite( $handle, $line );
			}
			fclose( $handle );
			chmod( $path_to_tb_config, 0666 );
			echo("<p>Alright, Throwback can now communicate with your database... we're ready for the next stage.  If you are ready: <form action=\"LoadDB.php\"><input type=\"submit\" value=\"run the install!\"></form></p> ");
		}
	}
}else{
	echo "An invalid option was entered, try navigating back to <a href='index.php'>the beginning</a> of the process.</p></body></html>";
}
?>

