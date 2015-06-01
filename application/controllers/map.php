<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Map extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->model('kjvapi');
	}
	
	function get()
	{
		$ref = $this->uri->segment(3);
		
		$results = array();
		$results['resources'] = array();
		if(is_numeric($ref)){
					
			$map_query = $this->db->query('SELECT maps.filename, maps.title FROM map_reference as ref LEFT JOIN maps ON ref.map_id = maps.id WHERE ref.end >= '.$ref.' AND ref.start <= '.$ref);
		    $map = $map_query->result();
		    
		    $this->output->set_output( json_encode( $map ) );
		}
	}
	function restructure()
	{
		$sql = 'SELECT * FROM map_reference';
	    $query = $this->db->query($sql);
	    $references = $query->result_array();
	    $i = 0;
	    foreach($references as $item){
	    	$book = str_pad($item['book'], 2, "0", STR_PAD_LEFT);
	    	$start_chapter = str_pad($item['start_chapter'], 3, "0", STR_PAD_LEFT);
	    	$start_verse = str_pad($item['start_verse'], 3, "0", STR_PAD_LEFT);
	    	$end_chapter = str_pad($item['end_chapter'], 3, "0", STR_PAD_LEFT);
	    	$end_verse = str_pad($item['end_verse'], 3, "0", STR_PAD_LEFT);
	    	
	    	$data['start'] = $book.$start_chapter.$start_verse;
	    	$data['end'] = $book.$end_chapter.$end_verse;
	    	
			$this->db->where('id', $item['id']);
			$this->db->update('map_reference', $data); 
	    	
	    }
	}
}