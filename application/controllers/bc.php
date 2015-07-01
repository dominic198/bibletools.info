<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bc extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper( "url" );
		$this->load->model( "kjvmodel" );
		$this->load->model( "commentarymodel" );
	}
	
	function get()
	{		
		$ref = construct_reference( $this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5) );
		
		$results['nav'] = $this->kjvmodel->nav( $ref );
		$results['text'] = $this->kjvmodel->verse( $ref );
		
		$results['resources'] = array();
		$results['resources'][] = $this->commentarymodel->get( $ref, "sdabc", "SDA Bible Commentary" );
		$results['resources'][] = $this->commentarymodel->get( $ref, "acbc", "Adam Clarke Bible Commentary" );
		$results['resources'][] = $this->commentarymodel->get( $ref, "mhcc", "Matthew Henry Concise Bible Commentary", true );
		
		$this->output->set_output( json_encode( $results ) );
	}
}