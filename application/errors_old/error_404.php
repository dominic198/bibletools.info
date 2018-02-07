<?php 
$to      = 'info@drawnigh.org';
$subject = $heading;
$message .= "<br /><b>URL: </b> ".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
$headers = 'From: info@drawnigh.org' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
$headers  .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
mail($to, $subject, $message, $headers);


?>
<!DOCTYPE html>
<html>
<head>
<title>DrawNigh | Error 404</title>
<link rel="stylesheet" type="text/css" href="/assets/css/style.php"/>
<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<style>
#error {
	padding:18px;
}
#error_wrap {
	width:500px;
	height:auto;
	margin:auto;
	background:white;
	box-shadow: 0 6px 15px -4px #ABABAB;
	-webkit-box-shadow: 0 6px 15px -4px #ABABAB;
	-moz-box-shadow: 0 6px 15px -4px #ababab;
	-o-box-shadow: 0 6px 15px -4px #ababab;
	-ms-box-shadow: 0 6px 15px -4px #ababab;
	margin-top: 120px;
}
h1 {
	padding: 6px 18px;
	border-bottom: 1px solid #EEE;
}
.logo {
	margin:24px auto;
	width:298px;
}
</style>
</head>
<body id="dashboard">
	<div id="error_wrap">
	<h1>Oops! Wrong way - 404</h1>
	<div id="error">
		<p>Unfortunately something went terribly wrong here.  We will try to fix this as soon as possible.  In the meantime, let us know how you ended up here by emailing: <a href="mailto:info@bibletools.info">info@bibletools.info</a></p>
	</div>
	</div>
</body>
</html>