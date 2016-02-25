<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Egw extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper( "url" );
		$this->load->model( "egwmodel" );
	}
	
	function get()
	{
		$filter = "AND active = 1";
		if( FULL ) { //TEMPORARY ACCESS TO EGW
			$filter = "";
		}
		
		$ref = $this->uri->segment( 3 );
		$offset = $this->uri->segment( 4 ) ? $this->uri->segment( 4 ) : 0;
		
		$data = json_encode( $this->egwmodel->verse_references( $ref, 10, $offset, $filter ) );
		$this->output->set_output( $data );
	}

	function content()
	{
		$ref = $this->uri->segment(3);
		if( $ref != "undefined" ){
			$data = json_encode( $this->egwmodel->content( $ref ) );
			$this->output->set_output( $data );
		}
	}
}