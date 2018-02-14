<?php
$domain = strtolower($_SERVER['HTTP_HOST']);
if( ! defined( "ENVIRONMENT" ) ) {	
	switch( $domain ) {
		case "bibletools.info":
			define( "ENVIRONMENT", "production" );
			break;
		default :
			define( "ENVIRONMENT", "development" );
			break;
	}
}
?>
