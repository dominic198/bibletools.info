$(document).ready(function(){
	
	if( $( "#search" ).val() > 0 ) {
		$( "#clear" ).show();
	}
	
	$( ".navbar-toggler, .open-menu" ).click( function() {
		openMenu();
	});
	
	$( document ).on( "click", ".overlay.menu", function(e) {
		closeMenu();
	});
	
	$( document ).on( "click", ".resource", function(e) {
		$(this).toggleClass( "expand" );
	});
	
	$( document ).on( "click", ".expand.resource .panel-body", function(e) {
		return false;
	});
	
	function openVerse( raw_ref ) {
		var ref = parseVerse( raw_ref );
		window.location.href = "/" + ref[0] + "_" + ref[1] + "." + ref[2];
	}
	
	$( "#search" ).on( "keyup change", function() {
		value = $(this).val();
		if( value.length > 1 && getBook( value.trim() ) == -1 ) {
			$( ".search-results, #clear" ).show();
			ref = parseVerse( value );
			book_name = getBook( getBcvBook( ref[0] ) );
			$( ".book-suggestion, .ref-suggestion" ).remove();
			last_character = value.trim().substr(-1);
			if( isNumber( last_character ) || last_character == ":" ) {
				$( ".search-results .verse-heading" ).after( "<li class='ref-suggestion'>Go to <b>" + book_name + " " + ref[1] + ":" + ref[2] + "</b></li>" );
			} else {
				$( ".search-results .verse-heading" ).after( "<li class='book-suggestion'>" + book_name + "</li>" );
			}
			if( $( ".search-results .selected" ).length < 1 ) {
				$( ".search-results li:not(.heading)" ).first().addClass( "selected" );
			}
		} else {
			$( ".search-results, #clear" ).hide();
		}
	});
	
	$( "#search" ).focus( function() {
		if( $(this).val() != "" ) {
			$( ".search-results" ).show();
		}
	});
	
	$( "#search" ).blur( function() {
		$( ".search-results" ).delay( 100 ).hide(0);
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
			if( $selected.hasClass( "book-suggestion" ) ) {
				$( "#search" ).val( $selected.text() + " " );
			} else if( $selected.hasClass( "ref-suggestion" ) ) {
				openVerse( $(this).val() );
			}
		}
	});
	
	$( document ).on( "mousedown", ".ref-suggestion", function() {
		openVerse( $(this).text() );
	});
	
	$( document ).on( "click", ".book-suggestion", function() {
		$( "#search" ).val( $selected.text() + " " );
		
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
	
	function loadVerse( ref ){
		$( ".verse .panel-body" ).html( '<span class="loading-animation"><b>•</b><b>•</b><b>•</b></span>' );
		window.history.pushState( ref, null, ref );
		$.getJSON( "/resources/json/" + ref, function( data ) {
			$( ".verse .panel-body" ).html( data.verse );
			$( ".next-verse" ).attr( "href", "/" + data.nav.next );
			$( ".prev-verse" ).attr( "href", "/" + data.nav.prev );
			$( ".prev-verse" ).toggleClass( "hidden", data.nav.prev == false );
			$( ".next-verse" ).toggleClass( "hidden", data.nav.next == false );
			$( "h2 .text-ref" ).text( data.text_ref );
			$( "#resource_list .resource" ).remove();
			$.each( data.resources, function( index, resource ) {
				$( "#resource_list .left-column" ).append( '<div class="panel panel-modern resource"><div class="panel-heading"><img src="/assets/img/authors/' + resource.logo + '.png"><div class="resource-info"><strong>' + resource.author + '</strong><br><small>' + resource.source + '</small></div></div><div class="panel-body">' + resource.content + '</div></div>' );
			});
		});
	}
	
	$( ".next-verse, .prev-verse" ).click( function(e) {
		e.preventDefault();
		ref = $(this).attr( "href" ).substring(1);
		loadVerse( ref );
	});
	
	//Global functions
	
	function parseVerse( ref ) {
		ref = decodeURIComponent( ref );
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
	
	function getPrettyRef( ref ) {
		return getBook( getBcvBook( ref[0] ) ) + " " + ref[1] + ":" + ref[2];
	}
	
	function isNumber( n ) {
	  return !isNaN( parseFloat( n ) ) && isFinite( n );
	}
	
	function getBcvBook( book ) {
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
	
	function getBook( book ) {
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
	
	function closeMenu(){
		//$( "ul#history_list" ).removeClass( "open" );
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

});