<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$to      = 'adam@bibletools.info';
$subject = $heading;
$message .= "<br /><b>URL: </b> ".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
$message .= "<br/>User agent: " . $_SERVER['HTTP_USER_AGENT'];
$message .= "<br/>Referral URL: " . $_SERVER["HTTP_REFERER"];
$message .= "IP: " . $_SERVER["REMOTE_ADDR"];
$headers = 'From: adam@bibletools.info' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
$headers  .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
mail($to, $subject, $message, $headers);

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>404 Page Not Found</title>
<style type="text/css">

::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }

body {
	background-color: #f0f3f6;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #717a8f;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
	text-align: center;
	font-size: 20vw;
	line-height: 20vw;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

p {
	margin: 12px 15px 12px 15px;
	text-align: center;
	color: #717a8f;
	font-size: 16px;
}
</style>
</head>
<body>
	<h1>404</h1>
	<p>Sorry! We couldn't find that :(</p>
</body>
</html>