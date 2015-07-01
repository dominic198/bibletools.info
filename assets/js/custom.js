$(document).ready(function(){
	$(document).ajaxError(function(e, jqxhr, settings, exception) {
		throwError( "Oops.  An error occurred." );
	});
	
	initializeApp();
	
	window.onpopstate = function(event) {
		if(event.state != null){
			getVerse(event.state, false);
		}
	};
	
	$(function() {
	    FastClick.attach(document.body);
	});

	$( "#resource_list" ).isotope();
	
	$( "form#search_form" ).submit(function(e){
		e.preventDefault();
	});
	
	window.addEventListener( "load", function(e) {
	
		window.applicationCache.addEventListener('updateready', function(event) {
			setTimeout(function(){ $( ".updating .progress-bar" ).css( "width", "100%" ); }, 700);
			setTimeout(function(){ location.reload(); }, 1500);
		}, false);
		
		window.applicationCache.addEventListener('cached', function(event) {
			$( ".overlay, .updating" ).hide();
		}, false);
			
		window.applicationCache.addEventListener('downloading', function(event) {
			$( "body" ).prepend('<div class="overlay"></div><div class="alert updating global error alert-success" role="alert"></span>Downloading update...<div class="progress"><div class="progress-bar progress-bar-striped active" style="width: 0%"></div></div></div>');		
		}, false);
		
		window.applicationCache.addEventListener('progress', function(event) {
			$( ".updating .progress-bar" ).css( "width", event.loaded/event.total*90 + "%" );
		}, false);
	
	}, false);
	
	var autocompleteBooks = ["Genesis ","Exodus ","Leviticus ","Numbers ","Deuteronomy ","Joshua ","Judges ","Ruth ","1 Samuel ","2 Samuel ","1 Kings ","2 Kings ","1 Chronicles ","2 Chronicles ","Ezra ","Nehemiah ","Esther ","Job ","Psalm ","Proverbs ","Ecclesiastes ","Song of Solomon ","Isaiah ","Jeremiah ","Lamentations ","Ezekiel ","Daniel ","Hosea ","Joel ","Amos ","Obadiah ","Jonah ","Micah ","Nahum ","Habakkuk ","Zephaniah ","Haggai ","Zechariah ","Malachi ","Matthew ","Mark ","Luke ","John ","Acts ","Romans ","1 Corinthians ","2 Corinthians ","Galatians ","Ephesians ","Philippians ","Colossians ","1 Thessalonians ","2 Thessalonians ","1 Timothy ","2 Timothy ","Titus ","Philemon ","Hebrews ","James ","1 Peter ","2 Peter ","1 John ","2 John ","3 John ","Jude ","Revelation "];
	
	$( "input#search" ).autocomplete({
		source: autocompleteBooks,
		autoFocus: true,
		delay: 0,
		select: function( event, ui ) {
			var originalEvent = event;
			while (originalEvent) {
			    if (originalEvent.keyCode == 13)
			        originalEvent.stopPropagation();
			    if (originalEvent == event.originalEvent)
			        break;
			    originalEvent = event.originalEvent;
			}
		}
	});
	
	$( ".open-menu" ).click( function(){
		openMenu();
	});
	
	$( ".overlay.menu" ).live( "click", function() {
		closeMenu();
	});
	
	$( "#menu .home" ).click( function(e){
		e.preventDefault();
		closeMenu();
		if( localStorage['lastRef'] ){
			getVerse( localStorage['lastRef'] );
		} else {
			getVerse( "Matt_1.1" );
		}
	});
	
	$( "input#search" ).live( "keydown", function(e) {
		if(e.which == 9) { // Tab
		    return false;
		}
		if(e.which == 13) { // Enter
			getVerse($(this).val());
		}
	});
		
	$( ".bc .panel-body a" ).live( "click", function(e){
		
		if($(this).data( "datatype" ) == "bible" ){
			e.preventDefault();
			ref = $(this).data( "reference" );
			getVerse(ref);
						
		} else {
			return false;
		}
	});
	
	$( ".bc .panel-body .scriptRef" ).live( "click", function(e){
			ref = $(this).attr( "ref" );
			getVerse(ref);
	});
	
	$( ".egw .panel-body span.bible-kjv" ).live( "click", function(e){
		e.preventDefault();
		ref = $(this).attr( "title" );
		getVerse(ref);
	});
	
	$( "#verse .prev" ).live( "click", function(e){
		e.preventDefault();
		ref = $( "#verse" ).attr( "data-prev" );
		getVerse(ref);
	});
	
	$( "#verse .next" ).live( "click", function(e){
		e.preventDefault();
		ref = $( "#verse" ).attr( "data-next" );
		getVerse(ref);
	});
	
	$( "#clear" ).live( "click", function(e){
		$( "#search" ).val( "" ).focus();
		$(this).hide();
	});
	
	$( ".feedback" ).click(function(e){
		e.preventDefault();
		closeMenu();
		getPage( "about" );
	});
	
	$( "#search" ).keydown(function(){
		if($(this).val() == "" ){
			$( "#clear" ).hide();
		} else {
			$( "#clear" ).show();
		}
	});
	
	$(document).swipe({
		swipeLeft:function() {
			ref = $( "#verse" ).attr( "data-next" );
			if( ref != undefined ){
				getVerse(ref);
			}
		},
		swipeRight:function() {
			ref = $( "#verse" ).attr( "data-prev" );
			if( ref != undefined ){
				getVerse(ref);
			}
		},
		fallbackToMouseEvents:false,
		threshold:200
	});
	
	$( ".box.bc, .box.egw" ).live( "click", function() {
		$(this).toggleClass( "expand" );
		$( "#resource_list" ).isotope( 'reloadItems' ).isotope();
	});
	
	$( ".expand.box .panel-body" ).live( "click", function() {
		return false;
	});
	
	$( "#load_more" ).live( "click", function() {
		$( this ).html( "loading..." );
		var ref = $( "#verse" ).attr( "data-ref" );
		var offset = $( ".egw" ).length;
					
		if( ! isNaN( verse ) ) {
			$.getJSON( "/egw/get/" + ref + "/" + offset, function( data ) {
				$( "#egw_template" ).tmpl( data.items ).appendTo( "#resource_list" );
				loadEGWContent();
				$( "#load_more" ).html( "Load More" );
				if( $( ".box.egw" ).length == data.total ) {
					$( "#load_more" ).hide();
				}
				$( "#resource_list" ).isotope( "reloadItems" ).isotope();
			});
		}
	});
	
	$( "form#contact #submit" ).live( "click", function(e) {
		e.preventDefault();
		$( "form#contact" ).submit();
	});
	
	$( "form#contact" ).live( "submit", function(e) {
		e.preventDefault();
		if($(this).find( "#message" ) != "" ){
			data = $(this).serializeArray();
			btn =  $(this).find( "#submit" );
			btnText = btn.text();
			btn.text( "Sending feedback..." );
			$.post( "/about/submit_message", data, function(){
				throwSuccess( "Feedback sent successfully" );
				$('form#contact').trigger( "reset" );
				btn.text(btnText);
			});
		}
	});
	
	$( "#verse .panel-body span" ).live( "click", function(e) {
		position = $(this).offset();
		width = $(this).width();
		$lexicon = $( "#lexicon" );
		$lexicon.css( "left", position.left ).css( "top", position.top ).css( "margin-left", "-" + ( 44 - width / 2 ) + "px" );
		if( ! $lexicon.hasClass( "visible" ) ) {
			$( "#lexicon" ).show().addClass( "animate visible" );
		}
	});
	
	$( "body" ).click(function() {
		$( "#lexicon" ).removeClass( "animate visible" );
	});
	
	$( "#lexicon" ).click(function(e) {
	    e.stopPropagation();
	});
	
	function titleCase(str){
	    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	}
	
	function closeMenu(){
		$( ".overlay.menu" ).fadeOut( 160 ).remove();
		$( "#menu" ).removeClass( "show" );
	}
	
	function openMenu(){
		$( "body" ).append( "<div class='overlay menu'></div>" )
		$( ".overlay.menu" ).fadeIn( 160 );
		$( "#menu" ).addClass( "show" );
	}
	
	function getVerse( stringRef, updateState ){
		if( stringRef == "about" ){
			getPage( "about" );
			return;
		}
		
		initializePage();
			
		arrayRef = parseVerse( stringRef );
		book = getBcvBook( arrayRef[0] );
		book_name = getBook( book );
		chapter = arrayRef[1];
		verse = arrayRef[2];
		numericRef = formatRef( book, chapter, verse );
		
		if( updateState != false ){
			stateRef = getBcvBook( book ) + "_" + chapter + "." + verse;
			window.history.pushState( stateRef, null, stateRef );
			localStorage['lastRef'] = stateRef;
		}
		
		$( "#verse .panel-heading" ).text( book_name + " " + chapter + ":" + verse );
		verse_elem = $( "#verse" );
		verse_elem.attr( "data-book", book );
		verse_elem.attr( "data-chapter", chapter );
		verse_elem.attr( "data-verse", verse );
		verse_elem.attr( "data-ref", numericRef );
		$( "#verse .loader" ).remove();
		$( "input#search" ).val( book_name + ' ' + chapter + ':' + verse );
		$( "#clear" ).show();
					
		$.getJSON( "/resources/" + numericRef, function( data ) {
			updateNavigation( data.nav );
			$( "#verse_template" ).tmpl( data.verse ).appendTo( "#verse .panel-body" );
			commentaries = $( "#bc_template" ).tmpl( data.commentaries );
			$( "#resource_list" ).isotope( "insert", commentaries ).isotope();
			
			maps = $( "#map_template" ).tmpl( data.maps );
			$( "#resource_list" ).isotope( "insert", maps ).isotope();
			$( ".box.map a" ).unbind();
			var lightbox = $( ".box.map a" ).imageLightbox( {
				onStart: 	 function() { overlayOn(); closeButtonOn( lightbox ); },
				onEnd:	 	 function() { overlayOff(); activityIndicatorOff(); closeButtonOff(); $( ".pinch-modal" ).remove(); },
				onLoadStart: function() { activityIndicatorOn(); },
				onLoadEnd:	 function() { activityIndicatorOff(); new RTP.PinchZoom( ".pinch-zoom", {}); },
				quitOnImgClick: false
			});
			egw = $( "#egw_template" ).tmpl( data.egw.items );
			$( "#resource_list" ).isotope( "insert", egw ).isotope();
			loadEGWContent();
			if($( ".box.egw" ).length == data.egw.total){
				$( "#load_more" ).hide();
			}else{
				$( "#load_more" ).show();
			}
			
		});
		
		var activityIndicatorOn = function(){
			$( '<div id="imagelightbox-loading"><div></div></div>' ).appendTo( 'body' );
		},
		activityIndicatorOff = function(){
			$( '#imagelightbox-loading' ).remove();
		},
		overlayOn = function(){
			$( '<div class="overlay"></div>' ).appendTo( 'body' );
		},
		overlayOff = function(){
			$( '.overlay' ).remove();
		},
		closeButtonOn = function( instance ){
			$( '<button type="button" id="imagelightbox-close" title="Close"></button>' ).appendTo( 'body' ).on( 'click', function(){ 
				$( this ).remove(); instance.quitImageLightbox(); return false;
			});
		},
		closeButtonOff = function(){
			$( '#imagelightbox-close' ).remove();
		};
	}
	
	function getPage(page){
		if(page == "about" ){
			initializePage();
			$.ajax( "/about/contact_form" ).done(function(response) {
				$( "#verse .panel-body" ).html(response);
				$( "#verse .panel-heading" ).text( "Feedback" );
				$( "#verse .loader" ).remove();
				$( "input#search" ).val( "" );
				window.history.pushState( "about", null, "about" );
				$( "#clear" ).hide();
				$( "#resource_list" ).isotope();
			});
		}
	}
	
	function parseVerse(ref){
		ref = ref.replace( "_", " " );
		var bcv = new bcv_parser;
		bcv.set_options( { book_alone_strategy: "first_chapter" } );
		new_ref = bcv.parse( ref ).osis();
		new_ref = new_ref.split( "-" );
		new_ref = new_ref[0];
		new_ref = new_ref.split( "." );
		
		if( ! new_ref[2] ) { new_ref[2] = "1" }
		
		return new_ref;
	}
	
	function createNumericRef( book, chapter, verse ){
		return padLeft( book, 2 ) + padLeft( chapter, 3 ) + padLeft( verse, 3 );
	}
	
	function padLeft(nr, n, str){
	    return Array(n-String(nr).length+1).join(str||'0')+nr;
	}
	
	function initializePage(){
		$( "#resource_list .box, #verse .prev, #verse .next" ).remove();
		$( "#verse .panel-body" ).empty().html('<div class="loader"></div>');
		$( "#verse" ).removeAttr( "data-prev data-next" );
		$( "#search_form input" ).blur();
		$( "#load_more" ).hide();
	}
	
	function initializeApp(){
		path = window.location.pathname
	
		path = path.replace( "/", "" );
		if( window.navigator.standalone || path == "" ){
			if( localStorage['lastRef'] ){
				getVerse( localStorage['lastRef'] );
			} else {
				getVerse( "Matt_1.1" );
			}
		} else {
			getVerse(path);
		}
	}
	
	function throwError( msg ){
		$( "body" ).prepend('<div class="alert global error alert-danger" role="alert"></span> <span class="sr-only">Error:</span> ' + msg + '</div>');
		$( ".global.error" ).delay(4000).fadeOut(2000);
	}
	
	function throwSuccess( msg ){
		$( "body" ).prepend('<div class="alert global error alert-success" role="alert"></span>' + msg + '</div>');
		$( ".global.error" ).delay(4000).fadeOut(2000);
	}
	
	function updateNavigation(nav){
		verse_elem = $( "#verse" );
		verse_elem.attr( "data-prev", nav.prev);
		verse_elem.attr( "data-next", nav.next);
		if( nav.next ){
			verse_elem.find( ".panel-heading" ).append('<a title="Next Verse" class="fa fa-caret-right next"></a>');	
		}
		if( nav.prev ){
			verse_elem.find( ".panel-heading" ).append('<a title="Previous Verse" class="fa fa-caret-left prev"></a>&nbsp;&nbsp;');	
		}
	}
	
	function loadEGWContent(){
		
		var ajax = new Array();
		$( ".egw" ).each(function(){
		var egw_item = $(this);
			var ref = encodeURIComponent($(this).data( "reference" ));
			if(ref !== "undefined" ){
				$.get( "/egw/content/" + ref, function(data) {
					var data = $.parseJSON(data);
					egw_item.find( ".panel-body" ).empty().append(data.content);
					egw_item.find( ".title" ).empty().append(data.title);
					$( "#resource_list" ).isotope( 'reloadItems' ).isotope();
				});
			}
		});
	}
	
	function loadGreekLex(number, word){
		$.getJSON( "/kjv/greek_lex/" + number, function(data) {
			$( "#details .lex" ).empty();
			$( "#greek_lex_template" ).tmpl(data).appendTo( "#details .lex" );
			word = word.replace(/[\.,-\/#!$%\^&\*;:{}=\-_`~()]/g,"" )
			$( "#details .lex .word" ).text( "[" + word + "]" );
		});		
	}
	function loadHebrewLex(number, word){
		$.getJSON( "/kjv/hebrew_lex/" + number, function(data) {
			console.log(data);
			$( "#details .lex" ).empty();
			$( "#greek_lex_template" ).tmpl(data).appendTo( "#details .lex" );
			word = word.replace(/[\.,-\/#!$%\^&\*;:{}=\-_`~()]/g,"" )
			$( "#details .lex .word" ).text( "[" + word + "]" );
		});		
	}
	
	function getBook(book){
	var books = [];
		books[1] = 'Genesis';
		books[2] = 'Exodus';
		books[3] = 'Leviticus';
		books[4] = 'Numbers';
		books[5] = 'Deuteronomy';
		books[6] = 'Joshua';
		books[7] = 'Judges';
		books[8] = 'Ruth';
		books[9] = '1 Samuel';
		books[10] = '2 Samuel';
		books[11] = '1 Kings';
		books[12] = '2 Kings';
		books[13] = '1 Chronicles';
		books[14] = '2 Chronicles';
		books[15] = 'Ezra';
		books[16] = 'Nehemiah';
		books[17] = 'Esther';
		books[18] = 'Job';
		books[19] = 'Psalms';
		books[20] = 'Proverbs';
		books[21] = 'Ecclesiastes';
		books[22] = 'Song of Solomon';
		books[23] = 'Isaiah';
		books[24] = 'Jeremiah';
		books[25] = 'Lamentations';
		books[26] = 'Ezekiel';
		books[27] = 'Daniel';
		books[28] = 'Hosea';
		books[29] = 'Joel';
		books[30] = 'Amos';
		books[31] = 'Obadiah';
		books[32] = 'Jonah';
		books[33] = 'Micah';
		books[34] = 'Nahum';
		books[35] = 'Habakkuk';
		books[36] = 'Zephaniah';
		books[37] = 'Haggai';
		books[38] = 'Zechariah';
		books[39] = 'Malachi';
		books[40] = 'Matthew';
		books[41] = 'Mark';
		books[42] = 'Luke';
		books[43] = 'John';
		books[44] = 'Acts';
		books[45] = 'Romans';
		books[46] = '1 Corinthians';
		books[47] = '2 Corinthians';
		books[48] = 'Galatians';
		books[49] = 'Ephesians';
		books[50] = 'Philippians';
		books[51] = 'Colossians';
		books[52] = '1 Thessalonians';
		books[53] = '2 Thessalonians';
		books[54] = '1 Timothy';
		books[55] = '2 Timothy';
		books[56] = 'Titus';
		books[57] = 'Philemon';
		books[58] = 'Hebrews';
		books[59] = 'James';
		books[60] = '1 Peter';
		books[61] = '2 Peter';
		books[62] = '1 John';
		books[63] = '2 John';
		books[64] = '3 John';
		books[65] = 'Jude';
		books[66] = 'Revelation';
		
		if(isNumber(book)){
			return books[book];
		} else {
			return books.indexOf(book);
		}
	}
	
	function getBcvBook(book){
	var books = [];
		books[1] = 'Gen';
		books[2] = 'Exod';
		books[3] = 'Lev';
		books[4] = 'Num';
		books[5] = 'Deut';
		books[6] = 'Josh';
		books[7] = 'Judg';
		books[8] = 'Ruth';
		books[9] = '1Sam';
		books[10] = '2Sam';
		books[11] = '1Kgs';
		books[12] = '2Kgs';
		books[13] = '1Chr';
		books[14] = '2Chr';
		books[15] = 'Ezra';
		books[16] = 'Neh';
		books[17] = 'Esth';
		books[18] = 'Job';
		books[19] = 'Ps';
		books[20] = 'Prov';
		books[21] = 'Eccl';
		books[22] = 'Song';
		books[23] = 'Isa';
		books[24] = 'Jer';
		books[25] = 'Lam';
		books[26] = 'Ezek';
		books[27] = 'Dan';
		books[28] = 'Hos';
		books[29] = 'Joel';
		books[30] = 'Amos';
		books[31] = 'Obad';
		books[32] = 'Jonah';
		books[33] = 'Mic';
		books[34] = 'Nah';
		books[35] = 'Hab';
		books[36] = 'Zeph';
		books[37] = 'Hag';
		books[38] = 'Zech';
		books[39] = 'Mal';
		books[40] = 'Matt';
		books[41] = 'Mark';
		books[42] = 'Luke';
		books[43] = 'John';
		books[44] = 'Acts';
		books[45] = 'Rom';
		books[46] = '1Cor';
		books[47] = '2Cor';
		books[48] = 'Gal';
		books[49] = 'Eph';
		books[50] = 'Phil';
		books[51] = 'Col';
		books[52] = '1Thess';
		books[53] = '2Thess';
		books[54] = '1Tim';
		books[55] = '2Tim';
		books[56] = 'Titus';
		books[57] = 'Phlm';
		books[58] = 'Heb';
		books[59] = 'Jas';
		books[60] = '1Pet';
		books[61] = '2Pet';
		books[62] = '1John';
		books[63] = '2John';
		books[64] = '3John';
		books[65] = 'Jude';
		books[66] = 'Rev';
		
		if( isNumber( book ) ) {
			return books[book];
		} else {
			return books.indexOf( book );
		}
	}
	
	function isNumber( n ) {
	  return !isNaN( parseFloat( n ) ) && isFinite( n );
	}
	
	function formatRef( book, chapter, verse ) {
		return pad( book, 2 ) + pad( chapter, 3 ) + pad( verse, 3 );
	}
	
	function pad( n, width, z ) {
		z = z || '0';
		n = n + '';
		return n.length >= width ? n : new Array( width - n.length + 1 ).join( z ) + n;
	}

});