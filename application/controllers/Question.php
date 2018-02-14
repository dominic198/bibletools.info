<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Question extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model( "questionmodel" );
	}

	function index()
	{
		$slug = $this->uri->segment(2);
		if( ! $slug ) show_404();
		$data = $this->questionmodel->get( $this->uri->segment(2) );
		$data["resources"] = $this->questionmodel->getResources( $data["id"] );
		$data["verses"] = $this->questionmodel->getVerses( $data["id"] );
		$data["related_questions"] = $this->questionmodel->getRelated( $data["id"], $data["category_id"] );
		$this->template->load( "template", "question", $data );
	}
}