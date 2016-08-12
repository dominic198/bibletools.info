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
		
		$html = $this->domparser->file_get_html( "http://bibletools.dev/script/uriah_smith/revelation.html" );
		foreach( $html->find( "section" ) as $section ) {
			$chapter = $section->getAttribute( "id" );
			foreach( $section->find( "div" ) as $verse ) {
				$start = $verse->getAttribute( "data-start" );
				$page = $verse->getAttribute( "data-page" );
				$paragraph = $verse->getAttribute( "data-paragraph" );
				$end = $verse->getAttribute( "data-end" );
				$data = [
					"verse" => $start,
					"end" => $end,
					"content" => trim( $verse->innertext ),
					"book" => 66, //Revelation
					"chapter" => $chapter,
					"page" => $page,
					"paragraph" => $paragraph,
				];
				$this->db->insert( "dar", $data );
			}
		}
	}
}