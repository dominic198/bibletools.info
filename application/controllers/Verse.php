<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Verse extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model( "resourcemodel" );
		$this->load->model( "kjvmodel" );
		$this->load->model( "mapmodel" );
		$this->load->helper( "reference" );
		$this->load->helper( "history" );
		$this->load->helper( "url" );
	}

	function index()
	{
		$ref = $this->uri->segment(1);
		$history_ref = getLastVerse();
		if( ! $ref && $history_ref ) {
			redirect( "/$history_ref" );
		} elseif( ! $ref ) {
			redirect( "/Matt_1.1" );
		}
		$short_ref = $ref;
		$ref = shortTextToNumber( $ref );
		$data["verse"] = $this->kjvmodel->html_verse( $ref );
		if( ! $data["verse"] ) show_404();
		$data["text_ref"] = parseReferenceToText( $ref );
		$data["short_ref"] = $short_ref;
		$data["navigation"] = $this->kjvmodel->nav( $ref );
		$data["main_resources"] = $this->resourcemodel->getMain( $ref );
		$data["sidebar_resources"] = array_filter( array_merge(
			[ $this->kjvmodel->getCrossReferences( $ref ) ],
			$this->mapmodel->get( $ref )
		) );
		$data["active_tab"] = "verses";
		$this->template->load( "template", "verse", $data );
		saveLastVerse( $short_ref );
	}
}