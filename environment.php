<?php
if(! defined('ENVIRONMENT') ) {
	$domain = strtolower($_SERVER['HTTP_HOST']);
	
	switch($domain) {
		case 'bibletools.info' :
			define('ENVIRONMENT', 'production');
			break;
		default :
			define('ENVIRONMENT', 'development');
			break;
	}
}
?>
