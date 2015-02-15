<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resources extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->model('kjvapi');
		$this->load->model('readermodel');
	}
	
	function get()
	{
		$book = $this->uri->segment(3);
		$chapter = $this->uri->segment(4);
		$verse = $this->uri->segment(5);
		
		$bc = $this->getBc($book, $chapter, $verse);
		$egw = $this->getEgw($book, $chapter, $verse);
		
		$this->output->set_output( json_encode( $bc + $egw ) );
	}
	
	function getBc($book, $chapter, $verse)
	{
		
		$results = array();
		$results['resources'] = array();
		if(isset($book) AND is_numeric($verse)){
			
			$results['nav'] = $this->kjvapi->nav($book, $chapter, $verse);
		
			$sdabc_query = $this->db->query('SELECT * FROM sdabc WHERE book = "'.$book.'" AND chapter = '.$chapter.' AND verse = '.$verse.' LIMIT 1');
		    $sdabc = $sdabc_query->result();
		    		    
		    if($sdabc) {
		    	$sdabc[0]->title = "SDA Bible Commentary";
		    	array_push($results['resources'], $sdabc[0]);
		    }
		    
		    $mhcc_query = $this->db->query('SELECT * FROM mhcc WHERE book = "'.$book.'" AND chapter = '.$chapter.' AND end_verse >= '.$verse.' AND start_verse <= '.$verse.' OR book = "'.$book.'" AND chapter = '.$chapter.' AND start_verse = '.$verse.' LIMIT 1');
		    $mhcc = $mhcc_query->result();
		     
		     if($mhcc) {
		    	$mhcc[0]->title = "Matthew Henry Concise Bible Commentary";
		    	array_push($results['resources'], $mhcc[0]);
		    }
		    
		    $acbc_query = $this->db->query('SELECT * FROM acbc WHERE book = "'.$book.'" AND chapter = '.$chapter.' AND verse = '.$verse.' LIMIT 1');
		    $acbc = $acbc_query->result();
		    		    
		    if($acbc) {
		    	$acbc[0]->title = "Adam Clarke Bible Commentary";
		    	array_push($results['resources'], $acbc[0]);
		    }
		    
		    return $results;
		}
	}
	
	function getEgw($book, $chapter, $verse)
	{		
		if(isset($book) AND is_numeric($chapter)){
			$sql = 'SELECT * FROM egw_scripture_reference WHERE book = "'.$book.'" AND chapter = '.$chapter.' AND endverse >= '.$verse.' AND verse <= '.$verse.' OR book = "'.$book.'" AND chapter = '.$chapter.' AND verse = '.$verse;
		    $query = $this->db->query($sql);
		    $egw = $query->result_array();
		    
			return $egw;
		}
	}
}