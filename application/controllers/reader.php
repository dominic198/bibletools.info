<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reader extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->model('readermodel');
	}

	function index()
	{
		$data['book'] = $this->uri->segment(1);
		$data['chapter'] = $this->uri->segment(2);
		
		$this->template->write_view('content', 'dashboard/reader', $data);
		$this->template->render();
	}
}