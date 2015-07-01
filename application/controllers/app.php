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
}