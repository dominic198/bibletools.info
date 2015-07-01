<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Map extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper( "url" );
		$this->load->model( "mapmodel" );
		$this->load->database();
	}
	
	function get()
	{
		$ref = $this->uri->segment( 3 );
		$results = $this->mapmodel->get( $ref );
		$this->output->set_output( json_encode( $results ) );
	}
}