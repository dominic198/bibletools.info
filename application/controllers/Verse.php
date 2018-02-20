<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Verse extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model( "resourcemodel" );
		$this->load->model( "kjvmodel" );
		$this->load->helper( "reference" );
	}

	function index()
	{
		$ref = $this->uri->segment(2);
		$ref = shortTextToNumber( $ref );
		if( ! $ref ) show_404();
		$data["verse"] = $this->kjvmodel->html_verse( $ref );
		$data["text_ref"] = parseReferenceToText( $ref );
		$data["resources"] = $this->resourcemodel->get( $ref );
		$this->template->load( "template", "verse", $data );
	}
}