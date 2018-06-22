<?php
$domain = strtolower($_SERVER['HTTP_HOST']);
if( ! defined( "ENVIRONMENT" ) ) {	
	switch( $domain ) {
		case "bibletools.info":
			define( "ENVIRONMENT", "production" );
			break;
		case "beta.bibletools.info":
			define( "ENVIRONMENT", "beta" );
			break;
		default :
			define( "ENVIRONMENT", "development" );
			break;
	}
}
?>
