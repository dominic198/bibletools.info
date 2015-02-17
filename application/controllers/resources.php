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
				
		$kjv = $this->getVerse($book, $chapter, $verse);
		$bc = $this->getBc($book, $chapter, $verse);
		$egw = $this->getEgw($book, $chapter, $verse);
		
		$resources = array( $kjv, $bc, $verse );
		
		$this->output->set_output( json_encode( $resources ) );
	}
	
	function getBc($book, $chapter, $verse)
	{
		
		$results = array();
		if(isset($book) AND is_numeric($verse)){
			
			//$results['nav'] = $this->kjvapi->nav($book, $chapter, $verse);
		
			$sdabc_query = $this->db->query('SELECT * FROM sdabc WHERE book = "'.$book.'" AND chapter = '.$chapter.' AND verse = '.$verse.' LIMIT 1');
		    $sdabc = $sdabc_query->result();
		    if($sdabc) {
		    	$sdabc[0]->title = "SDA Bible Commentary";
		    	array_push($results, $sdabc[0]);
		    	
		    }
		    
		    $mhcc_query = $this->db->query('SELECT content FROM mhcc WHERE book = "'.$book.'" AND chapter = '.$chapter.' AND end_verse >= '.$verse.' AND start_verse <= '.$verse.' OR book = "'.$book.'" AND chapter = '.$chapter.' AND start_verse = '.$verse.' LIMIT 1');
		    $mhcc = $mhcc_query->result();
		     
		     if($mhcc) {
		    	$mhcc[0]->title = "Matthew Henry Concise Bible Commentary";
		    	array_push($results, $mhcc[0]);
		    }
		    
		    $acbc_query = $this->db->query('SELECT content FROM acbc WHERE book = "'.$book.'" AND chapter = '.$chapter.' AND verse = '.$verse.' LIMIT 1');
		    $acbc = $acbc_query->result();
		    		    
		    if($acbc) {
		    	$acbc[0]->title = "Adam Clarke Bible Commentary";
		    	array_push($results, $acbc[0]);
		    }
		    //print_r($results);
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
	function getVerse($book, $chapter, $verse)
	{

		if(is_numeric($book) AND is_numeric($chapter) AND is_numeric($verse)){
			$sql = 'SELECT text FROM av WHERE book = '.$book.' AND chapter = '.$chapter.' AND verse = '.$verse;
		    $query = $this->db->query($sql);
			return $query->result_array();
		}
	}
}