<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mhcc extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->model('readermodel');
	}
	
	function get()
	{
		$book = $this->uri->segment(3);
		$chapter = $this->uri->segment(4);
		$verse = $this->uri->segment(5);
		
		if(isset($book) AND is_numeric($verse)){
			$sql = 'SELECT * FROM mhcc WHERE book = "'.$book.'" AND chapter = '.$chapter.' AND end_verse >= '.$verse.' AND start_verse <= '.$verse.' OR book = "'.$book.'" AND chapter = '.$chapter.' AND start_verse = '.$verse.' LIMIT 1';
		    $query = $this->db->query($sql);
		    $result = $query->result();
		    $result[0]->title = "Matthew Henry Concise Bible Commentary";
		    
		    $this->output->set_output( json_encode( $result ) );
		}		
	}
	
	function get_bc()
	{
		
	}
}