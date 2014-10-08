<?php

class tbdb {

	var $show_errors = true;
	var $num_queries = 0;	
	var $last_query;
	var $col_info;
	var $queries;
	
	function __construct($dbuser, $dbpassword, $dbname, $dbhost) {
		register_shutdown_function(array(&$this, "__destruct"));
		$error = "<h1>Error establishing a database connection</h1>
		<p>This either means that the username and password information in your <code>tb-config.php</code> file is incorrect or we can't contact the database server at <code>$dbhost</code>. This could mean your host's database server is down.</p>
		<ul>
			<li>Are you sure you have the correct username and password?</li>
			<li>Are you sure that you have typed the correct hostname?</li>
			<li>Are you sure that the database server is running?</li>
		</ul>
		<p>If you're unsure what these terms mean you probably shouldn't be using this software.</p>
		";
		
		try {
			$this->DBH = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword);
			$this->DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}
		catch(PDOException $e) {
			echo $error;
			die();
		}

		if (!$this->DBH) {
			echo $error;
			die();
		}
	}

	function __destruct() {
		return true;		
	}

	
	// ====================================================================
	//	Format a string correctly for safe insert under all PHP conditions
	
	function escape($string) {
		return addslashes( $string ); // Disable rest for now, causing problems
		if( !$this->dbh || version_compare( phpversion(), '4.3.0' ) == '-1' )
			return mysql_escape_string( $string );
		else
			return mysql_real_escape_string( $string, $this->dbh );
	}

	// ==================================================================
	//	Print SQL/DB error.

	function print_error($str = '') {
		global $EZSQL_ERROR;
		if (!$str) $str = mysql_error();
		$EZSQL_ERROR[] = 
		array ('query' => $this->last_query, 'error_str' => $str);

		$str = htmlspecialchars($str, ENT_QUOTES);
		$query = htmlspecialchars($this->last_query, ENT_QUOTES);
		// Is error output turned on or not..
		if ( $this->show_errors ) {
			// If there is an error then take note of it
			print "<div id='error'>
			<p class='wpdberror'><strong>WordPress database error:</strong> [$str]<br />
			<code>$query</code></p>
			</div>";
		} else {
			return false;	
		}
	}



	// ==================================================================
	//	Basic Query	- see docs for more detail

	function query($query) {
		try{
			$this->stmt = $this->DBH->prepare($query);
			$this->stmt->execute();
			$this->results = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
			$this->stmt->closeCursor();
		}catch (PDOException $e){
			return False;
		}
		return $this->results;
		
	/*	// initialise return
		$return_val = 0;
		$this->flush();

		$this->result = $this->DBH->query('SELECT * FROM table');
 
		++$this->num_queries;


		// If there is an error then take note of it..
//		if ( mysql_error() ) {
	//		$this->print_error();
		//	return false;
		//}

		if ( preg_match("/^\\s*(insert|delete|update|replace) /i",$query) ) {
			$this->rows_affected = $row_count = $this->result->rowCount();
			// Take note of the insert_id
			if ( preg_match("/^\\s*(insert|replace) /i",$query) ) {
				$this->insert_id =	$this->result->lastInsertId();
			}
			// Return number of rows affected
			$return_val = $this->rows_affected;
		} else {
			$i = $this->result->columnCount();

			$num_rows = 0;
			while ( $row = $this->result->fetch(PDO::FETCH_OBJ)) {
				$this->last_result[$num_rows] = $row;
				$num_rows++;
			}

			$this->result->$stmt->closeCursor();

			// Log number of rows the query returned
			$this->num_rows = $num_rows;
			
			// Return number of rows selected
			$return_val = $this->num_rows;
		}
*/
	}



}

$tbdb = new tbdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
?>