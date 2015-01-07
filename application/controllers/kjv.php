<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kjv extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->model('kjvapi');
	}

	function index()
	{
		redirect('/home/');
	}
	function get()
	{
		$book = $this->uri->segment(3);
		$chapter = $this->uri->segment(4);
		$data = json_encode($this->kjvapi->chapter($book, $chapter));
		$this->output->set_output($data);
	}
	function get_nav()
	{
		$book = $this->uri->segment(3);
		$chapter = $this->uri->segment(4);
		$verse = $this->uri->segment(5);
		$data = json_encode($this->kjvapi->nav($book, $chapter, $verse));
		$this->output->set_output($data);
	}
	function search()
	{
		if(isset($_POST['phrase'])){
			$phrase = $_POST['phrase'];
			if($this->uri->segment(3) != ""){ $offset = $this->uri->segment(3); } else { $offset = 0; }
			$data = json_encode($this->kjvapi->search($phrase, $offset));
			$this->output->set_output($data);
		}
	}
	function get_verse()
	{
		//header('Content-Type: text/html; charset=utf-8');
		$book = $this->uri->segment(3);
		$chapter = $this->uri->segment(4);
		$verse = $this->uri->segment(5);
		$data = $this->kjvapi->verse($book, $chapter, $verse);
		$this->output->set_output($data);
	}
	function greek_lex()
	{
		$number = $this->uri->segment(3);
		$data = json_encode($this->kjvapi->greek_lex($number));
		$this->output->set_output($data);
	}
	function hebrew_lex()
	{
		$number = $this->uri->segment(3);
		$data = json_encode($this->kjvapi->hebrew_lex($number));
		$this->output->set_output($data);
	}
}