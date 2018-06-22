<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Questions extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model( "questionmodel" );
	}

	function index()
	{
		$data["formatted_questions"] = $this->questionmodel->getFormatted();
		$data["popular_questions"] = $this->questionmodel->getPopular();
		$data["recent_questions"] = $this->questionmodel->getRecent();
		$data["active_tab"] = "questions";
		$this->template->load( "template", "questions", $data );
	}
}