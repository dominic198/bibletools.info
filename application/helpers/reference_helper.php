<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists( "construct_reference" ) )
{
    function constructReference( $book, $chapter, $verse )
    {
		return str_pad( $book, 2, "0", STR_PAD_LEFT )
			. str_pad( $chapter, 3, "0", STR_PAD_LEFT )
			. str_pad( $verse, 3, "0", STR_PAD_LEFT );
    }
    
    function parseTextToShort( $ref )
    {
    	//Modified from:
    	//https://stackoverflow.com/questions/23719791/php-parsing-explode-bible-search-string-into-variables-or-tokens
    	if( $ref == "" ) return false;
		$ref = str_replace( "_", " ", $ref );
		$ref = str_replace( ".", ":", $ref );
		$parts = preg_split('/\s*:\s*/', trim( $ref, " ;") );
		$components = [ "book" => "", "chapter" => "", "verse" => "" ];
		if( isset( $parts[0] ) ) {
			if( preg_match( '/\d+\s*$/', $parts[0], $out ) ) {
				$components['chapter'] = rtrim( $out[0] );
			} else {
				$components['chapter'] = 1;
			}
			$book = trim( preg_replace( '/\d+\s*$/', "", $parts[0] ) );
			$book = strtolower( str_replace( " ", "", $book ) );
			$books = getBookOptions();
			if( array_key_exists( $book, $books ) ) {
				$components['book'] = $books[$book];
			} else {
				return false;
			}
		}
		
		if( isset( $parts[1] ) ) {
			preg_match( "~^(\d+)~", $parts[1], $output );
			$components['verse'] = $output[0];
		} else {
			$components['verse'] = 1;
		}
		$abbreviations = array_flip( getBookAbbreviationNumbers() );
		return $abbreviations[$components["book"]] . "_" . $components["chapter"] . "." . $components["verse"];
    }
    
    function parseReference( $ref )
    {
    	return [
    		"book" => getBook( + substr( $ref, 0, 2 ) ),
    		"chapter" => + substr( $ref, 2, 3 ),
    		"verse" => + substr( $ref, 5, 3 ),
    	];
    }
    
     function parseReferenceToText( $start, $end = null )
    {
    	if( ! is_numeric( $start ) ) return false;
    	$start = [
    		"book" => getBook( + substr( $start, 0, 2 ) ),
    		"chapter" => + substr( $start, 2, 3 ),
    		"verse" => + substr( $start, 5, 3 ),
    	];
    	
    	if( is_numeric( $end ) ) {
	    	$end = [
	    		"book" => getBook( + substr( $end, 0, 2 ) ),
	    		"chapter" => + substr( $end, 2, 3 ),
	    		"verse" => + substr( $end, 5, 3 ),
	    	];
	    } else {
	    	$end = null;
	    }
    	
    	$ref = $start["book"] . " " . $start["chapter"];
    	if( $start["verse"] ) $ref .= ":" . $start["verse"];
    	if( $end && $end["chapter"] && $end["chapter"] != $start["chapter"] ) {
    		$ref .= "-" . $end["chapter"];
    		if( $end && $end["verse"] ) $ref .= ":" . $end["verse"];
    	} else {
    		if( $end && $end["verse"] ) $ref .= "-" . $end["verse"];
    	}
    	
    	return $ref;
    }
    
    function getBook( $book_number )
    {
    	$books = getBooks();
    	if( ! array_key_exists( $book_number, $books ) ) return false;
		return $books[$book_number];
    }
    
    function textToNumber( $ref )
    {
    	$array = explode( ".", $ref );
    	$books = getBookAbbreviationNumbers();
    	$book_number = $books[$array[0]];
    	return constructReference( $book_number, $array[1], $array[2] );
    }
    
	function shortTextToNumber( $ref )
    {
    	if( ! strpos( $ref, "_" ) || ! strpos( $ref, "." ) ) return false;
    	$pieces = explode( "_", $ref );
    	$book = strtolower( $pieces[0] );
    	$chapter = explode( ".", $pieces[1] )[0];
    	$verse = explode( ".", $pieces[1] )[1];
    	$book_abbreviations = getBookOptions();
    	if( ! array_key_exists( $book, $book_abbreviations ) ) return false;
    	return constructReference( $book_abbreviations[$book], $chapter, $verse );
    }
    
    function bookNumberToAbbreviation( $number )
    {
    	return $number ? array_flip( getBookAbbreviationNumbers() )[$number] : false;
    }
    
    function getBooks()
    {
    	return [
			1 => "Genesis",
			2 => "Exodus",
			3 => "Leviticus",
			4 => "Numbers",
			5 => "Deuteronomy",
			6 => "Joshua",
			7 => "Judges",
			8 => "Ruth",
			9 => "1 Samuel",
			10 => "2 Samuel",
			11 => "1 Kings",
			12 => "2 Kings",
			13 => "1 Chronicles",
			14 => "2 Chronicles",
			15 => "Ezra",
			16 => "Nehemiah",
			17 => "Esther",
			18 => "Job",
			19 => "Psalms",
			20 => "Proverbs",
			21 => "Ecclesiastes",
			22 => "Song of Solomon",
			23 => "Isaiah",
			24 => "Jeremiah",
			25 => "Lamentations",
			26 => "Ezekiel",
			27 => "Daniel",
			28 => "Hosea",
			29 => "Joel",
			30 => "Amos",
			31 => "Obadiah",
			32 => "Jonah",
			33 => "Micah",
			34 => "Nahum",
			35 => "Habakkuk",
			36 => "Zephaniah",
			37 => "Haggai",
			38 => "Zechariah",
			39 => "Malachi",
			40 => "Matthew",
			41 => "Mark",
			42 => "Luke",
			43 => "John",
			44 => "Acts",
			45 => "Romans",
			46 => "1 Corinthians",
			47 => "2 Corinthians",
			48 => "Galatians",
			49 => "Ephesians",
			50 => "Philippians",
			51 => "Colossians",
			52 => "1 Thessalonians",
			53 => "2 Thessalonians",
			54 => "1 Timothy",
			55 => "2 Timothy",
			56 => "Titus",
			57 => "Philemon",
			58 => "Hebrews",
			59 => "James",
			60 => "1 Peter",
			61 => "2 Peter",
			62 => "1 John",
			63 => "2 John",
			64 => "3 John",
			65 => "Jude",
			66 => "Revelation",
		];
    }
    
    //Not used?
    function getBookAbbreviationNumbers()
    {
    	return [
    		"Gen" => 1,
			"Exod" => 2,
			"Lev" => 3,
			"Num" => 4,
			"Deut" => 5,
			"Josh" => 6,
			"Judg" => 7,
			"Ruth" => 8,
			"1Sam" => 9,
			"2Sam" => 10,
			"1Kgs" => 11,
			"2Kgs" => 12,
			"1Chr" => 13,
			"2Chr" => 14,
			"Ezra" => 15,
			"Neh" => 16,
			"Esth" => 17,
			"Job" => 18,
			"Ps" => 19,
			"Prov" => 20,
			"Eccl" => 21,
			"Song" => 22,
			"Isa" => 23,
			"Jer" => 24,
			"Lam" => 25,
			"Ezek" => 26,
			"Dan" => 27,
			"Hos" => 28,
			"Joel" => 29,
			"Amos" => 30,
			"Obad" => 31,
			"Jonah" => 32,
			"Mic" => 33,
			"Nah" => 34,
			"Hab" => 35,
			"Zeph" => 36,
			"Hag" => 37,
			"Zech" => 38,
			"Mal" => 39,
			"Matt" => 40,
			"Mark" => 41,
			"Luke" => 42,
			"John" => 43,
			"Acts" => 44,
			"Rom" => 45,
			"1Cor" => 46,
			"2Cor" => 47,
			"Gal" => 48,
			"Eph" => 49,
			"Phil" => 50,
			"Col" => 51,
			"1Thess" => 52,
			"2Thess" => 53,
			"1Tim" => 54,
			"2Tim" => 55,
			"Titus" => 56,
			"Phlm" => 57,
			"Heb" => 58,
			"Jas" => 59,
			"1Pet" => 60,
			"2Pet" => 61,
			"1John" => 62,
			"2John" => 63,
			"3John" => 64,
			"Jude" => 65,
			"Rev" => 66,
    	];
    }
    
    function getBooksNoSpaces()
    {
    	return [
			1 => "Genesis",
			2 => "Exodus",
			3 => "Leviticus",
			4 => "Numbers",
			5 => "Deuteronomy",
			6 => "Joshua",
			7 => "Judges",
			8 => "Ruth",
			9 => "1Samuel",
			10 => "2Samuel",
			11 => "1Kings",
			12 => "2Kings",
			13 => "1Chronicles",
			14 => "2Chronicles",
			15 => "Ezra",
			16 => "Nehemiah",
			17 => "Esther",
			18 => "Job",
			19 => "Psalms",
			20 => "Proverbs",
			21 => "Ecclesiastes",
			22 => "SongofSolomon",
			23 => "Isaiah",
			24 => "Jeremiah",
			25 => "Lamentations",
			26 => "Ezekiel",
			27 => "Daniel",
			28 => "Hosea",
			29 => "Joel",
			30 => "Amos",
			31 => "Obadiah",
			32 => "Jonah",
			33 => "Micah",
			34 => "Nahum",
			35 => "Habakkuk",
			36 => "Zephaniah",
			37 => "Haggai",
			38 => "Zechariah",
			39 => "Malachi",
			40 => "Matthew",
			41 => "Mark",
			42 => "Luke",
			43 => "John",
			44 => "Acts",
			45 => "Romans",
			46 => "1Corinthians",
			47 => "2Corinthians",
			48 => "Galatians",
			49 => "Ephesians",
			50 => "Philippians",
			51 => "Colossians",
			52 => "1Thessalonians",
			53 => "2Thessalonians",
			54 => "1Timothy",
			55 => "2Timothy",
			56 => "Titus",
			57 => "Philemon",
			58 => "Hebrews",
			59 => "James",
			60 => "1Peter",
			61 => "2Peter",
			62 => "1John",
			63 => "2John",
			64 => "3John",
			65 => "Jude",
			66 => "Revelation",
		];
    }
    
    function getBookOptions()
    {
    	return [
    		"genesis" => 1,
			"gen" => 1,
			"gn" => 1,
			"ge" => 1,
			"exodus" => 2,
			"exo" => 2,
			"ex" => 2,
			"exod" => 2,
			"leviticus" => 3,
			"lev" => 3,
			"le" => 3,
			"lv" => 3,
			"numbers" => 4,
			"num" => 4,
			"nm" => 4,
			"nu" => 4,
			"deuteronomy" => 5,
			"deu" => 5,
			"dt" => 5,
			"de" => 5,
			"deut" => 5,
			"joshua" => 6,
			"jos" => 6,
			"josh" => 6,
			"judges" => 7,
			"jdg" => 7,
			"judg" => 7,
			"ruth" => 8,
			"rth" => 8,
			"rt" => 8,
			"rut" => 8,
			"ru" => 8,
			"1samuel" => 9,
			"1sam" => 9,
			"1sa" => 9,
			"1s" => 9,
			"1sm" => 0,
			"1sa" => 9,
			"2samuel" => 10,
			"2sam" => 10,
			"2sa" => 10,
			"2s" => 10,
			"2sm" => 10,
			"2sa" => 10,
			"1kings" => 11,
			"1ki" => 11,
			"1kgs" => 11,
			"1ki" => 11,
			"2kings" => 12,
			"2ki" => 12,
			"2kgs" => 12,
			"2ki" => 12,
			"1chronicles" => 13,
			"1chr" => 13,
			"1ch" => 13,
			"1chron" => 13,
			"1ch" => 13,
			"2chronicles" => 14,
			"2chr" => 14,
			"2ch" => 14,
			"2chron" => 14,
			"2ch" => 14,
			"ezra" => 15,
			"ezr" => 15,
			"esr" => 15,
			"1ezr" => 15,
			"nehemiah" => 16,
			"neh" => 16,
			"ne" => 16,
			"2ezr" => 16,
			"esther" => 17,
			"est" => 17,
			"esth" => 17,
			"es" => 17,
			"job" => 18,
			"jb" => 18,
			"psalms" => 19,
			"psa" => 19,
			"ps" => 19,
			"psalm" => 19,
			"proverbs" => 20,
			"pro" => 20,
			"pr" => 20,
			"prov" => 20,
			"prv" => 20,
			"ecclesiastes" => 21,
			"ecc" => 21,
			"ec" => 21,
			"eccl" => 21,
			"songofsolomon" => 22,
			"sng" => 22,
			"sos" => 22,
			"so" => 22,
			"song" => 22,
			"isaiah" => 23,
			"isa" => 23,
			"is" => 23,
			"jeremiah" => 24,
			"je" => 24,
			"jer" => 24,
			"jr" => 24,
			"lamentations" => 25,
			"lam" => 25,
			"la" => 25,
			"lm" => 25,
			"ezekiel" => 26,
			"ezk" => 26,
			"ez" => 26,
			"ezek" => 26,
			"eze" => 26,
			"daniel" => 27,
			"dan" => 27,
			"da" => 27,
			"dn" => 27,
			"hosea" => 28,
			"hos" => 28,
			"ho" => 28,
			"joel" => 29,
			"jol" => 29,
			"jl" => 29,
			"joe" => 29,
			"amos" => 30,
			"amo" => 30,
			"am" => 30,
			"obadiah" => 31,
			"oba" => 31,
			"ob" => 31,
			"obad" => 31,
			"obd" => 31,
			"jonah" => 32,
			"jon" => 32,
			"jnh" => 32,
			"micah" => 33,
			"mic" => 33,
			"mi" => 33,
			"mch" => 33,
			"nahum" => 34,
			"nah" => 34,
			"na" => 34,
			"nam" => 34,
			"habakkuk" => 35,
			"hab" => 35,
			"ha" => 35,
			"zephaniah" => 36,
			"zeph" => 36,
			"zep" => 36,
			"haggai" => 37,
			"hag" => 37,
			"hagg" => 37,
			"zechariah" => 38,
			"zech" => 38,
			"zec" => 38,
			"zch" => 38,
			"malachi" => 39,
			"mal" => 39,
			"ml" => 39,
			"matthew" => 40,
			"mt" => 40,
			"mat" => 40,
			"matt" => 40,
			"mark" => 41,
			"mk" => 41,
			"mr" => 41,
			"mrk" => 41,
			"luke" => 42,
			"lk" => 42,
			"luk" => 42,
			"l" => 42,
			"lu" => 42,
			"john" => 43,
			"jn" => 43,
			"jhn" => 43,
			"j" => 43,
			"joh" => 43,
			"acts" => 44,
			"act" => 44,
			"ac" => 44,
			"romans" => 45,
			"rom" => 45,
			"ro" => 45,
			"r" => 45,
			"rm" => 45,
			"1corinthians" => 46,
			"1cor" => 46,
			"1co" => 46,
			"2corinthians" => 47,
			"2cor" => 47,
			"2co" => 47,
			"galatians" => 48,
			"gal" => 48,
			"ga" => 48,
			"g" => 48,
			"ephesians" => 49,
			"eph" => 49,
			"ep" => 49,
			"e" => 49,
			"philippians" => 50,
			"phil" => 50,
			"php" => 50,
			"ph" => 50,
			"phili" => 50,
			"colossians" => 51,
			"col" => 51,
			"1thessalonians" => 52,
			"1th" => 52,
			"1thess" => 52,
			"1thes" => 52,
			"1th" => 52,
			"2thessalonians" => 53,
			"2th" => 53,
			"2thess" => 53,
			"2thes" => 53,
			"2th" => 53,
			"1timothy" => 54,
			"1tim" => 54,
			"1ti" => 54,
			"1t" => 54,
			"1tm" => 54,
			"1ti" => 54,
			"2timothy" => 55,
			"2tim" => 55,
			"2ti" => 55,
			"2t" => 55,
			"2tm" => 55,
			"2ti" => 55,
			"titus" => 56,
			"tit" => 56,
			"tt" => 56,
			"philemon" => 57,
			"phm" => 57,
			"phlm" => 57,
			"philem" => 57,
			"phile" => 57,
			"hebrews" => 58,
			"heb" => 58,
			"hebr" => 58,
			"h" => 58,
			"hbr" => 58,
			"james" => 59,
			"jas" => 59,
			"jam" => 59,
			"ja" => 59,
			"1peter" => 60,
			"1pet" => 60,
			"1pt" => 60,
			"1p" => 60,
			"1pe" => 60,
			"2peter" => 61,
			"2pet" => 61,
			"2pt" => 61,
			"2p" => 61,
			"2pe" => 61,
			"1john" => 62,
			"1jn" => 62,
			"1jo" => 62,
			"1j" => 62,
			"1jo" => 62,
			"2john" => 63,
			"2jn" => 63,
			"2jo" => 63,
			"2j" => 63,
			"2jo" => 63,
			"3john" => 64,
			"3jn" => 64,
			"3jo" => 64,
			"3j" => 64,
			"3jo" => 64,
			"jude" => 65,
			"jud" => 65,
			"jd" => 65,
			"revelation" => 66,
			"rev" => 66,
			"re" => 66,
    	];
    }
}