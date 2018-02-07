<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Answers extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model( "questionmodel" );
	}

	function index()
	{
		$data = $this->questionmodel->get( $this->uri->segment(2) );
		$data["resources"] = $this->questionmodel->getResources( $data["id"] );
		$this->load->view( "answers", $data );
	}
}