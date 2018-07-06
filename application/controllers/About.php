<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class About extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function feedback()
	{
		$this->template->load( "template", "feedback" );
	}
	
	function send_feedback()
	{
		$name = $this->input->post( "name" );
		$email = $this->input->post( "email" );
		$message = $this->input->post( "message" ) . "\r\n\r\n" . $email;
		$headers = "From: $email";
		
		if( ! empty( $message ) ) {
			mail( "adam@bibletools.info", "BibleTools.info Feedback from $name", $message, $headers );
		}
	}
}