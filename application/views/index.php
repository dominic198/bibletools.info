<!DOCTYPE html>
<html lang="en" manifest="cache.manifest">
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
	
	<title>BibleTools.info</title>
	
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<?php if( ENVIRONMENT == "production" ) { ?>
		<link href="assets/app.min.css?v=1" rel="stylesheet">
		<script type="text/javascript" src="/assets/app.min.js?v=1"></script>
	<?php } else { ?>
		<link href="assets/css/lib.css" rel="stylesheet">
		<link href="assets/css/custom.css" rel="stylesheet">
		<script type="text/javascript" src="/assets/js/lib.js"></script>
		<script type="text/javascript" src="/assets/js/custom.js"></script>
	<?php } ?>
</head>
<body data-spy="scroll" data-offset="0" data-target="#navigation">
		<!----------TEMPLATES---------->
	<script id="verse_template" type="text/x-jquery-tmpl">
	{{if strongs}}
		<span data-strongs="${strongs}">${word}</span>
	{{else}}
		${word}
	{{/if}}
	</script>
	<script id="egw_template" type="text/x-jquery-tmpl">
	<div class="col-sm-6 box egw" data-reference="${reference}">
		<div class="panel panel-modern">
			<div class="panel-heading">
				<span class="title">
					{{if verse}}
						${chapter}:${verse}{{if endverse}}-${endverse}{{/if}} <span>${reference}</span>
					{{else}}
						<span>${reference}</span>
					{{/if}}
				</span>
				<a href="http://text.egwwritings.org/search.php?lang=en&section=all&collection=2&QUERY=${reference}" title="Open at EGWWritings.org" target="_blank" class="fa fa-share-square-o open"></a>
			</div>
			<div class="panel-body">
				loading ...
			</div>
		</div><!--/ .panel -->
	</div><!--/ .col -->
	</script>
	<script id="bc_template" type="text/x-jquery-tmpl">
	<div class="col-sm-6 box bc">
		<div class="panel panel-modern">
			<div class="panel-heading">${title}</div>
			<div class="panel-body">
				{{html content}}
			</div>
		</div><!--/ .panel -->
	</div>
	</script>
	<script id="map_template" type="text/x-jquery-tmpl">
	<div class="col-sm-6 box map">
		<div class="panel panel-modern">
			<div class="panel-heading">${title}</div>
			<div class="panel-body" >
				<a href="/assets/img/maps/${filename}">
					<img src="/assets/img/maps/${filename}"/>
				</a>
			</div>
		</div><!--/ .panel -->
	</div>
	</script>
	<script id="verse_template" type="text/x-jquery-tmpl">
	{{if strongs}}
		<span data-strongs="${strongs}">${word}</span>
	{{else}}
		${word}
	{{/if}}
	</script>
	<section id="menu">
		<header><h3><b>BibleTools</b>.info</h3></header>
		<ul class="main">
			<li><i class="fa fa-home"></i><a class="home">Home</a></li>
			<li><i class="fa fa-dollar"></i><a class="donate" target="_blank" href="http://www.gofundme.com/bibletools">Donate</a></li>
			<!--<li><i class="fa fa-heart"></i><a>Favorites</a></li>-->
		</ul>
		<hr/>
		<ul class="sub">
			<li><a class="feedback">Send Feedback</a></li>
		</ul>
	</section>
	<div id="headerwrap">
	    <div class="container">
	    	<div class="row centered">
	    		<div class="col-lg-12">
					<h1><b>BibleTools</b>.info</h1>
					<h3>Bible verse resources and analysis tools.</h3>	
					<form action="." id="search_form">			
						<input id="search" placeholder="Enter reference"/>
						<a class="fa fa-times-circle" id="clear"></a>
						<a class="fa fa-bars open-menu"></a>
					</form>
					<br>
	    		</div>
	    	</div>
	    </div> <!--/ .container -->
	</div><!--/ #headerwrap -->
	<section id="lexicon"></section>
	<div class="container main">
		<div class="row">
	    		<div id="resource_list">
	    			<div class="col-sm-6" id="verse">
			    		<div class="panel panel-modern">
							<div class="panel-heading">Loading...</div>
							<div class="panel-body">
								Loading...
							</div>
						</div><!--/ .panel -->
			    	</div>
	    		</div>
	    		<a id="load_more">Load More</a>
		</div><!--/ .row -->
	</div>
	<div id="c">
		<div class="container">
			<p>Created by <a href="http://rawcomposition.com">Adam Jackson</a> • <a href="/about" id="feedback">Feedback</a></p>
		</div>
	</div>
</body>
</html>
