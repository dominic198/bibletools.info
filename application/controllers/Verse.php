<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Verse extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model( "resourcemodel" );
		$this->load->model( "kjvmodel" );
		$this->load->helper( "reference" );
		$this->load->library( "session" );
		$this->load->helper( "url" );
	}

	function index()
	{
		$ref = $this->uri->segment(1);
		if( ! $ref ) redirect( "/Matt_1.1" );
		$short_ref = $ref;
		$ref = shortTextToNumber( $ref );
		$data["verse"] = $this->kjvmodel->html_verse( $ref );
		if( ! $data["verse"] ) show_404();
		$data["text_ref"] = parseReferenceToText( $ref );
		$data["short_ref"] = $short_ref;
		$data["navigation"] = $this->kjvmodel->nav( $ref );
		$data["resources"] = $this->resourcemodel->get( $ref );
		$data["active_tab"] = "verses";
		$this->template->load( "template", "verse", $data );
		$this->saveLastVerse( $short_ref );
	}
	
	private function getLastVerse()
	{
		$history = $this->session->history;
		return $history[0];
	}
	
	private function saveLastVerse( $ref )
	{
		if( $this->getLastVerse() == $ref ) return;
		$history = $this->session->history ?: [];
		array_unshift( $history, $ref );
		$this->session->history = array_slice( $history, 0, 10 );
	}
}