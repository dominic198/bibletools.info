<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists( "construct_reference" ) )
{
    function constructReference( $book, $chapter, $verse )
    {
		return str_pad( $book, 2, "0", STR_PAD_LEFT )
			. str_pad( $chapter, 3, "0", STR_PAD_LEFT )
			. str_pad( $verse, 3, "0", STR_PAD_LEFT );
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
    	$pieces = explode( "_", $ref );
    	$book = $pieces[0];
    	$chapter = explode( ".", $pieces[1] )[0];
    	$verse = explode( ".", $pieces[1] )[1];
    	$book_abbreviations = getBookAbbreviationNumbers();
    	return constructReference( $book_abbreviations[$book], $chapter, $verse );
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
}