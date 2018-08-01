<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class About extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function feedback()
	{
		$data["active_tab"] = "feedback";
		$this->template->set( "title", "Feedback" );
		$this->template->load( "template", "feedback", $data );
	}
	
	function info()
	{
		$data["active_tab"] = "about";
		$this->template->set( "title", "About" );
		$this->template->load( "template", "about", $data );
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