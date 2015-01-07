<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Acbc extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->model('kjvapi');
	}
	
	function get()
	{
		$book = $this->uri->segment(3);
		$chapter = $this->uri->segment(4);
		$verse = $this->uri->segment(5);
		
		if(isset($book) AND is_numeric($verse)){
			$sql = 'SELECT * FROM acbc WHERE book = "'.$book.'" AND chapter = '.$chapter.' AND verse = '.$verse.' LIMIT 1';
		    $query = $this->db->query($sql);
		    $result = $query->result();
		    $result[0]->title = "SDA Bible Commentary";
		    $result[0]->nav = $this->kjvapi->nav($book, $chapter, $verse);
		    
		    $this->output->set_output( json_encode( $result ) );
		}
	}
}