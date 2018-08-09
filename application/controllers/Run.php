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
		//
	}
	
	function minify()
	{
		$this->load->library( "minify" ); 
		$this->minify->css( array( "lib.css", "custom.css" ) ); 
		$this->minify->deploy_css( true );
		$this->minify->js( array( "lib.js", "custom.js"  ) ); 
		$this->minify->deploy_js( true );
	}
	
	function restructure()
	{
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
	
	function churches()
	{
		$churches = array();
		$page = 183;
		while( $page < 244 ){
			$html = $this->domparser->file_get_html( "http://adventistdirectory.org/default.aspx?page=searchresults&&EntityType=C&CtryCode=US&SortBy=0&PageIndex=$page" );
			if( is_object($html) ){
				$table = $html->find( "table", 1 );
				foreach ( $table->find( "tr" ) as $row ) {
					$link = $row->find( "a", 0 );
					if( $link ) {
						$url = "http://adventistdirectory.org/" . $link->href;
						$church = $this->domparser->file_get_html( $url );
						
						$entity_id = explode( "EntityID=", $url );
						$entity_id = $entity_id[1];
												
						$pastor = "";
						$website = "";
						$members = "";
						$phone = "";
						$mailing_address = "";
						$physical_address = "";
						$rows = $church->find( "tr");
						
						foreach( $rows as $row ) {
							$plain = $row->find( "td", 0 )->plaintext;
							if( strchr( $plain, "Mail" ) ) {
								$mailing_address_td = $row->find( "td", 1 )->innertext;
								$mailing_address = $this->parseAddress( $mailing_address_td );
							} elseif( strchr( $plain, "Address" ) ) {
								$physical_address_td = $row->find( "td", 1 )->innertext;
								$physical_address = $this->parseAddress( $physical_address_td );
							} elseif( strchr( $plain, "Members" ) ) {
								$members = $row->find( "td", 1 )->plaintext;
							} elseif( strchr( $plain, "Phone" ) ) {
								$phone = $row->find( "td", 1 )->plaintext;
							} elseif( strchr( $plain, "Website" ) ) {
								$website = $row->find( "td", 1 )->plaintext;
							} elseif( strchr( $plain, "Pastor" ) ) {
								$pastor = $row->find( "td", 1 )->plaintext;
							}
						}
						
						$data = array(
							"entity_id" => $entity_id,
							"name" => $church->find( "#_ctl0_lblName", 0 )->plaintext,
							"members" => trim( $members ),
							"pastor" => trim( $pastor ),
							"phone" => trim( $phone ),
							"website" => trim( $website ),
						);
						
						if( $mailing_address !== "" ) {
							$data['mailing_address_name'] = $mailing_address['name'];
							$data['mailing_address'] = $mailing_address['address'];
							$data['mailing_state'] = $mailing_address['state'];
							$data['mailing_zip'] = $mailing_address['zip'];
							$data['mailing_city'] = $mailing_address['city'];
							$data['mailing_country'] = $mailing_address['country'];
						}
						
						if( $physical_address !== "" ) {
							$data['physical_address_name'] = $physical_address['name'];
							$data['physical_address'] = $physical_address['address'];
							$data['physical_state'] = $physical_address['state'];
							$data['physical_zip'] = $physical_address['zip'];
							$data['physical_city'] = $physical_address['city'];
							$data['physical_country'] = $physical_address['country'];
						}
						
						$this->db->insert('churches', $data);
						unset( $data );
						unset( $website );
						unset( $phone );
						unset( $pastor );
						unset( $physical_address );
						unset( $mailing_address );
					}
				}
			}
		$page++;
		}
	}
	
	function parseAddress( $td )
	{
		$array = explode( "</div>", $td );
		if( array_key_exists( 1, $array ) ) {
			$td = $array[1];
		}
		$td = str_replace( "<br>", "", $td );
		
		$address = array_map( function( $item ) {
			return trim( preg_replace('/\t+/', '', $item ) );
		}, explode( PHP_EOL, $td ) );
		
		$address = array_values( array_filter( $address ) );
		if( count( $address ) == 4 ) {
			$location = explode( "  ", $address[2] );
			$state = end( explode( " ", $location[0] ) );
			$city = explode( " ", $location[0] );
			array_pop( $city );
			$city = implode( " ", $city );
			$zip = $location[1];
			
			return array(
				"name" => $address[0],
				"address" => $address[1],
				"state" => $state,
				"zip" => $zip,
				"city" => $city,
				"country" => $address[3]
			);
		} else {
			$location = explode( "  ", $address[1] );
			$state = end( explode( " ", $location[0] ) );
			$city = explode( " ", $location[0] );
			array_pop( $city );
			$city = implode( " ", $city );
			if( array_key_exists( 1, $location ) ) {
				$zip = $location[1];
			} else {
				$zip = "";
			}
			
			
			return array(
				"name" => "",
				"address" => $address[0],
				"state" => $state,
				"zip" => $zip,
				"city" => $city,
				"country" => $address[2]
			);
		}
	}
	
	function tsk()
	{		
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
			$this->db->set( "content", $content );
			$this->db->where( "id", $item["id"] );
			$this->db->update( "egw_quotes" );
		}
		libxml_use_internal_errors( $previous_use_internal_errors );
	}
	
	//DAR
	function dar_ref_to_href()
	{
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
	
	function fix_abundance()
	{
		$sql = 'SELECT * FROM abundance';
		$query = $this->db->query($sql);
		$items = $query->result_array();
		foreach( $items as $item ) {
			$consistency = ($item["wk1"] > 0) + ($item["wk2"] > 0) + ($item["wk3"] > 0) + ($item["wk4"] > 0) + ($item["wk5"] > 0) + ($item["wk6"] > 0) + ($item["wk7"] > 0) + ($item["wk8"] > 0) + ($item["wk9"] > 0) + ($item["wk10"] > 0) + ($item["wk11"] > 0) + ($item["wk12"] > 0) + ($item["wk13"] > 0) + ($item["wk14"] > 0) + ($item["wk15"] > 0) + ($item["wk16"] > 0) + ($item["wk17"] > 0) + ($item["wk18"] > 0) + ($item["wk19"] > 0) + ($item["wk20"] > 0) + ($item["wk21"] > 0) + ($item["wk22"] > 0) + ($item["wk23"] > 0) + ($item["wk24"] > 0) + ($item["wk25"] > 0) + ($item["wk26"] > 0) + ($item["wk27"] > 0) + ($item["wk28"] > 0) + ($item["wk29"] > 0) + ($item["wk30"] > 0) + ($item["wk31"] > 0) + ($item["wk32"] > 0) + ($item["wk33"] > 0) + ($item["wk34"] > 0) + ($item["wk35"] > 0) + ($item["wk36"] > 0) + ($item["wk37"] > 0) + ($item["wk38"] > 0) + ($item["wk39"] > 0) + ($item["wk40"] > 0) + ($item["wk41"] > 0) + ($item["wk42"] > 0) + ($item["wk43"] > 0) + ($item["wk44"] > 0) + ($item["wk45"] > 0) + ($item["wk46"] > 0) + ($item["wk47"] > 0) + ($item["wk48"] > 0);
			$this->db->set( "consistency", $consistency, FALSE );
			$this->db->where( "id", $item["id"] );
			$this->db->update('abundance');
		}
	}
	
	function create_egw_info()
	{
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
	
	function info()
	{
		echo phpinfo();
	}
}