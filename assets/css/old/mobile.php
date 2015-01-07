<?php header("Content-type: text/css; charset: UTF-8"); include("prefixes.php"); ?>
/*----------------------------*/
/*----Global Styles
/*----------------------------*/
body {
	font-size: 16px;
	background: #eaeaea;
	-webkit-font-smoothing: antialiased;
	font: 16px/1.8 'PT Sans', sans-serif;
}
a, a:link, a:visited {
	text-decoration: none;
	color:#666;
	/*border-bottom:dashed 1px #ccc;*/
	cursor: pointer;
}
a:hover, a:active {
	color:#333;
}
h1 {
	font-size:24px;
	font-weight:normal;
}
h2 {
	font-size:22px;
	color:#666;
	font-weight:normal;
}
h3 {
	color:<?php echo $titleColor; ?>;
}
b {
	font-weight: bold;
	color:#333;
}
i {
	font-style: italic;
}
center {
	text-align: left;
}
input[type='text'],
input[type='tel'],
input[type='password'] {
	border:solid 1px #999;
	padding:8px;
	font-size:18px;
	<?php echo boxShadow("2px 2px 2px rgba(0,0,0,0.1)"); ?>
	outline: none;
}
textarea {
	<?php echo boxShadow("2px 2px 2px rgba(0,0,0,0.1)"); ?>
	border: solid 1px #999;
	padding:8px;
	<?php echo $boxSizing; ?>
	min-width:250px;
	max-width: 100%;
}
input[type='checkbox'] {
	margin:6px 0;
}
hr {
	border-top:dashed 1px #ccc;
	margin:12px 0;
	height:1px;
	clear:both;
	float:left;
	width:100%;
}
form p {
	padding:8px;
}



body #container, #head-content, #head-content #menu-bar {
	width:100%;
}
#pre-head #actions, #menu-bar #search-wrap, #green-strip, .profile-img {
	display:none;
}
#head-content #menu-bar {
	bottom:0;
}
#head-content {
	height:auto;
	margin-top:85px;
}