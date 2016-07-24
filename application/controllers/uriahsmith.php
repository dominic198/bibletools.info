<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Uriahsmith extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library( "domparser" );
	}
	
	function parse() {
		
		$html = $this->domparser->file_get_html( "http://bibletools.dev/script/uriah_smith/daniel.html" );
		foreach( $html->find( "section" ) as $section ) {
			$chapter = $section->getAttribute( "id" );
			foreach( $section->find( "div" ) as $verse ) {
				$start = $verse->getAttribute( "data-start" );
				$end = $verse->getAttribute( "data-end" );
				//print_r( $verse->innertext );die;
				$data = [
					"start" => $start,
					"end" => $end,
					"content" => trim( $verse->innertext ),
					"book" => 27,
					"chapter" => $chapter,
				];
				$this->db->insert( "dar", $data );
			}
		}
	}
}