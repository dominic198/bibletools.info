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
		$map = $this->getMap($book, $chapter, $verse);
		$egw = $this->getEgw($book, $chapter, $verse);
		
		$resources = array();
		$resources['resources'] = array_merge( $kjv, $bc, $map, $egw );

		$this->output->set_output( json_encode( $resources ) );
	}
	
	function getBc($book, $chapter, $verse)
	{
		
		$results = array();
		if(isset($book) AND is_numeric($verse)){
					
			$sdabc_query = $this->db->query('SELECT content FROM sdabc WHERE book = "'.$book.'" AND chapter = '.$chapter.' AND verse = '.$verse.' LIMIT 1');
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
		    return $results;
		}
	}
	
	function getMap($book, $chapter, $verse)
	{
		if(isset($book) AND is_numeric($verse)){
			$book = str_pad($book, 2, "0", STR_PAD_LEFT);
	    	$chapter = str_pad($chapter, 3, "0", STR_PAD_LEFT);
	    	$verse = str_pad($verse, 3, "0", STR_PAD_LEFT);
			$ref = $book.$chapter.$verse;
						
			$map_query = $this->db->query('SELECT maps.filename, "Update the app to view Biblical maps." as content, maps.title FROM map_reference as ref LEFT JOIN maps ON ref.map_id = maps.id WHERE ref.end >= '.$ref.' AND ref.start <= '.$ref);
		    $map = $map_query->result();
		    
		   return $map;
		}
	}
	
	function getEgw($book, $chapter, $verse)
	{		
		if(isset($book) AND is_numeric($chapter)){
			$sql = 'SELECT ref.reference, quote.text as content, quote.title FROM egw_scripture_reference as ref LEFT JOIN egw_quotes_new as quote ON ref.reference = quote.reference WHERE ref.book = "'.$book.'" AND ref.chapter = '.$chapter.' AND ref.endverse >= '.$verse.' AND ref.verse <= '.$verse.' OR ref.book = "'.$book.'" AND ref.chapter = '.$chapter.' AND ref.verse = '.$verse;
		    $query = $this->db->query($sql);
		    $egw = $query->result_array();
			return $egw;
		}
	}
	function getVerse($book, $chapter, $verse)
	{

		if(is_numeric($book) AND is_numeric($chapter) AND is_numeric($verse)){
			$sql = 'SELECT text, books.book FROM av LEFT JOIN books ON av.book = books.number WHERE av.book = '.$book.' AND chapter = '.$chapter.' AND verse = '.$verse;
		    $query = $this->db->query($sql);
		    $results = $query->result_array();
		    $results[0]['title'] = "{$results[0]['book']} {$chapter}:{$verse}";
		    $results[0] += $this->kjvapi->numericNav($book, $chapter, $verse);
		    unset( $results[0]['book'] );
			return $results;
		}
	}
}