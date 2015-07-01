<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class App extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$data['ref'] = $this->uri->segment(1);
		$this->load->view( "index", $data );
	}
	
	function contact_form()
	{
		$this->load->view( "contact_form" );
	}

	function submit_message()
	{
		$name = $this->input->post( "name" );
		$email = $this->input->post( "email" );
		$message = $this->input->post( "message" ) . "\r\n\r\n" . $email;
		$headers = "From: $email";
		
		if( ! empty( $message ) ) {
			mail( "akjackson1@gmail.com", "BibleTools.info Feedback from $name", $message, $headers );
		}
	}
}