<?php header("Content-type: text/css; charset: UTF-8"); include("prefixes.php"); ?>
@import url('reset.css');
/*----------------------------*/
/*----Global Styles
/*----------------------------*/
body {
	font-size: 16px;
	background: #eaeaea;
	-webkit-font-smoothing: antialiased;
	font: 16px/1.8 'PT Sans', sans-serif;
	background: url(/assets/img/background.jpg);
	background-size: 100%;
	background-attachment: fixed;
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
em { font-style: italic; }
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
input.loading {
	background: white url(/assets/img/loader_white.gif) no-repeat right center;
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

/*----------------------------*/
/*----CUSTOM Global Styles
/*----------------------------*/
.error {
	padding: 8px;
	margin: 8px;
	background: #9b2b2b;
	<?php echo $redGrad; ?>
	<?php echo $boxSizing; ?>
	color: #eee;
	width: 100%;
}
.success {
	padding:8px;
	margin: 8px;
	width:100%;
	<?php echo $greenGrad; ?>
	<?php echo $boxSizing; ?>
}
.ui-widget-content {
	<?php echo boxShadow("0 1px 2px rgba(0,0,0,0.3)"); ?>
}
.feedbackBox {
	display: block;
	position: fixed;
	left:-23px;
	top:75%;
	padding:4px 8px;
	color:#eee;
	background: #444;
	border: solid 1px #fff;
	border-bottom: none;
	<?php echo rotate("90"); ?>
}
#shareBox .emails, #shareBox .url {
	width:445px;
}
#shareBox {
	height:485px !important;
}
.right {
	float:right;
}
.left {
	float:left;
}
.hidden {
	display:none !IMPORTANT;
}
.center {
	position: absolute;
	top:49%;
	margin:auto;
}
.clear:after {
    content: ".";
    display: block;
    height: 0;
    clear: both;
    visibility: hidden;
}
.loader {

	float:right;
	padding:6px;
}
p.small {
	font-size: 12px;
	line-height: 14px;
	color:<?php echo $titleColor; ?>;
}
.highlight {
	<?php echo $greenGrad; ?>
}
.hint {
	font-style: italic;
	color: <?php echo $titleColor; ?>;
	font-size: 18px;
}
.hint.center {
	position: absolute;
	top: 49%;
	width: 100%;
	display: block;
	text-align: center;
}	
html.disableScroll {
	overflow-y:hidden !IMPORTANT;
}
body .error.global {
	position: fixed;
	width:350px;
	left:50%;
	margin-left:-175px;
	top:12px;
	z-index: 9999999;
	<?php echo boxShadow("0 2px 10px 1px rgba(0,0,0,.4)"); ?>
	text-align: center;
	<?php echo borderRadius("4px"); ?>
}

