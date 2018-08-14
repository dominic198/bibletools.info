<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resources extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper( "url" );
		$this->load->helper( "reference" );
		$this->load->model( "kjvmodel" );
		$this->load->model( "resourcemodel" );
		$this->load->model( "mapmodel" );
	}
	
	function get()
	{
		//ANDROID
		
		$ref = constructReference( $this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5) );
		$resources = [ array_filter( $this->kjvmodel->plain_verse( $ref ) ) ] + $this->resourcemodel->getAndroid( $ref );
		$results['resources'] = array_values( array_filter( $resources ) );
		$log = [
			"verse" => $ref,
			"formatted_verse" => parseReferenceToText( $ref ),
			"ip" => $_SERVER["REMOTE_ADDR"] ?? null,
			"user_agent" => $_SERVER['HTTP_USER_AGENT'] ?? null,
			"api_version" => 0,
		];
		$this->db->insert( "log", $log );
		$this->output->set_output( json_encode( $results ) );
	}
}