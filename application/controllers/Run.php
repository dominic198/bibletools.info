<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Run extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library( "domparser" );
		$this->load->helper( "reference" );
	}

	function index()
	{
		die("Restricted");
	}
	
	function minify()
	{
		die("Restricted");
		$this->load->library( "minify" ); 
		$this->minify->css( array( "lib.css", "custom.css" ) ); 
		$this->minify->deploy_css( true );
		$this->minify->js( array( "lib.js", "custom.js"  ) ); 
		$this->minify->deploy_js( true );
	}
    
    function reorder_index()
    {   
		die("Restricted");
		$this->db->trans_start();
        // - 1. Commentaries
        $this->db->query( "UPDATE `index` LEFT JOIN resources ON `index`.resource_id = resources.id SET `index`.order_group = 1 WHERE resources.info_id < 7" );
        
        // - 2. EGW emphasis, specific verse
        $this->db->query( "UPDATE `index` LEFT JOIN resources ON `index`.resource_id = resources.id SET `index`.order_group = 2 WHERE resources.info_id > 7 AND emphasis = 1 AND end IS NULL" );
		
        // - 3. EGW verse (not emphasis)
        $this->db->query( "UPDATE `index` LEFT JOIN resources ON `index`.resource_id = resources.id SET `index`.order_group = 3 WHERE resources.info_id > 7 AND emphasis != 1 AND end IS NULL" );
        
        // - 4. EGW emphasis, verse range
        $this->db->query( "UPDATE `index` LEFT JOIN resources ON `index`.resource_id = resources.id SET `index`.order_group = 4 WHERE resources.info_id > 7 AND emphasis = 1 AND end IS NOT NULL" );
        
        // - 5. EGW verse range (not emphasis)
        $this->db->query( "UPDATE `index` LEFT JOIN resources ON `index`.resource_id = resources.id SET `index`.order_group = 5 WHERE resources.info_id > 7 AND emphasis != 1 AND end IS NOT NULL" );
        
        // - 6. EGW chapter (create index entries?)
        $this->db->query( "UPDATE `index` LEFT JOIN resources ON `index`.resource_id = resources.id SET `index`.order_group = 6 WHERE resources.start LIKE '%000' AND resources.info_id > 7" );
        $this->db->trans_complete();
    }
	
	function restructure()
	{
		die("Restricted");
		$sql = 'SELECT id, verse, end_verse, book, chapter FROM barnes';
		$query = $this->db->query($sql);
		$references = $query->result_array();
		foreach($references as $item){
			$book = str_pad($item['book'], 2, "0", STR_PAD_LEFT);
			$chapter = str_pad($item['chapter'], 3, "0", STR_PAD_LEFT);
			$start_verse = str_pad($item['verse'], 3, "0", STR_PAD_LEFT);
			$endverse = $item['end_verse'];
			if( $endverse == 0 ) {
				$endverse = $item['verse'];
			}
			$end_verse = str_pad($endverse, 3, "0", STR_PAD_LEFT);
			
			$data['start'] = $book.$chapter.$start_verse;
			$data['end'] = $book.$chapter.$end_verse;
			
			$this->db->where('id', $item['id']);
			$this->db->update('barnes', $data);
			//die;
		}
	}
	
	function remove_commas()
	{
		die("Restricted");
		$sql = "SELECT * FROM egw_scripture_reference WHERE verse LIKE '%,%'";
		$query = $this->db->query( $sql );
		$references = $query->result_array();
		foreach( $references as $item ){
			$verse = str_replace( " ", "", $item['verse'] );
			$verse = explode( ",", $verse );
			
			$data['verse'] = $verse[0];
			$data['endverse'] = $verse[1];
						
			$this->db->where('id', $item['id']);
			$this->db->update('egw_scripture_reference', $data);
		}
	}
	
	function tsk()
	{		
		die("Restricted");
		$query = $this->db->get( "kjv_verses", 32000, 16883 );
		
		foreach ( $query->result() as $verse ) {
			
			$ref = $verse->ref;
			$context = stream_context_create( [
				"http" => [
					"header"  => "Content-type: application/x-www-form-urlencoded\r\n",
					"method"  => "POST",
					"content" => http_build_query( [ "key" => "value1" ] ),
				],
			] );
			
			$result = file_get_contents( "http://tsk-online.com//Data/GetTskReferences/$ref", false, $context );
			if ( $result === FALSE ) { die( "error" ); }
			
			$array = json_decode( $result );
			$html = $this->domparser->str_get_html( $array->TskReferences );
			$html = str_replace( "subhead2", "head", $html );
			$html = str_replace( 'a href="#"', "a", $html );
			$data = [
				"start" => $ref,
				"content" => trim( $html ),
			];
			$this->db->insert( "tsk", $data );
			unset( $data );
		}
	}
	
	function get_full_url( $url )
	{
		die("Restricted");
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, false);
		$header = curl_exec( $ch );
		
		$fields = explode( "\r\n", preg_replace( '/\x0D\x0A[\x09\x20]+/', ' ', $header ) );
		
		for( $i=0; $i<count( $fields ); $i++ )
		{
			if( strpos( $fields[$i], "Location" ) !== false )
			{
				$url = str_replace( "Location: ", "", $fields[$i] );
			}
		}
		return $url;
	}
	
	function get_page_chunk( $url )
	{
		die("Restricted");
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, "type=chunk" );
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"x-requested-with: XMLHttpRequest",
		));
		return curl_exec( $ch );
	}
	
	function merge_resources()
	{
		die("Restricted");
		$sql = 'SELECT * FROM sdabc';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		foreach( $items as $item ) {
			$data = [
				"start" => $item["start"],
				"content" => $item["content"],
				"info_id" => 6,
			];
			//$this->db->insert( "resources", $data );
			unset( $data );
		}
	}
	
	function pull_scripture_reference()
	{
		die("Restricted");
		$book_code = "2778.27587";
		$book_names = array_flip( getBooksNoSpaces() );
		$toc = $this->domparser->file_get_html( "https://m.egwwritings.org/en/book/$book_code/toc/part" );
		foreach( $toc->find( "a" ) as $chapter ) {
			$html = $this->domparser->file_get_html( "https://m.egwwritings.org/" . $chapter->getAttribute( "href" ) );
			$content = $html->find( ".egw_content_wrapper" );
			$book = null;
			$chapter = null;
			$verses = null;
			$verse_parts = [];
			foreach( $html->find( ".egw_content_wrapper" ) as $item ) {
				if( $item->tag == "h3" ) {
					$verse_parts = [ 0 ];
					$refcode = str_replace( "ScriptIndex ", "", $item->getAttribute( "data-refcode" ) );
					$refcode_parts = explode( ".", $refcode );
					$book = $refcode_parts[0];
					$book_number = $book_names[$book];
					$chapter = $refcode_parts[1];
				} elseif( $item->tag == "h4" ) {
					$h4 = $item->plaintext;
					$verses = str_replace( ",", "-", str_replace( " ", "", $h4 ) );
					$verse_parts = explode( "-", $verses );
					$refcode = str_replace( "ScriptIndex ", "", $item->getAttribute( "data-refcode" ) );
					$refcode_parts = explode( ".", $refcode );
					$book = $refcode_parts[0];
					$book_number = $book_names[$book];
					$chapter = $refcode_parts[1];
				} elseif( $item->tag == "p" ) {
					$quotes = $item->find( "a" );
					if( ! array_key_exists( 0, $verse_parts ) ) die;
					foreach( $quotes as $quote ) {
						$data = [
							"emphasis" => $quote->parent()->tag == "strong",
							"href" => $quote->href,
							"reference" => $quote->plaintext,
							"start" => constructReference( $book_number, $chapter, trim($verse_parts[0]) ),
						];
						if( array_key_exists( 1, $verse_parts ) ) {
							$data["end"] = constructReference( $book_number, $chapter, trim($verse_parts[1]) );
						}
						$this->db->insert( "scripture_index", $data );
						unset( $data );
					}
				}
			}
		}
		
		
	}
	
	function pull_egw_paragraph_quotes()
	{
		die("Restricted");
		$sql = 'SELECT scripture_index.* FROM scripture_index LEFT JOIN egw_quotes_new quotes ON scripture_index.reference = quotes.reference WHERE scripture_index.reference LIKE "%.%" AND quotes.id IS NULL';
		$query = $this->db->query($sql);
		$references = $query->result_array();
		foreach( $references as $item ) {
			$ref = $item["reference"];
			
			$exists_query = $this->db->query( "SELECT id FROM egw_quotes_new WHERE reference = '$ref'" );
			if( $exists_query->num_rows() > 0 ) continue;
			
			$url = $this->get_full_url( "https://m.egwwritings.org/search?query=$ref" );
			$paragraph_id = substr( $url, strpos( $url, "#" ) + 1 );
			$html = $this->domparser->file_get_html( $url );
			$quote = $html->find( "#" . $paragraph_id, 0 );
			
			$data = [
				"reference" => $ref,
			];
			
			$h3 = $html->find( "h3", 0 );
			$h4 = $html->find( "h4", 0 );
			if( $h3 ) {
				$data["chapter_title"] = trim( html_entity_decode( $h3->plaintext ) );
			}
			if( $h4 ) {
				$data["section_title"] = trim( html_entity_decode( $h4->plaintext ) );
			}
			if( $quote ) {
				$data["content"] = trim( $quote );
			} else {
				$data["failed"] = 1;
			}
			$this->db->insert( "egw_quotes_new", $data );
			unset( $data );
		}
	}
	
	function fix_egw_failed_quotes()
	{
		die("Restricted");
		$sql = 'SELECT * FROM egw_quotes_new WHERE reference NOT LIKE "%.%" AND run_again = 0';
		$query = $this->db->query($sql);
		$references = $query->result_array();
		foreach( $references as $item ) {
			$ref = $item["reference"];
			$ref = explode( "-", $ref );
			$ref = $ref[0];
			
			//$url = $this->get_full_url( "https://m.egwwritings.org/search?query=$ref" );
			//$paragraph_id = substr( $url, strpos( $url, "#" ) + 1 );
			$html = $this->domparser->file_get_html( "https://m.egwwritings.org/search?query=$ref" );
			$quotes = $html->find( "p.standard-indented" );
			
			$usable_quotes = "";
			foreach( $quotes as $quote ) {
				if( strpos( $quote->getAttribute( "data-refcode" ), $ref ) !== false ) {
					$usable_quotes .= trim( $quote );
				}
			}
			$data = [
				"run_again" => 1,
			];
			
			$h3 = $html->find( "h3", 0 );
			$h4 = $html->find( "h4", 0 );
			if( $h3 ) {
				$data["chapter_title"] = trim( html_entity_decode( $h3->plaintext ) );
			}
			if( $h4 ) {
				$data["section_title"] = trim( html_entity_decode( $h4->plaintext ) );
			}
			if( $usable_quotes !== "" ) {
				$data["content"] = $usable_quotes;
				$data["failed"] = 0;
			} else {
				$data["failed"] = 1;
			}
			$this->db->set( $data );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "egw_quotes_new" );
			unset( $data );
			unset( $usable_quotes );
		}
	}
	
	function ref_to_href()
	{
		die("Restricted");
		$sql = 'SELECT * FROM resources WHERE content LIKE "%scriptRef%"';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		$previous_use_internal_errors = libxml_use_internal_errors( true );
		foreach( $items as $item ) {
			$doc = new DOMDocument( "1.0", "UTF-8" );
			$doc->loadHTML( "<html>" . $item['content'] . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$xpath = new DOMXPath($doc);
			$spans = $xpath->query( "//span[@class = 'scriptRef']" );

			foreach( $spans as $span ) {
				$ref = str_replace( "+", " ", $span->getAttribute( "ref" ) );
				if( strpos($ref, "1ma") === 0 || strpos($ref, "bar") === 0  || strpos($ref, "2ma") === 0 || strpos($ref, "sir") === 0 || strpos($ref, "jdt") === 0 || strpos($ref, "1es") === 0 || strpos($ref, "2es") === 0 || strpos($ref, "wis") === 0 ) {
					continue;
				}
				$content = $span->nodeValue;
				$a = $doc->createElement( "a", $content );
				$a->setAttribute( "class", "bible-ref" );
				$new_ref = parseTextToShort( $ref );
				if( ! $new_ref ) {
					die( "Couldn't parse $ref" . " in resource id: {$item['id']}" );
				}
				$a->setAttribute( "href", "/" . $new_ref );
				$span->parentNode->replaceChild( $a, $span );
			}
			$content = str_replace( "<html>", "", $doc->saveHTML() );
			$content = str_replace( "</html>", "", $content );
			$this->db->set( "content", $content );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "resources" );
		}
		libxml_use_internal_errors( $previous_use_internal_errors );
	}
	
	//EGW
	function egw_ref_to_href()
	{
		die("Restricted");
		$sql = 'SELECT * FROM egw_quotes WHERE content LIKE "%egwlink_bible%"';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		$previous_use_internal_errors = libxml_use_internal_errors( true );
		foreach( $items as $item ) {
			$doc = new DOMDocument( "1.0", "UTF-8" );
			$doc->loadHTML( "<html>" . $item['content'] . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$xpath = new DOMXPath($doc);
			$links = $xpath->query( "//a[@class = 'link egwlink egwlink_bible']" );
			
			foreach( $links as $link ) {
				$content = $link->nodeValue;
				$a = $doc->createElement( "a", $content );
				$a->setAttribute( "class", "bible-ref" );
				$new_ref = parseTextToShort( $content );
				if( ! $new_ref ) {
					echo "Couldn't parse $content" . " in resource id: {$item['id']}";
					continue;
				}
				$a->setAttribute( "href", "/" . $new_ref );
				$link->parentNode->replaceChild( $a, $link );
			}
			$content = str_replace( "<html>", "", $doc->saveHTML() );
			$content = str_replace( "</html>", "", $content );
			$this->db->set( "content", $content );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "egw_quotes" );
		}
		libxml_use_internal_errors( $previous_use_internal_errors );
	}
	
	//EGW, incomplete remote reference
	function egw_ref_to_href_complex()
	{
		die("Restricted");
		ini_set('memory_limit', '512M');
		$sql = 'SELECT * FROM egw_quotes WHERE content LIKE "%egwlink_bible%"';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		$previous_use_internal_errors = libxml_use_internal_errors( true );
		foreach( $items as $item ) {
			$doc = new DOMDocument( "1.0", "UTF-8" );
			$doc->loadHTML( "<html>" . $item['content'] . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$xpath = new DOMXPath($doc);
			$links = $xpath->query( "//a[@class = 'link egwlink egwlink_bible']" );
			
			foreach( $links as $link ) {
				$content = $link->nodeValue;
				$href = $link->getAttribute( "href" );
				$html = $this->domparser->file_get_html( "https://m.egwwritings.org" . $href );
				$p = $html->find( "#" . explode( "#", $href )[1], 0 );
				$new_ref = $p->getAttribute( "data-refcode" );
				$new_ref = explode( " — ", $new_ref )[1];
				$new_ref = parseTextToShort( $new_ref );
				if( ! $new_ref ) {
					echo "Couldn't parse $content" . " in resource id: {$item['id']}";
					continue;
				}
				$a = $doc->createElement( "a", $content );
				$a->setAttribute( "class", "bible-ref" );
				$a->setAttribute( "href", "/" . $new_ref );
				$link->parentNode->replaceChild( $a, $link );
			}
			$content = str_replace( "<html>", "", $doc->saveHTML() );
			$content = str_replace( "</html>", "", $content );
			//echo $content;die;
			$this->db->set( "content", $content );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "egw_quotes" );
		}
		libxml_use_internal_errors( $previous_use_internal_errors );
	}
	
	//DAR
	function dar_ref_to_href()
	{
		die("Restricted");
		$sql = 'SELECT * FROM resources WHERE content LIKE \'%class="link"%\'';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		$previous_use_internal_errors = libxml_use_internal_errors( true );
		foreach( $items as $item ) {
			$doc = new DOMDocument( "1.0", "UTF-8" );
			$doc->loadHTML( "<html>" . $item['content'] . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$xpath = new DOMXPath($doc);
			$links = $xpath->query( "//a[@class = 'link']" );
			
			foreach( $links as $link ) {
				$content = $link->nodeValue;
				$href = $link->getAttribute( "href" );
				$html = $this->domparser->file_get_html( "https://m.egwwritings.org" . $href );
				$p = $html->find( "#" . explode( "#", $href )[1], 0 );
				$new_ref = $p->getAttribute( "data-refcode" );
				$new_ref = explode( " — ", $new_ref )[1];
				$new_ref = parseTextToShort( $new_ref );
				if( ! $new_ref ) {
					echo "Couldn't parse $content" . " in resource id: {$item['id']}<br>";
					continue;
				}
				$a = $doc->createElement( "a", $content );
				$a->setAttribute( "class", "bible-ref" );
				$a->setAttribute( "href", "/" . $new_ref );
				$link->parentNode->replaceChild( $a, $link );
			}
			$content = str_replace( "<html>", "", $doc->saveHTML() );
			$content = str_replace( "</html>", "", $content );
			$this->db->set( "content", $content );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "resources" );
		}
		libxml_use_internal_errors( $previous_use_internal_errors );
	}
	
	//SDABC
	function sdabc_ref_to_href()
	{
		die("Restricted");
		$sql = 'SELECT * FROM resources WHERE content LIKE "%bibleref%"';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		$previous_use_internal_errors = libxml_use_internal_errors( true );
		foreach( $items as $item ) {
			$doc = new DOMDocument( "1.0", "UTF-8" );
			$doc->loadHTML( "<html>" . $item['content'] . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$xpath = new DOMXPath($doc);
			$links = $xpath->query( "//a[@class = 'bibleref']" );
			foreach( $links as $link ) {
				$ref = $link->getAttribute( "data-reference" );
				$link->removeAttribute( "data-datatype" );
				//$a = $doc->createElement( "a", $content );
				//$a->setAttribute( "class", "bible-ref" );
				if( strpos( $ref, "-" ) !== -1 ) {
					$ref = explode( "-", $ref );
					$ref = $ref[0];
				}
				$ref_pieces = explode( ".", $ref );
				if( ! array_key_exists( 1, $ref_pieces)) {
					$ref_pieces[1] = 1;
				}
				$verse = $ref_pieces[1];
				
				preg_match('#(\d+)$#', $ref_pieces[0], $matches);//Get numbers at end of string
				$chapter = $matches[0];
				
				$book = rtrim( $ref_pieces[0], $chapter );
				$ref_to_parse = $book . " " . $chapter . ":" . $verse;
				$new_ref = parseTextToShort( $ref_to_parse );
				if( ! $new_ref ) {
					$span = $doc->createElement( "span", $link->nodeValue );
					$link->parentNode->replaceChild( $span, $link );
				} else {
					$link->setAttribute( "href", "/" . $new_ref );
					$link->setAttribute( "class", "bible-ref" );
				}
			}
			$content = str_replace( "<html>", "", $doc->saveHTML() );
			$content = str_replace( "</html>", "", $content );
			$this->db->set( "content", $content );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "resources" );
		}
		libxml_use_internal_errors( $previous_use_internal_errors );
	}
	
	//SDABC
	function sdabc_ref_to_href_more()
	{
		die("Restricted");
		$sql = 'SELECT * FROM resources WHERE content LIKE "%iv-vol%"';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		$previous_use_internal_errors = libxml_use_internal_errors( true );
		foreach( $items as $item ) {
			$doc = new DOMDocument( "1.0", "UTF-8" );
			$doc->loadHTML( "<html>" . $item['content'] . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$xpath = new DOMXPath($doc);
			$links = $xpath->query( "//a[@data-datatype = 'bible']" );
			foreach( $links as $link ) {
				$ref = $link->getAttribute( "data-reference" );
				$link->removeAttribute( "data-datatype" );
				$link->removeAttribute( "data-resourcename" );
				if( strpos( $ref, "-" ) !== -1 ) {
					$ref = explode( "-", $ref );
					$ref = $ref[0];
				}
				$ref_pieces = explode( ".", $ref );
				if( ! array_key_exists( 1, $ref_pieces)) {
					$ref_pieces[1] = 1;
				}
				$verse = $ref_pieces[1];
				
				preg_match('#(\d+)$#', $ref_pieces[0], $matches);//Get numbers at end of string
				$chapter = $matches[0];
				
				$book = rtrim( $ref_pieces[0], $chapter );
				$ref_to_parse = $book . " " . $chapter . ":" . $verse;
				$new_ref = parseTextToShort( $ref_to_parse );
				if( ! $new_ref ) {
					$span = $doc->createElement( "span", $link->nodeValue );
					$link->parentNode->replaceChild( $span, $link );
				} else {
					$link->setAttribute( "href", "/" . $new_ref );
					$link->setAttribute( "class", "bible-ref7777" );
				}
			}
			$content = str_replace( "<html>", "", $doc->saveHTML() );
			$content = str_replace( "</html>", "", $content );
			$this->db->set( "content", $content );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "resources" );
		}
		libxml_use_internal_errors( $previous_use_internal_errors );
	}
	
	//SDABC
	function sdabc_popup_to_span()
	{
		die("Restricted");
		$sql = 'SELECT * FROM resources WHERE content LIKE "%popup%"';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		$previous_use_internal_errors = libxml_use_internal_errors( true );
		foreach( $items as $item ) {
			$doc = new DOMDocument( "1.0", "UTF-8" );
			$doc->loadHTML( "<html>" . $item['content'] . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$xpath = new DOMXPath($doc);
			$links = $xpath->query( "//a[@rel = 'popup']" );
			foreach( $links as $link ) {
				$content = $link->nodeValue;
				$span = $doc->createElement( "span", $link->nodeValue );
				$span->setAttribute( "data-content", $link->getAttribute( "data-content" ) );
				$span->setAttribute( "class", "help-popup" );
				$link->parentNode->replaceChild( $span, $link );
			}
			$content = str_replace( "<html>", "", $doc->saveHTML() );
			$content = str_replace( "</html>", "", $content );
			//echo $content;die;
			$this->db->set( "content", $content );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "resources" );
		}
		libxml_use_internal_errors( $previous_use_internal_errors );
	}
	
	//SDABC
	function sdabc_internal_to_span()
	{
		die("Restricted");
		$sql = 'SELECT * FROM resources WHERE content LIKE "%iv-vol7%"';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		$previous_use_internal_errors = libxml_use_internal_errors( true );
		foreach( $items as $item ) {
			$doc = new DOMDocument( "1.0", "UTF-8" );
			$doc->loadHTML( "<html>" . $item['content'] . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$xpath = new DOMXPath($doc);
			$links = $xpath->query( "//a[@data-resourcename = 'iv-vol7']" );
			foreach( $links as $link ) {
				$content = $link->nodeValue;
				$span = $doc->createElement( "span", $link->nodeValue );
				$span->setAttribute( "class", "sdabc-internal" );
				$link->parentNode->replaceChild( $span, $link );
			}
			$content = str_replace( "<html>", "", $doc->saveHTML() );
			$content = str_replace( "</html>", "", $content );
			//echo $content;die;
			$this->db->set( "content", $content );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "resources" );
		}
		libxml_use_internal_errors( $previous_use_internal_errors );
	}
	
	//SDABC
	function sdabc_remove_milestones()
	{
		die("Restricted");
		$sql = 'SELECT * FROM resources WHERE content LIKE "%data-resourcename%"';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		$previous_use_internal_errors = libxml_use_internal_errors( true );
		foreach( $items as $item ) {
			$doc = new DOMDocument( "1.0", "UTF-8" );
			$doc->loadHTML( "<html>" . $item['content'] . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$xpath = new DOMXPath($doc);
			$links = $xpath->query( "//a[@rel = 'milestone']" );
			foreach( $links as $link ) {
				$link->parentNode->removeChild( $link );
			}
			$content = str_replace( "<html>", "", $doc->saveHTML() );
			$content = str_replace( "</html>", "", $content );
			//echo $content;die;
			$this->db->set( "content", $content );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "resources" );
		}
		libxml_use_internal_errors( $previous_use_internal_errors );
	}
	
	//SDABC
	function sdabc_remove_monographs()
	{
		die("Restricted");
		$sql = 'SELECT * FROM resources WHERE content LIKE "%text.serial.magazine%"';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		$previous_use_internal_errors = libxml_use_internal_errors( true );
		foreach( $items as $item ) {
			$doc = new DOMDocument( "1.0", "UTF-8" );
			$doc->loadHTML( "<html>" . $item['content'] . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$xpath = new DOMXPath($doc);
			$links = $xpath->query( "//a[@data-resourcetype = 'text.serial.magazine']" );
			foreach( $links as $link ) {
				$span = $doc->createElement( "span", $link->nodeValue );
				$link->parentNode->replaceChild( $span, $link );
			}
			$content = str_replace( "<html>", "", $doc->saveHTML() );
			$content = str_replace( "</html>", "", $content );
			//echo $content;die;
			$this->db->set( "content", $content );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "resources" );
		}
		libxml_use_internal_errors( $previous_use_internal_errors );
	}
	
	//SDABC
	function sdabc_egw_refs()
	{
		die("Restricted");
		$sql = 'SELECT * FROM resources WHERE content LIKE "%data-resourcename%"';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		$previous_use_internal_errors = libxml_use_internal_errors( true );
		foreach( $items as $item ) {
			$doc = new DOMDocument( "1.0", "UTF-8" );
			$doc->loadHTML( "<html>" . $item['content'] . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$xpath = new DOMXPath($doc);
			$links = $xpath->query( "//a[@data-datatype = 'page']" );
			foreach( $links as $link ) {
				$page = str_replace( "Page.p_", "", $link->getAttribute( "data-reference" ) );
				$book_code = str_replace( "iv-", "", $link->getAttribute( "data-resourcename" ) );
				$reference = $book_code . " " . $page;
				$link->removeAttribute( "data-reference" );
				$link->removeAttribute( "data-resourcename" );
				$link->removeAttribute( "data-datatype" );
				$link->setAttribute( "href", "https://m.egwwritings.org/search?query=$reference" );
				$link->setAttribute( "target", "_blank" );
				$link->setAttribute( "class", "egw-ref" );
			}
			$content = str_replace( "<html>", "", $doc->saveHTML() );
			$content = str_replace( "</html>", "", $content );
			$this->db->set( "content", $content );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "resources" );
		}
		libxml_use_internal_errors( $previous_use_internal_errors );
	}
	
	function convert_span_to_b()
	{
		die("Restricted");
		$sql = 'SELECT * FROM resources WHERE content LIKE \'%<span class="head">%\'';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		$previous_use_internal_errors = libxml_use_internal_errors( true );
		foreach( $items as $item ) {
			$doc = new DOMDocument( "1.0", "UTF-8" );
			$doc->loadHTML( "<html>" . $item['content'] . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$xpath = new DOMXPath($doc);
			$spans = $xpath->query( "//span[@class = 'head']" );

			foreach( $spans as $span ) {
				$phrase = $span->nodeValue;
				$b = $doc->createElement( "b", $phrase );
				$span->parentNode->replaceChild( $b, $span );
			}
			$content = str_replace( "<html>", "", $doc->saveHTML() );
			$content = str_replace( "</html>", "", $content );
			$this->db->set( "content", $content );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "resources" );
		}
		libxml_use_internal_errors( $previous_use_internal_errors );
	}
	
	function fix_egw_refs()
	{
		die("Restricted");
		$sql = 'SELECT * FROM scripture_index';
		$query = $this->db->query($sql);
		$quotes = $query->result_array();
		$last_book = null;
		foreach( $quotes as $item ) {
			if( preg_match( "/[a-z]/i", $item["reference"] ) ) {
				$last_book = explode( " ", $item["reference"] )[0];
			} else {
				$new_ref = $last_book . " " . $item["reference"];
				$this->db->set( "old_ref", $item["reference"] );
				$this->db->set( "reference", $new_ref );
				$this->db->where( "id", $item["id"] );
				//$this->db->update( "scripture_index" );
			}
		}
	}
	
	function create_index()
	{
		die("Restricted");
		$verse_query = $this->db->query( "SELECT * FROM kjv_verses WHERE ref > 55003006" );
		$verses = $verse_query->result_array();
		foreach( $verses as $verse ) {
			$ref = $verse["ref"];
			$resources = $this->db->query( "SELECT id FROM resources WHERE end >= $ref AND start <= $ref OR start = $ref" )->result_array();
			$index = 0;
			foreach( $resources as $resource ) {
				$data = [
					"verse" => $verse["ref"],
					"resource_id" => $resource["id"],
					"order_index" => $index,
				];
				$this->db->insert( "index", $data );
				$index++;
			}
		}
	}
    
    function create_index_for_chapter_comments()
	{
		die("Restricted");
		$resources = $this->db->query( "SELECT * FROM resources WHERE start LIKE '%000' AND info_id > 7")->result_array();
        //print_r($resources);die;
		foreach( $resources as $resource ) {
			$chapter = substr( $resource["start"], 0, 5 );
            $verse_query = $this->db->query( "SELECT * FROM kjv_verses WHERE ref LIKE '$chapter%'" );
            $verses = $verse_query->result_array();
            foreach( $verses as $verse ) {
                $ref = $verse["ref"];
                $data = [
                    "verse" => $verse["ref"],
                    "resource_id" => $resource["id"],
                ];
                $this->db->insert( "index", $data );
            }
		}
	}
	
	function create_egw_info()
	{
		die("Restricted");
		$query = $this->db->query( "SELECT * FROM resources WHERE reference != '' AND info_id IS NULL" );
		$items = $query->result_array();
		foreach( $items as $item ) {
			$code = explode( " ", $item["reference"] );
			$code = $code[0];
			$info = $this->db->query( "SELECT id FROM resource_info WHERE code = '$code'" );
			$info = $info->row_array();
			if( array_key_exists( "id", $info ) ) {
				$this->db->set( "info_id", $info["id"], FALSE );
				$this->db->where( "id", $item["id"] );
				$this->db->update('resources');
			}
		}
		
	}
	
	function remove_duplicate_commentaries()
	{
		die("Restricted");
		$this->output->enable_profiler(TRUE);
		$query = $this->db->query( "SELECT *, COUNT(*) count FROM resources GROUP BY content HAVING count > 1 AND info_id = 2 ORDER BY count DESC" );
		$items = $query->result_array();
		foreach( $items as $item ) {
			$duplicates = $this->db->select( "*" )
				->from( "resources" )
				->where( "info_id", 2 )
				->where( "length", $item["length"] )
				->where( "start", $item["start"] )
				->where( "id !=", $item["id"] )
				->get()
				->result_array();
			foreach( $duplicates as $duplicate ) {
				$this->db->delete( "resources", [ "id" => $duplicate["id"] ] );
				$this->db->delete( "index", [ "resource_id" => $duplicate["id"] ] );
			}
		}
		
	}
}