/*----------------------------*/
/*----DROP DOWN SELECT
/*----------------------------*/
.dropdown dd { 
	position:relative;
}
dl.dropdown { 
	float:left;
	width:auto;
	border: none;
	<?php //echo boxShadow("white 0 1px 0 0"); ?>
	<?php //echo $greyGrad; ?>
	<?php //echo borderRadius("4px"); ?>
	/*border: 1px solid #AAA;*/
	padding:0px 6px;
	line-height: 28px;
}
dl.dropdown.wood {
	border: 1px solid rgba(120, 73, 0, 0.63);
	padding: 0 9px;
	margin-left: 6px;
	background: rgba(162, 105, 30, 0.5);
	<?php echo borderRadius("4px"); ?>
	<?php echo boxShadow("0 0 19px 0 rgba(0, 0, 0, 0.18) inset, 0 1px 1px 0 rgba(0, 0, 0, 0.1) inset, inset 0px -15px 16px rgba(255, 255, 255, 0.1)"); ?>
	color:white;
}
dl.dropdown#book {
	margin-right:8px;
}
dl.dropdown#chapter {
	width:auto;
	padding-right: 0;
}
dl.dropdown#chapter ul {
	overflow-y: auto;
	min-width: 180px;

}
dl.dropdown#chapter ul li {
	float:left;
	border-left: 1px solid #2A2A2A;
	border-right: 1px solid black;
}
dl.dropdown#chapter ul li a {
	width:28px;
	height:28px;
	padding: 0;
	text-align: center;
	line-height: 29px;
}
.dropdown dt a {
	display:block;
	padding-right:20px;
	border:none;
}
.dropdown dt a:hover {
	background:none;
}
.dropdown dt a h2 {
	cursor:pointer;
	display:block;
	font-size:16px;
	color:#666;
}
.dropdown dt a h2 span.more {
	position: relative;
	top: -2px;
	border-style: solid dashed dashed;
	border-color: transparent;
	border-top-color: silver;
	display: -moz-inline-box;
	display: inline-block;
	font-size: 0;
	height: 0;
	line-height: 0;
	width: 0;
	border-width: 5px 5px 0;
	padding-top: 1px;
	left: 10px;
}
.dropdown dd ul { 
	background:#222;
	border:none;
	left:0px;
	padding:5px 0px;
	position:absolute;
	top:15px;
	width:auto;
	min-width:190px;
	list-style:none;
	max-height:310px;
	overflow-y: scroll;
	border:solid 4px #333;
	<?php echo borderRadius("4px"); ?>
	margin-left:-20px;
	z-index: 9999;
	
}
#book_code.dropdown dd ul {
	width:265px;
}
.dropdown dd ul h3 {
	color:#666;
	margin-left:8px;
}
#book_code.dropdown span.code {
	display:none;
}
.dropdown dd .wrap {
	display:none;
}
.dropdown dd .arrow {
	position: absolute;
	top: 4px;
	border-style: solid dashed dashed;
	border-color: transparent;
	border-bottom-color: #333;
	display: -moz-inline-box;
	display: inline-block;
	font-size: 0;
	height: 0;
	line-height: 0;
	width: 0;
	border-width: 0 8px 10px;
	padding-top: 1px;
	left: 10px;
}
.dropdown span.value { display:none;}
.dropdown dd ul li a { 
	padding:3px 8px;
	display:block;
	border-top: 1px solid #2A2A2A;
	border-bottom: 1px solid black;
	color:#eee;
}
.dropdown dd ul li a:hover { 
	<?php echo $greenGrad; ?>
	<?php echo boxShadow("0 0 19px 0 rgba(0, 0, 0, 0.30) inset, 0 1px 1px 0 rgba(0, 0, 0, 0.3) inset"); ?>
	color:#444;
}

.dropdown img.flag { border:none; vertical-align:middle; margin-left:10px; }
.flagvisibility { display:none;}

/*----------------------------*/
/*----BROWSER
/*----------------------------*/
#browser .loader {
	background: url(/assets/img/loader_white.gif);
	width:32px;
	height:32px;
	top:50%;
	left:50%;
	margin-top:-16px;
	margin-left:-16px;
	padding:0;
	position: absolute;
}
#reader .sub_toolbar {
	margin-bottom:12px;
	float:left;
}
#browser .search_form {
	margin-bottom:12px;
	float:right;
}
#browser #egw_right .search_form input {
	width: 331px;
}
#browser .search_form input {
	<?php echo boxShadow("white 0 1px 0 0"); ?>
	<?php echo $greyGrad; ?>
	<?php echo borderRadius("4px"); ?>
	border: 1px solid #AAA;
	padding:3px 6px;
	float:left
	font-size:16px;
	width:165px;
}


#browser #reader {
	position: relative;
}
#browser #details {
	overflow-y:auto;
	height:467px;
	padding-right: 24px;
}

#browser .col.right {
	width:37%;
	display:none;
}
#details h2 {
	margin: 10px 0 4px 0;
}
#details p {
	margin-top: 8px;
}
#details ol {
	margin-left:20px;
	margin-top:8px;
}

