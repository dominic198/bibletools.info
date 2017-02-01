<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Run extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library( "domparser" );
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
	
	function pull_egw_paragraph_quotes()
	{
		$url = $this->get_full_url( "https://m.egwwritings.org/search?query=TMK 22.3" );
		$paragraph_id = substr( $url, strpos( $url, "#" ) + 1 );
		$html = $this->domparser->file_get_html( $url );
		$quote = $html->find( "#" . $paragraph_id, 0 );
		echo $quote;die;
		$sql = 'SELECT * FROM egw_quotes WHERE reference LIKE "%.%" LIMIT 1 OFFSET 1';
	    $query = $this->db->query($sql);
	    $references = $query->result_array();
	    foreach( $references as $item ) {
	    	$ref = $item["reference"];
	    	$html = $this->domparser->file_get_html( "https://m.egwwritings.org/search?query=$ref" );
	    	echo $html;die;
	    	$quote = $html->find( ".egw-selected-paragraph", 0 );
	    	
	    	if( $quote ) {
	    		die( "quote found" );
	    	} else {
	    		die( "quote not found" );
	    	}
	    	//print_r($quote);die;
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
}