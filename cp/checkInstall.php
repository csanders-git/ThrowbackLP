<?php

if ( file_exists( 'tb-config.php') ) {

	// We can continue loading the page that included this

} else {

	$path = './install.php';
	header( 'Location: ' . $path );
	exit;

}
