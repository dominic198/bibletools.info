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
		$this->minify->js( array( "custom.js", "lib.js" ) ); 
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
	
	function ref_to_href()
	{
		$sql = 'SELECT * FROM resources WHERE content LIKE "%bible-kjv%" LIMIT 1';
		$query = $this->db->query($sql);
		$resources = $query->result_array();
		//print_r($resources);die;
		$previous_use_internal_errors = libxml_use_internal_errors( true );
		foreach( $resources as $item ) {
			$doc = new DOMDocument( "1.0", "UTF-8" );
			$doc->loadHTML($item['content']);
			$xpath = new DOMXPath($doc);

			$nodes = $xpath->query( "//span[@class = 'bible-kjv']" );
			
			foreach( $nodes as $node ) {
				//$anchor = $doc->createElement( "a" );
				$node->parentNode->replaceChild( $node->firstChild, $node );
			}
			
			echo $doc->saveHTML();die;
			//$this->db->where( "id", $item["id"] );
			//$this->db->update( "content", $new_content );
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
}