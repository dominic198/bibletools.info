<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Run extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function index()
	{
		//
	}
	
	function minify()
	{
		$this->load->library( "minify" ); 
		$this->minify->css( array( "lib.css", "custom.css" ) ); 
		$this->minify->deploy_css( true );
		$this->minify->js( array( "custom.js", "lib.js" ) ); 
		$this->minify->deploy_js( true );
	}
	
	function restructure()
	{
		$sql = 'SELECT * FROM egw_scripture_reference';
	    $query = $this->db->query($sql);
	    $references = $query->result_array();
	    foreach($references as $item){
	    	$book = str_pad($item['book'], 2, "0", STR_PAD_LEFT);
	    	$chapter = str_pad($item['chapter'], 3, "0", STR_PAD_LEFT);
	    	$start_verse = str_pad($item['verse'], 3, "0", STR_PAD_LEFT);
	    	$end_verse = str_pad($item['endverse'], 3, "0", STR_PAD_LEFT);
	    	
	    	$data['start'] = $book.$chapter.$start_verse;
	    	$data['end'] = $book.$chapter.$end_verse;
	    	
			$this->db->where('id', $item['id']);
			$this->db->update('egw_scripture_reference', $data);
			//die;
	    }
	}
	
	function remove_commas()
	{
		$sql = "SELECT * FROM egw_scripture_reference WHERE verse LIKE '%,%'";
	    $query = $this->db->query( $sql );
	    $references = $query->result_array();
	    foreach( $references as $item ){
	    	$verse = str_replace( " ", "", $item['verse'] );
	    	$verse = explode( ",", $verse );
	    	
	    	$data['verse'] = $verse[0];
	    	$data['endverse'] = $verse[1];
	    		    	
			$this->db->where('id', $item['id']);
			$this->db->update('egw_scripture_reference', $data);
	    }
	}
}