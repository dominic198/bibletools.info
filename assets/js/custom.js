$(document).ready(function(){
	
	if( $( ".text-ref" ).text().length > 0 ) {
		$( "#search" ).val( $( ".text-ref" ).text() );
		$( "#clear" ).show();
	}
	
	$( ".navbar-toggler, .open-menu" ).click( function() {
		openMenu();
	});
	
	$( ".map .panel-body a" ).magnificPopup( {
		type: "image"
	});
	
	$( document ).on( "click", ".overlay.menu", function(e) {
		closeMenu();
	});
	
	$( document ).on( "click", ".resource.expand .panel-heading", function(e) {
		$(this).removeClass( "expand" );
	});
	
	$( document ).on( "click", ".resource:not(.expand)", function(e) {
		$(this).addClass( "expand" );
	});
	
	function loadVerse( ref, raw = false ){
		$( ".verse .panel-body" ).html( '<span class="loading-animation"><b>•</b><b>•</b><b>•</b></span>' );
		$( "#search" ).blur();
		closeMenu();
		if( raw ) {
			url = "/resources/json/query/" + ref;
		} else {
			window.history.pushState( ref, null, ref );
			url = "/resources/json/" + ref;
		}
		$.getJSON( url, function( data ) {
			if( $( ".verse" ).length < 1 ) {
				window.location = "/" + data.short_ref;
			}
			if( raw ) {
				window.history.pushState( data.short_ref, null, data.short_ref );
			}
			$( "#search" ).val( data.text_ref );
			$( ".verse .panel-body" ).html( data.verse );
			$( ".next-verse" ).attr( "href", "/" + data.nav.next );
			$( ".prev-verse" ).attr( "href", "/" + data.nav.prev );
			$( ".prev-verse" ).toggleClass( "hidden", data.nav.prev == false );
			$( ".next-verse" ).toggleClass( "hidden", data.nav.next == false );
			$( "h2 .text-ref" ).text( data.text_ref );
			$( "#resource_list .resource" ).remove();
			$.each( data.main_resources, function( index, resource ) {
				$( "#resource_list .left-column" ).append( '<div class="panel panel-modern resource" data-index-id="' + resource.id + '"><div class="panel-heading"><div class="author-icon ' + resource.logo + '"></div><div class="resource-info"><strong>' + resource.author + '</strong><br><small>' + resource.source + '</small></div></div><div class="panel-body">' + resource.content + '</div><div class="panel-footer"><small>Was this helpful?</small><a class="mark-unhelpful"></a><a class="mark-helpful"></a></div></div>' );
			});
			$.each( data.sidebar_resources, function( index, resource ) {
				$( "#resource_list .right-column" ).append( '<div class="panel panel-modern resource ' + resource.class + '"><div class="panel-heading"><strong>' + resource.source + '</strong></div><div class="panel-body">' + resource.content + '</div></div>' );
			});
			$( ".map .panel-body a" ).magnificPopup( {
				type: "image"
			});
			$( ".history-list" ).prepend( '<li><a href="/' + ref + '" class="dropdown-item ref-link">' + data.text_ref + '</a></li>' );
			$( ".history-list" ).each(function( index ) {
				if( $(this).find( "li" ).length > 10 ) {
					$(this).find( "li" ).last().remove();
				}
			});
		});
	}
	
	function showSuggestions() {
		value = $( "#search" ).val();
		var books = [ "Genesis", "Exodus", "Leviticus", "Numbers", "Deuteronomy", "Joshua", "Judges", "Ruth", "1 Samuel", "2 Samuel", "1 Kings", "2 Kings", "1 Chronicles", "2 Chronicles", "Ezra", "Nehemiah", "Esther", "Job", "Psalms", "Proverbs", "Ecclesiastes", "Song of Solomon", "Isaiah", "Jeremiah", "Lamentations", "Ezekiel", "Daniel", "Hosea", "Joel", "Amos", "Obadiah", "Jonah", "Micah", "Nahum", "Habakkuk", "Zephaniah", "Haggai", "Zechariah", "Malachi", "Matthew", "Mark", "Luke", "John", "Acts", "Romans", "1 Corinthians", "2 Corinthians", "Galatians", "Ephesians", "Philippians", "Colossians", "1 Thessalonians", "2 Thessalonians", "1 Timothy", "2 Timothy", "Titus", "Philemon", "Hebrews", "James", "1 Peter", "2 Peter", "1 John", "2 John", "3 John", "Jude", "Revelation" ];
		var results = books.filter( function( item ){
			return item.toLowerCase().indexOf( value.toLowerCase() ) > -1;            
		});
		if( value.length > 1 && results.length > 0 && books.join( "." ).toLowerCase().split( "." ).indexOf( value.toLowerCase() ) == -1 ) {
			$( ".search-results, #clear" ).show();
			
			$( ".book-suggestion" ).remove();
			$.each( results, function( index, item ) {
				$( ".search-results .verse-heading" ).after( "<li class='book-suggestion'>" + item + "</li>" );
			});
			
			if( $( ".search-results .selected" ).length < 1 ) {
				$( ".search-results li:not(.heading)" ).first().addClass( "selected" );
			}
		} else {
			$( ".search-results li.selected" ).removeClass( "selected" );
			hideSuggestions()
		}
		$( "#clear" ).toggle( value.length > 0 );
	}
	
	function hideSuggestions( delay = false ) {
		if( delay ) {
			setTimeout(function(){
				$( ".search-results" ).delay( 100 ).hide(0);
				$( ".book-suggestion" ).remove();
			}, 100);
		} else {
			$( ".search-results" ).hide();
			$( ".book-suggestion" ).remove();
		}
		
	}
	
	$( "#search" ).on( "input", function(e) {
		showSuggestions();
	});
	
	$( "#search" ).focus( function() {
		if( $(this).val() != "" ) {
			showSuggestions();
		}
	});
	
	$( "#search" ).blur( function() {
		hideSuggestions( true );
	});
	
	$( "#search" ).keydown(function(e) {
		if( e.which == 38 ) { //up
			$element = $( ".search-results li.selected" ).prevAll( "li:not(.heading)" ).eq(0);
			if( $element.length > 0 ) {
				$( ".search-results li.selected" ).removeClass( "selected" );
				$element.addClass( "selected" )
			}
		} else if( e.which == 40 ) { //down
			$element = $( ".search-results li.selected" ).nextAll( "li:not(.heading)" ).eq(0);
			if( $element.length > 0 ) {
				$( ".search-results li.selected" ).removeClass( "selected" );
				$element.addClass( "selected" )
			}
		} else if( e.which == 13 ) { //enter
			e.preventDefault();
			$selected = $( ".search-results .selected" );
			if( $selected.length > 0 ) {
				$( "#search" ).val( $selected.text() + " " );
				hideSuggestions();
			} else {
				loadVerse( $(this).val(), true );
			}
		}
	});
	
	$( document ).on( "click", ".book-suggestion", function() {
		$( "#search" ).val( $(this).text() + " " ).focus();
		
	});
	
	$( ".toggle-history" ).click( function() {
		$( ".history-list" ).show();
	});
	
	$( "#clear" ).click( function(e) {
		$( "#search" ).val( "" ).focus();
		$(this).hide();
	});
	
	$( document ).mouseup( function (e) {
		var container = $( "#search_form" );
		
		if ( ! container.is( e.target )
			&& container.has( e.target ).length === 0 )
		{
			$( ".search-results" ).hide();
		}
	});
	
	$( document ).on( "click", ".ref-link", function(e) {
		e.preventDefault();
		ref = $(this).attr( "href" ).substring(1);
		loadVerse( ref );
		$( ".dropdown-menu" ).hide();
	});
	
	$( document ).on( "click", ".verse .panel-body a", function(e) {
		
		clearLexicon();
		
		$lexicon = $( "#lexicon" );
		$verse = $( ".verse" );
		$word = $(this);
		
		$word.addClass( "selected" );
		$( "body" ).addClass( "no_scroll" );
		if( ! $lexicon.hasClass( "visible" ) ) {
			$lexicon.addClass( "visible" );
			$( "body" ).addClass( "lexicon");
		}
		verse = $verse.attr( "data-short-ref" );
		word_id = $word.attr( "id" );
		
		$.getJSON( "/resources/json/" + verse + "/" + word_id, function( data ) {
			$( "#lexicon .definition" ).empty();
			$( '<h2>' + data.strongs.word + '<small>' + data.strongs.pronun.dic + '</small></h2><p class="short">' + data.strongs.data.def.short + '</p><div class="long">' + data.strongs.data.def.html + '</div><div class="resources"></div>' ).appendTo( "#lexicon .definition" );
			$.each( data.resources, function( index, resource ) {
				$( '<div class="row"><div class="col-sm-12 resource"><div class="panel panel-modern"><div class="panel-heading"><strong>' + resource.title + '</strong></div><div class="panel-body">' + resource.content + '</div></div></div></div>' ).appendTo( "#lexicon .resources" );
			});
			data.strongs.connected_words.forEach( function( word ) {
				$( ".verse #" + word.id ).addClass( "selected" );
			});
		});
		
	});
	
	$( "#lexicon .close" ).click(function() {
		clearLexicon();
	});
	
	function clearLexicon() {
		$( "#lexicon" ).removeClass( "visible" ).find( ".definition" ).text( "Loading..." ).find( ".resources" ).empty();
		$( "body" ).removeClass( "lexicon no_scroll" ).focus();
		$( ".verse a.selected" ).removeClass( "selected" );
	}
	
	$( document ).mouseup( function (e) {
		var container = $( "#lexicon" );
		
		if ( ! container.is( e.target )
			&& container.has( e.target ).length === 0
			&& container.hasClass( "visible" ) )
		{
			clearLexicon();
		}
	});
	
	$( document ).on( "click", ".expand ul.occurances li", function(e) {
		var ref = $(this).find( "strong" ).text();
		getVerse( ref );
		clearLexicon();
	});
	
	$( document ).on( "click", ".mark-helpful", function() {
		var index_id = $(this).parents( ".resource" ).attr( "data-index-id" );
		$.get( "/resources/helpful/" + index_id );
		$(this).parents( ".resource" ).find( ".panel-footer" ).html( "<small>Thanks! We may rank this resource higher next time.</small>" );
	});
	
	$( document ).on( "click", ".mark-unhelpful", function() {
		var index_id = $(this).parents( ".resource" ).attr( "data-index-id" );
		$.get( "/resources/unhelpful/" + index_id );
		$(this).parents( ".resource" ).find( ".panel-footer" ).html( "<small>Good to know, we may put this resource further down the list.</small>" );
	});
	
	//Global functions
	
	function closeMenu(){
		$( ".history-list" ).removeClass( "open" );
		$( ".overlay.menu" ).fadeOut( 160 ).remove();
		$( "#menu" ).removeClass( "show" );
	}
	
	function openMenu(){
		$( "body" ).append( "<div class='overlay menu'></div>" )
		$( ".overlay.menu" ).fadeIn( 160 );
		$( "#menu" ).addClass( "show" );
	}
	
	$( "#menu .history" ).click( function(){
		$( "#menu .history-list" ).toggleClass( "open" );
	});	
	
	//Dropdown menus
	$( ".nav-item" ).click(function(){
		$( "ul:first",this ).show();
	});
	$( document ).mouseup( function (e) {
		var container = $( ".dropdown-menu" );
		
		if ( ! container.is( e.target )
			&& container.has( e.target ).length === 0 )
		{
			container.hide();
		}
	});
});