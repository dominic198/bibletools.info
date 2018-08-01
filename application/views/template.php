<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" manifest="old.manifest">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="BibleTools.info is a web app designed to enhance your Bible study experience by providing powerful resources for almost every verse.">
	<meta name="author" content="Adam Jackson">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, user-scalable=no" />
	<!--[if IE]><link rel="shortcut icon" href="favicon.ico"><![endif]-->
	<link rel="icon" href="/favicon.png"> 
	<link rel="apple-touch-icon" href="/assets/img/icons/Icon-60@2x.png" />
  	<link rel="apple-touch-icon" sizes="180x180" href="/assets/img/icons/Icon-60@3x.png" />
  	<link rel="apple-touch-icon" sizes="76x76" href="/assets/img/icons/Icon-76.png" />
  	<link rel="apple-touch-icon" sizes="152x152" href="/assets/img/icons/Icon-76@2x.png" />
  	<link rel="apple-touch-icon" sizes="58x58" href="/assets/img/icons/Icon-Small@2x.png" />
	
	<title><?php echo $title ?></title>
	
	<link href='https://fonts.googleapis.com/css?family=Lato:300,400,300italic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<?php if( ENVIRONMENT == "production" ) { ?>
		<link href="assets/app.min.css?v=2.0" rel="stylesheet">
		<script type="text/javascript" src="/assets/app.min.js?v=2.0"></script>
	<?php } else { ?>
		<link href="/assets/css/lib.css" rel="stylesheet">
		<link href="/assets/css/custom.css?v=2" rel="stylesheet">
		<script type="text/javascript" src="/assets/js/lib.js"></script>
		<script type="text/javascript" src="/assets/js/custom.js?v=4"></script>
	<?php } ?>
</head>
<body data-spy="scroll" data-offset="0" data-target="#navigation">
	<section id="menu">
		<header><h3><b>BibleTools</b>.info</h3></header>
		<ul class="main">
			<li><i class="fa fa-home"></i><a href="/">Home</a></li>
			<li><i class="fa fa-smile-o"></i><a class="donate" target="_blank" href="http://www.gofundme.com/bibletools">Donate</a></li>
			<li>
				<i class="fa fa-history"></i><a class="history">History</a>
				<ul class="history-list">
					<?php foreach( $history as $key => $item ) { ?>
						<li><a href="/<?php echo $key; ?>" class="dropdown-item ref-link"><?php echo $item; ?></a></li>
					<?php } ?>
				</ul>
			</li>
			<!--<li><i class="fa fa-heart"></i><a>Favorites</a></li>-->
		</ul>
		<hr/>
		<ul class="sub">
			<li><a href="/about/feedback">Send Feedback</a></li>
		</ul>
	</section>
	<nav class="navbar nav-main navbar-expand-sm navbar-dark">
		<div class="container">
			<button class="navbar-toggler" type="button" data-toggle="collapse">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="/">BibleTools.info</a>
			<div class="collapse navbar-collapse">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item <?php if( isset( $active_tab ) && $active_tab == "verses" ) { echo "active"; } ?>">
						<a class="nav-link" href="/">Verses</a>
					</li>
					<li class="nav-item <?php if( isset( $active_tab ) && $active_tab == "about" ) { echo "active"; } ?>">
						<a class="nav-link" href="/about/info">About</a>
					</li>
					<li class="nav-item <?php if( isset( $active_tab ) && $active_tab == "feedback" ) { echo "active"; } ?>">
						<a class="nav-link" href="/about/feedback">Feedback</a>
					</li>
					<li class="nav-item">
						<div class="dropdown">
							<a class="nav-link dropdown-toggle history-toggle" href="javascript:void(0)" data-toggle="dropdown"><svg role="img" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle" width="18" height="18" viewBox="0 0 512 512"><path fill="currentColor" d="M20 24h10c6.627 0 12 5.373 12 12v94.625C85.196 57.047 165.239 7.715 256.793 8.001 393.18 8.428 504.213 120.009 504 256.396 503.786 393.181 392.834 504 256 504c-63.926 0-122.202-24.187-166.178-63.908-5.113-4.618-5.354-12.561-.482-17.433l7.069-7.069c4.503-4.503 11.749-4.714 16.482-.454C150.782 449.238 200.935 470 256 470c117.744 0 214-95.331 214-214 0-117.744-95.331-214-214-214-82.862 0-154.737 47.077-190.289 116H164c6.627 0 12 5.373 12 12v10c0 6.627-5.373 12-12 12H20c-6.627 0-12-5.373-12-12V36c0-6.627 5.373-12 12-12zm321.647 315.235l4.706-6.47c3.898-5.36 2.713-12.865-2.647-16.763L272 263.853V116c0-6.627-5.373-12-12-12h-8c-6.627 0-12 5.373-12 12v164.147l84.884 61.734c5.36 3.899 12.865 2.714 16.763-2.646z" class=""></path></svg></a>
							<ul class="dropdown-menu dropdown-menu-right history-list">
								<?php foreach( $history as $key => $item ) { ?>
									<li><a class="dropdown-item ref-link" href="/<?php echo $key; ?>"><?php echo $item; ?></a></li>
								<?php } ?>
							</ul>
						</div>
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
	<div id="headerwrap">
	    <div class="container">
	    	<div class="row">
	    		<div class="col-lg-12">
					<h1><b>BibleTools</b>.info</h1>
					<h3>Bible Verse Explanations and Resources</h3>	
					<form action="." id="search_form">			
						<input id="search" autocomplete="off" placeholder="Type a reference"/>
						<a class="fa fa-times-circle" id="clear"></a>
						<a class="fa fa-bars open-menu"></a>
						<div class="search-results">
							<ul>
								<li class="heading verse-heading">Suggestion</li>
							</ul>
						</div>
					</form>
					<br>
	    		</div>
	    	</div>
	    </div> <!--/ .container -->
	</div><!--/ #headerwrap -->
	<section id="lexicon" class="col-sm-5">
		<div class="content">
			<span class="arrow"></span>
			<span class="close"><i class="fa fa-close"></i></span>
			<div class="definition">Loading...</div>
		</div>
	</section>
	<div class="container main">
		<?php echo $contents; ?>
	</div>
</body>
</html>