#reader .verse strong {
	<?php echo borderRadius("3px"); ?>
	<?php //echo $greyGrad; ?>
	background-color: #EEEDEB;
	border-top: 1px solid #CCC;
	color: #666;
	cursor: pointer;
	display: inline-block;
	font-family: "Helvetica Neue", Arial, "Liberation Sans", FreeSans, sans-serif;
	font-size: 10px;
	font-weight: bold;
	line-height: 1;
	margin-top: 4px;
	margin-right: 6px;
	padding: 2px 4px 3px;
}
#reader .verse {
	margin-top:8px;
	font-size:14px;
	color:#444;
	cursor: pointer;
}
#reader .verse.result {
	border-bottom: dashed 1px #CCC;
	padding-bottom: 8px;
}
#reader .verse.selected {
	background: #FFF6E3;
}
#reader #chapterContent {
	clear:both;
	margin-top:4px;
	/*overflow-y: auto;
	height: 408px;*/
	padding-bottom: 50px;
	/*border-right:solid 1px #e9e9e9;*/
	padding-right:24px;
}
#reader #chapterContent::after {
	background-size: 100%;
	background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, rgba(255, 255, 255, 0)), color-stop(100%, white));
	background-image: -webkit-linear-gradient(rgba(255, 255, 255, 0), white);
	background-image: -moz-linear-gradient(rgba(255, 255, 255, 0), white);
	background-image: -o-linear-gradient(rgba(255, 255, 255, 0), white);
	background-image: -ms-linear-gradient(rgba(255, 255, 255, 0), white);
	background-image: linear-gradient(rgba(255, 255, 255, 0),white);
	content: "";
	position: absolute;
	top: auto;
	left: 0;
	right: 0;
	bottom: 0;
	height: 50px;
	pointer-events: none;
}
#reader #chapterContent .load_more {
	margin:8px 0;
	border:none;
	float:left;
}
#reader .shadow.top {
	<?php echo boxShadow("0px 10px 10px -7px #eee inset"); ?>
	height:50px;
	margin-bottom:-50px;
	clear:left;
}

#browser #reader { min-height:200px; width:100%; }



/*----------------------------*/
/*----STUDY BROWSER
/*----------------------------*/
.study #browser .toolbar {
	padding:4px 24px;
}
/*----------------------------*/
/*----Boxes
/*----------------------------*/
#col-right .bc { background: #DCC4BF !important; }
#col-right .bc h4.title { background:#CA9B92 !important; }
#col-right .bc p:after {
	background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, rgba(255, 255, 255, 0)), color-stop(100%, #A8DBF3)) !important;
	background-image: -webkit-linear-gradient(rgba(255, 255, 255, 0), #DCC4BF) !important;
	background-image: -moz-linear-gradient(rgba(255, 255, 255, 0), #DCC4BF) !important;
	background-image: -o-linear-gradient(rgba(255, 255, 255, 0), #DCC4BF) !important;
	background-image: -ms-linear-gradient(rgba(255, 255, 255, 0), #DCC4BF) !important;
	background-image: linear-gradient(rgba(255, 255, 255, 0),#DCC4BF) !important;
}

#col-right {
	width:48%;
	float:right;
	<?php echo $boxSizing; ?>	
	position: relative;
	overflow:auto;
	padding-bottom: 30px;
}

#col-right .box {
	margin-bottom:12px;
	float:left;
	width:48%;
	cursor:pointer;
	background: #A8DBF3;
	<?php echo boxShadow("0 1px 4px rgba(0,0,0,0.27), 0 0 40px rgba(0,0,0,0.06) inset"); ?>
}
#col-right .box {
	margin-right:8px;
}

#col-right .box span.head { font-weight:bold; font-style:italic; }

#col-right .box h4.title {
	background:#92B8CA;
	color:white;
	padding:5px;
	white-space: nowrap;
	text-overflow: ellipsis;
	overflow: hidden;
}

#col-right .box h4.title .open { float:right; display:none; padding:5px 0; color:#eee; }
#col-right .box h4.title .open:hover { color:#fff; }

#col-right .box .content {
	padding:5px;
	position:relative;
	height:120px;
	overflow:hidden;
	font-size:12px;
	padding-bottom:40px;
}

#col-right .box .content a { cursor:text; }

#col-right p { margin-bottom: 14px; }

#col-right .box.expand .content {
	height:auto;
	cursor:text;
}
#col-right .box.expand .content:after {
	height:0;
}
#col-right .box.expand {
	width:98%;
}
#col-right .box.expand .open { display:block !important; }
#col-right .box h2 { display:none; }

