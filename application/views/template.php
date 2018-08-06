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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<?php if( ENVIRONMENT == "production" ) { ?>
		<link href="/assets/app.min.css?v=2.1" rel="stylesheet">
		<script async type="text/javascript" src="/assets/app.min.js?v=2.1"></script>
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
			<li><a href="/"><svg class="icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 00 580 580"><path fill="currentColor" d="M557.1 240.7L512 203.8V104c0-4.4-3.6-8-8-8h-32c-4.4 0-8 3.6-8 8v60.5L313.4 41.1c-14.7-12.1-36-12.1-50.7 0L18.9 240.7c-3.4 2.8-3.9 7.8-1.1 11.3l20.3 24.8c2.8 3.4 7.8 3.9 11.3 1.1l14.7-12V464c0 8.8 7.2 16 16 16h168c4.4 0 8-3.6 8-8V344h64v128c0 4.4 3.6 8 8 8h168c8.8 0 16-7.2 16-16V265.8l14.7 12c3.4 2.8 8.5 2.3 11.3-1.1l20.3-24.8c2.6-3.4 2.1-8.4-1.3-11.2zM464 432h-96V304c0-4.4-3.6-8-8-8H216c-4.4 0-8 3.6-8 8v128h-96V226.5l170.9-140c2.9-2.4 7.2-2.4 10.1 0l170.9 140V432z" class=""></path></svg>Home</a></li>
			<li><a class="donate" target="_blank" href="http://www.gofundme.com/bibletools"><svg class="icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 580 580"><path fill="currentColor" d="M551.9 312c-31.1-26.4-69.3-16.1-88.4-1.8l-60.4 45.5h-3.3c-.2-38-30.5-67.8-69.2-67.8h-144c-28.4 0-56.3 9.4-78.5 26.3L79.8 336H16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h80l41.3-31.5c14-10.7 31.4-16.5 49.4-16.5h144c27.9 0 29.1 40.2-1.1 40.2h-59.8c-7.6 0-13.8 6.2-13.8 13.8v.1c0 7.6 6.2 13.8 13.8 13.8h134.5c9.7 0 19.2-3.2 26.9-9l61.3-46.1c8.3-6.2 20.5-6.7 28.4 0 10.1 8.5 9.3 23.1-.9 30.7L419.4 455c-7.8 5.8-17.2 9-26.9 9H16c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h376.8c19.9 0 39.3-6.5 55.2-18.5l100.8-75.9c16.6-12.5 26.5-31.5 27.1-52 .7-20.5-8.1-40.1-24-53.6zM257.6 144.3l50.1 14.3c3.6 1 6.1 4.4 6.1 8.1 0 4.6-3.8 8.4-8.4 8.4h-32.8c-3.6 0-7.1-.8-10.3-2.2-4.8-2.2-10.4-1.7-14.1 2l-17.5 17.5c-5.3 5.3-4.7 14.3 1.5 18.4 9.5 6.3 20.4 10.1 31.8 11.5V240c0 8.8 7.2 16 16 16h16c8.8 0 16-7.2 16-16v-17.6c30.3-3.6 53.4-31 49.3-63-2.9-23-20.7-41.3-42.9-47.7l-50.1-14.3c-3.6-1-6.1-4.4-6.1-8.1 0-4.6 3.8-8.4 8.4-8.4h32.8c3.6 0 7.1.8 10.3 2.2 4.8 2.2 10.4 1.7 14.1-2l17.5-17.5c5.3-5.3 4.7-14.3-1.5-18.4-9.5-6.3-20.4-10.1-31.8-11.5V16c0-8.8-7.2-16-16-16h-16c-8.8 0-16 7.2-16 16v17.6c-30.3 3.6-53.4 31-49.3 63 2.9 23 20.6 41.3 42.9 47.7z" class=""></path></svg>Donate</a></li>
			<li>
				<a class="history"><svg class="icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 580 580"><path fill="currentColor" d="M504 255.532c.252 136.64-111.182 248.372-247.822 248.468-64.014.045-122.373-24.163-166.394-63.942-5.097-4.606-5.3-12.543-.443-17.4l16.96-16.96c4.529-4.529 11.776-4.659 16.555-.395C158.208 436.843 204.848 456 256 456c110.549 0 200-89.468 200-200 0-110.549-89.468-200-200-200-55.52 0-105.708 22.574-141.923 59.043l49.091 48.413c7.641 7.535 2.305 20.544-8.426 20.544H26.412c-6.627 0-12-5.373-12-12V45.443c0-10.651 12.843-16.023 20.426-8.544l45.097 44.474C124.866 36.067 187.15 8 256 8c136.811 0 247.747 110.781 248 247.532zm-167.058 90.173l14.116-19.409c3.898-5.36 2.713-12.865-2.647-16.763L280 259.778V116c0-6.627-5.373-12-12-12h-24c-6.627 0-12 5.373-12 12v168.222l88.179 64.13c5.36 3.897 12.865 2.712 16.763-2.647z" class=""></path></svg>History</a>
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
						<input id="search" autocomplete="off" autocorrect="off" placeholder="Type a reference"/>
						<a id="clear"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm101.8-262.2L295.6 256l62.2 62.2c4.7 4.7 4.7 12.3 0 17l-22.6 22.6c-4.7 4.7-12.3 4.7-17 0L256 295.6l-62.2 62.2c-4.7 4.7-12.3 4.7-17 0l-22.6-22.6c-4.7-4.7-4.7-12.3 0-17l62.2-62.2-62.2-62.2c-4.7-4.7-4.7-12.3 0-17l22.6-22.6c4.7-4.7 12.3-4.7 17 0l62.2 62.2 62.2-62.2c4.7-4.7 12.3-4.7 17 0l22.6 22.6c4.7 4.7 4.7 12.3 0 17z" class=""></path></svg></a>
						<a class="open-menu"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z" class=""></path></svg></a>
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
			<span class="close"><svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M207.6 256l107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z" class=""></path></span>
			<div class="definition">Loading...</div>
		</div>
	</section>
	<div class="container main">
		<?php echo $contents; ?>
	</div>
</body>
</html>
