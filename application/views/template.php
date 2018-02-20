<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="BibleTools.info is a web app designed to enhance your Bible study experience by providing powerful resources for almost every verse.">
	<meta name="author" content="Adam Jackson">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, user-scalable=no" />
	<!--[if IE]><link rel="shortcut icon" href="favicon.ico"><![endif]-->
	<link rel="icon" href="favicon.png"> 
	<link rel="apple-touch-icon" href="/assets/img/icons/Icon-60@2x.png" />
  	<link rel="apple-touch-icon" sizes="180x180" href="/assets/img/icons/Icon-60@3x.png" />
  	<link rel="apple-touch-icon" sizes="76x76" href="/assets/img/icons/Icon-76.png" />
  	<link rel="apple-touch-icon" sizes="152x152" href="/assets/img/icons/Icon-76@2x.png" />
  	<link rel="apple-touch-icon" sizes="58x58" href="/assets/img/icons/Icon-Small@2x.png" />
	
	<title>Answers to Difficult Bible Questions</title>
	
	<link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<?php if( ENVIRONMENT == "production" ) { ?>
		<link href="assets/app.min.css?v=1.6" rel="stylesheet">
		<script type="text/javascript" src="/assets/app.min.js?v=1.5"></script>
	<?php } else { ?>
		<link href="/assets/css/answers.css" rel="stylesheet">
		<script type="text/javascript" src="/assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="/assets/js/lib.js"></script>
		<script type="text/javascript" src="/assets/js/custom.js"></script>
	<?php } ?>
</head>
<body data-spy="scroll" data-offset="0" data-target="#navigation">
	<nav class="navbar nav-main navbar-expand-lg navbar-dark">
		<div class="container">
			<div class="collapse navbar-collapse">
				<a class="navbar-brand mr-auto" href="#">BibleTools.info</a>
				<ul class="navbar-nav">
					<li class="nav-item active">
						<a class="nav-link" href="#">Questions</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">Verses</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">How it Works</span></a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<?php if( $is_admin ) { ?>
		<nav class="navbar navbar-expand-lg navbar-light">
			<div class="container">
				<div class="collapse navbar-collapse">
					<ul class="navbar-nav">
						<li class="nav-item">
							<a class="nav-link" href="/admin/dashboard">Dashboard</span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/admin/question">Add Question</span></a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	<?php } ?>
	<section id="menu">
		<header><h3><b>BibleTools</b>.info</h3></header>
		<ul class="main">
			<li><i class="fa fa-home"></i><a class="home">Home</a></li>
			<li><i class="fa fa-smile-o"></i><a class="donate" target="_blank" href="http://www.gofundme.com/bibletools">Donate</a></li>
			<li>
				<i class="fa fa-history"></i><a class="history">History</a>
				<ul id="history_list"></ul>
			</li>
			<!--<li><i class="fa fa-heart"></i><a>Favorites</a></li>-->
		</ul>
		<hr/>
		<ul class="sub">
			<li><a class="feedback">Send Feedback</a></li>
		</ul>
	</section>
	<div id="headerwrap">
	    <div class="container">
	    	<div class="row text-center">
	    		<div class="col-lg-12">
					<h1><b>BibleTools</b>.info</h1>
					<h3>Bible Verse Explanations and Resources</h3>	
					<form action="." id="search_form">			
						<input id="search" autocomplete="off" placeholder="Search for a verse or question..."/>
						<a class="fa fa-times-circle" id="clear"></a>
						<div class="search-results">
							<ul>
								<li class="heading verse-heading">Verses</li>
								<li class="heading">Questions</li>
								<li><a href="/question/why-did-god-curse-israel-with-famine-for-3-5-years">Why did God curse Israel with famine for 3.5 years?</a></li>
							</ul>
						</div>
					</form>
					<br>
	    		</div>
	    	</div>
	    </div> <!--/ .container -->
	</div><!--/ #headerwrap -->
	<div class="container main">
		<?php echo $contents; ?>
	</div>
</body>
</html>