#col-right .box p:after {
	background-size: 100%;
	background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, rgba(255, 255, 255, 0)), color-stop(100%, #A8DBF3));
	background-image: -webkit-linear-gradient(rgba(255, 255, 255, 0), #A8DBF3);
	background-image: -moz-linear-gradient(rgba(255, 255, 255, 0), #A8DBF3);
	background-image: -o-linear-gradient(rgba(255, 255, 255, 0), #A8DBF3);
	background-image: -ms-linear-gradient(rgba(255, 255, 255, 0), #A8DBF3);
	background-image: linear-gradient(rgba(255, 255, 255, 0),#A8DBF3);
	content: "";
	position: absolute;
	top: auto;
	left: 0;
	right: 0;
	bottom: 0;
	height: 70px;
	pointer-events: none;
}
#col-right .box .loader {
	background: url(/assets/img/loader_white.gif);
	width:32px;
	height:32px;
	top:8px;
	left:50%;
	margin-top:-16px;
	margin-left:-16px;
	padding:0;
	position: absolute;
}

#col-right #load_more {
	width:100%;
	position:absolute;
	bottom:0;
	left:0;
	padding:2px;
	text-align:center;
	background:#eee;
	<?php echo $boxSizing; ?>
}

/*----------------------------*/
/*----MAIN PAGE ELEMENTS
/*----------------------------*/
header {
	width:100%;
	margin-top:12px;
}
footer {
	<?php echo $blackGrad; ?>
	padding:22px;
	clear:both;
	margin-top:12px;
	text-align: center;
	color:<?php echo $titleColor; ?>;
}
footer h3 {
	color: #ccc;
	border-bottom: dashed 1px #666;
}
footer a, footer a:hover {
	color: #ccc;
	clear:left;
}
footer ul {
	list-style-type:circle;
}
footer.extended {
	background:#222;
	overflow:auto;
	text-align:left;
}
footer .widgets {
	width:980px;
	margin:auto;
}
footer .widget {
	float: left;
	width: 222px;
	margin-bottom: 12px;
	margin-right: 23px;
}
body #container {
	padding:12px 0;
	margin:auto;
	width:980px;
	display:none;

}
#pre-head {
	padding:0 12px;
	position: relative;
}
#pre-head #logo {
	float:left;
	width:298px;
	height:76px;
	background:url(/assets/img/logo.png);
	border:none;
}
#head-content {
	clear:both;
	padding-top:1px;
	margin-top:34px;
	float:left;
	height:120px;
	position:relative;
	width:980px;
}
/*----------------------------*/
/*----DASHBOARD
/*----------------------------*/
#pre-head #actions {
	margin-top:12px;
	position: absolute;
	bottom:0;
	right:12px;
}
#green-strip {
	width:100%;
	position:absolute;
	background:#343434;
	<?php echo boxShadow("inset 0px -60px 55px rgba(0,0,0,0.5)"); ?>
	background-image:url(/assets/img/bg.png);
	height:105px;
	top:122px;
	z-index:-1;
}
.load_more .down {
	position: relative;
	top: -1px;
	border-style: solid dashed dashed;
	border-color: transparent;
	border-top-color: silver;
	display: -moz-inline-box;
	display: inline-block;
	font-size: 0;
	height: 0;
	line-height: 0;
	width: 0;
	border-width: 5px 5px 0;
	padding-top: 1px;
	left: 5px;
}

/*----------------------------*/
/*----EDIT JOURNAL
/*----------------------------*/
#toolbar, .toolbar {
	width: 100%;
	padding:6px 24px;
	background-image: -webkit-linear-gradient(#EEEDEB, #DDDCDA);
	background-image: -moz-linear-gradient(#EEEDEB, #DDDCDA);
	background-image: linear-gradient(#EEEDEB,#DDDCDA);
	border-bottom: 1px solid #CBC9CF;
	border-top: 1px solid #FEFEFE;
	margin: -12px 0 22px -24px;
}
#browser .toolbar {
	padding:6px 12px;
}
#toolbar .inside, .toolbar .inside {
	overflow:visible;
	color:<?php echo $titleColor; ?>;	
	line-height:18px;
	text-align: left;
	margin:auto;
	height:30px;
}
.toolbar .inside {
	line-height: 26px;
}
/*#toolbar a.button, .toolbar a.button {
	border: none;
	<?php echo $greyGrad; ?>
	<?php echo borderRadius("4px"); ?>
	border: 1px solid #AAA;
	padding:3px 6px;
}*/
.toolbar a {
	padding: 0 6px;
	margin-right:6px;
}
#toolbar span, .toolbar span {
	line-height:24px;
	margin-left:8px;
}
#toolbar a:active, .toolbar a:active {
	<?php echo $blackGrad; ?>
	color: #eee;
}

#dashboard #body-wrap #col-left {
	clear:both;
	padding:12px 24px;
	<?php echo $boxSizing; ?>
	<?php echo boxShadow("0 1px 4px rgba(0,0,0,0.27), 0 0 40px rgba(0,0,0,0.06) inset"); ?>
	background: #fff;
	position: relative;
	overflow:auto;
	width:50%;
	float:left;
}
#toolbar time {
	font-size:16px;
	color: <?php echo $titleColor; ?>;
	margin-left:0;
}
