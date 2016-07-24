<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Studylight extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library( "domparser" );
	}

	function commentary()
	{
		$books = $this->_getCommentary( "bnb" );
		foreach( $books as $book ) {
			if( $book != "" ) {
				$chapters = $this->_getCommentaryChapters( $book );
				//$chapters = [ 5 ];
				foreach( $chapters as $chapter ) {
					$html = $this->domparser->file_get_html( "https://www.studylight.org/commentaries/bnb/view.cgi?bk=$book&ch=$chapter" );
					$sections = $html->find( "div[style='margin-top:25px;']" );
					foreach( $sections as $section ) {
						//echo $section;die;
						$heading = $section->find( ".large", 0 );
						if( $heading ) {
							//echo $heading;die;
							if( strpos( $heading, "Verses" ) ) {
								$range = substr( $heading, strpos( $heading, "Verses " ) + 7 );
								$fix = 1;
								if( count( $section->find( ".emphasis" ) ) == 1 ) {
									$fix = 2; //Maybe fix
								}
								$data = [
									"verse" => explode( "-", $range )[0],
									"end_verse" => explode( "-", $range )[1],
									"content" => trim( $section->innertext ),
									"book" => $book + 1,
									"chapter" => $chapter,
									"fix" => $fix,
								];
								$this->db->insert( "barnes", $data );
							} else {
								$data = [
									"verse" => substr( $heading, strpos( $heading, "Verse " ) + 6 ),
									"end_verse" => 0,
									"content" => trim( $section->innertext ),
									"book" => $book + 1,
									"chapter" => $chapter,
								];
								$this->db->insert( "barnes", $data );
							}
							
						}
						//echo $section;
						
					}
					
				}
				//die;
			}
		}
	}
	
	function fix() {
		echo "<style>.large { font-size: 24px; color: blue; } .emphasis { color: blue; font-weight: bold; }</style>";
		$query = "SELECT * FROM barnes WHERE fix = 1";
		$results = $this->db->query( $query )->result_array();
		foreach( $results as $item ) {
			$html = $this->domparser->str_get_html( $item["content"] );
			$id = $item["id"];
			$verse = $item["verse"];
			$end_verse = $item["end_verse"];
			echo "<section id='$id' data-start='$verse' data-end='$end_verse'>$html</section>";
			/*$bolds = $html->find( ".emphasis" );
			$verse_headers = 0;
			foreach( $bolds as $bold ) {
				//echo $bold->plaintext;
				if( strpos( $bold->plaintext, ":" ) ) {
					//die("test");
					$verse_headers++;
				}
			}
			echo $verse_headers . "<br/>";*/
		}
	}
	
	function fix_local() {
		
		$html = $this->domparser->file_get_html( "http://bibletools.dev/test2.html" );
		foreach( $html->find( "section" ) as $section ) {
			$id = $section->getAttribute( "id" );
			$start = $section->getAttribute( "data-start" );
			$end = $section->getAttribute( "data-end" );
			$overview = $section->find( ".overview", 0 );
			
			$db_section = $this->db->query( "SELECT * FROM barnes WHERE id = $id" )->row_array();
			
			$data = [
				"verse" => $start,
				"end_verse" => $end,
				"content" => trim( $overview->innertext ),
				"book" => $db_section["book"],
				"chapter" => $db_section["chapter"],
				"range_overview" => 1,
			];
			$this->db->insert( "barnes_copy", $data );
			
			
			//print_r($db_section);die;
			foreach( $section->find( ".verse" ) as $verse ) {
				$reference = $verse->getAttribute( "data-ref" );
				if( strpos( $reference, "-" ) ) {
					$verse_range = explode( "-", $reference );
					$verse_start = $verse_range[0];
					$verse_end = $verse_range[1];
				} else {
					$verse_start = $reference;
					$verse_end = 0;
				}
				
				$data = [
					"verse" => $verse_start,
					"end_verse" => $verse_end,
					"content" => trim( $verse->innertext ),
					"book" => $db_section["book"],
					"chapter" => $db_section["chapter"],
				];
				$this->db->insert( "barnes_copy", $data );
			}
			//die;
		}
	}
	
	private function _getCommentary( $commentary )
	{
		$html = $this->domparser->file_get_html( "http://www.studylight.org/commentaries/$commentary/" );
		$ot_table = $html->find( "table", 1 );
		$nt_table = $html->find( "table", 2 );
		$ot_links = $ot_table->find( "a" );
		//echo $ot_links[0];die;
		$nt_links = $nt_table->find( "a" );
		
		/*$ot = array_map( function( $link ) {
			return substr( $link->href, strpos( $link->href, "bk=" ) + 3 );
		}, $ot_links );*/
		
		$nt = array_map( function( $link ) {
			return substr( $link->href, strpos( $link->href, "bk=" ) + 3 );
		}, $nt_links );
		
		return $nt;
	}
	
	private function _getCommentaryChapters( $book )
	{
		$html = $this->domparser->file_get_html( "https://www.studylight.org/commentaries/bnb/view.cgi?bk=$book" );
		$table = $html->find( "table", 1 );
		$links = $table->find( "a" );
		
		$array = array_map( function( $link ) {
			return substr( $link->href, strpos( $link->href, "ch=" ) + 3 ); 
		}, $links );
		
		//REMOVE LATER TO GET INTRODUCTIONS
		return array_filter( $array );
	}
}
/*$range = substr( $heading, strpos( $heading, "Verses " ) + 7 );
								$content = "";
								$start = explode( "-", $range )[0];
								$end = explode( "-", $range )[1];
								$range_overview = true;
								
								foreach( $section->find( "p" ) as $p ) {
									
									if( $p->find( ".emphasis", 0 ) && $range_overview == true ) {
										//if( $end == 13 ) { echo $p->find( ".emphasis", 0 ); die; }
										$data = [
											"start" => $start,
											"end" => $end,
											"content" => $content,
											"book" => $book + 1,
											"chapter" => $chapter,
											"range_overview" => $range_overview,
										];
										
										$end = 0;
										$reference1 = $p->find( ".emphasis", 0 )->find( ".scriptRef", 0 );
										if( count( $p->find( ".emphasis", 0 )->find( ".scriptRef" ) ) > 1 ){
											$reference2 = $p->find( ".emphasis", 0 )->find( ".scriptRef", 1 );
											$end = explode( ":", $reference2 )[1];
										}
										
										$this->db->insert( "barnes", $data );
										die($p->find( ".emphasis", 0 ));
										$range = "";
										$content = "";
										$start = explode( ":", $reference1 )[1];
										$range_overview = false;
										
									} elseif( $p->find( ".emphasis", 0 ) && $p->find( ".emphasis", 0 )->find( ".scriptRef" ) && count( $p->find( ".emphasis", 0 )->find( ".scriptRef" ) ) > 1 ) {
										$reference1 = $p->find( ".emphasis", 0 )->find( ".scriptRef", 0 );
										$reference2 = $p->find( ".emphasis", 0 )->find( ".scriptRef", 1 );
										//echo $reference2; die;
										$data = [
											"start" => $start,
											"end" => $end,
											"content" => $content,
											"book" => $book + 1,
											"chapter" => $chapter,
										];
										
										$this->db->insert( "barnes", $data );
										$range = "";
										$content = "";
										$start = explode( ":", $reference1 )[1];
										$end = explode( ":", $reference2 )[1];
									} elseif( $p->find( ".emphasis", 0 ) && $p->find( ".emphasis", 0 )->find( ".scriptRef" ) && count( $p->find( ".emphasis", 0 )->find( ".scriptRef" ) ) == 1 ) {
										$reference = $p->find( ".emphasis", 0 )->find( ".scriptRef", 0 );
										$data = [
											"start" => $start,
											"end" => $end,
											"content" => $content,
											"book" => $book + 1,
											"chapter" => $chapter,
										];
										
										$this->db->insert( "barnes", $data );
										$range = "";
										$content = "";
										if( strpos( $reference, "-" ) ) {
											$verse_range = explode( ":", $reference )[1];
											$start = explode( "-", $verse_range )[0];
											$end = explode( "-", $verse_range )[1];
										} else {
											$start = explode( ":", $reference )[1];
											$end = 0;
										}
										
									} else {
										
										$content .= $p;
									}
									
								}
								
								$data = [
									"start" => $start,
									"end" => $end,
									"content" => $content,
									"book" => $book + 1,
									"chapter" => $chapter,
								];
								$this->db->insert( "barnes", $data );*/