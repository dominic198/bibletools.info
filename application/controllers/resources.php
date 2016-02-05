<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resources extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper( "url" );
		$this->load->helper( "reference" );
		$this->load->model( "kjvmodel" );
		$this->load->model( "egwmodel" );
		$this->load->model( "commentarymodel" );
		$this->load->model( "mapmodel" );
	}
	
	function web()
	{
		$ref = $this->uri->segment(2);
		$word = $this->uri->segment(3);
		
		if( $word ) {
			$strongs = $this->kjvmodel->lexicon( $ref, $word );
			$resources = array(
				"strongs" => $strongs,
				"resources" => array( 
					$strongs['data']['def']['long'],
					$this->kjvmodel->lexicon_occurances( $ref, $word, $strongs['base_word'] ),
				),
			);
			$this->output->set_output( json_encode( $resources ) );
		} else {
			$commentaries = array(
				$this->commentarymodel->get( $ref, "sdabc", "SDA Bible Commentary" ),
				$this->commentarymodel->get( $ref, "mhcc", "Matthew Henry Concise Bible Commentary", true ),
				$this->commentarymodel->get( $ref, "acbc", "Adam Clarke Bible Commentary" ),
				$this->commentarymodel->get( $ref, "tsk", "Treasury of Scripture Knowledge" ),
			);
			
			$resources = array(
				"verse" => $this->kjvmodel->html_verse( $ref ) ,
				"nav" => $this->kjvmodel->nav( $ref ),
				"commentaries" => array_values( array_filter( $commentaries ) ),
				"maps" => $this->mapmodel->get( $ref ),
				"egw" => $this->egwmodel->verse_references( $ref, 10 ),
			);
			
			$this->output->set_output( json_encode( $resources ) );
		}
	}
	
	function get()
	{
		//ANDROID
		
		$ref = constructReference( $this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5) );
		
		$maps = $this->mapmodel->get( $ref );
		foreach( $maps as $key => $map ) {
			$maps[$key]['content'] = "Update the app to view Biblical maps.";
		}
		
		$resources = array(
			$this->kjvmodel->plain_verse( $ref ),
			$this->commentarymodel->get( $ref, "sdabc", "SDA Bible Commentary" ),
			$this->commentarymodel->get( $ref, "mhcc", "Matthew Henry Concise Bible Commentary", true ),
			$this->commentarymodel->get( $ref, "acbc", "Adam Clarke Bible Commentary" ),
		);
		$resources = array_merge( $resources,
			$maps,
			$this->egwmodel->verse_quotes( $ref )
		);
		
		$results['resources'] = array_values( array_filter( $resources ) );
		$this->output->set_output( json_encode( $results ) );
	}
}