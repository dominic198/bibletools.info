<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resources extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper( "url" );
		$this->load->helper( "reference" );
		$this->load->model( "kjvmodel" );
		$this->load->model( "resourcemodel" );
		$this->load->model( "mapmodel" );
		$this->load->helper( "history" );
	}
	
	function json()
	{
		$segment = $this->uri->segment(3);
		$ref = $segment == "query" ? parseTextToShort( urldecode( $this->uri->segment(4) ) ) : $segment;
		$short_ref = $ref;
		$word = $this->uri->segment(4);
		$ref = shortTextToNumber( $ref );
		
		if( $segment != "query" && $word ) {
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
			$resources = array(
				"main_resources" => $this->resourcemodel->getMain( $ref ),
				"sidebar_resources" => array_filter( array_merge(
					[ $this->kjvmodel->getCrossReferences( $ref ) ],
					$this->mapmodel->get( $ref )
				) ),
				"verse" => $this->kjvmodel->html_verse( $ref ),
				"text_ref" => parseReferenceToText( $ref ),
				"short_ref" => $short_ref,
				"nav" => $this->kjvmodel->nav( $ref ),
			);
			saveLastVerse( $short_ref );
			$log = [
				"verse" => $ref,
				"formatted_verse" => $resources["text_ref"],
				"ip" => $_SERVER["REMOTE_ADDR"],
				"user_agent" => $_SERVER['HTTP_USER_AGENT'] ?? null,
				"type" => "web",
			];
			$this->db->insert( "log", $log );
			$this->output->set_output( json_encode( $resources ) );
		}
	}
	
	function get()
	{
		//ANDROID
		
		$ref = constructReference( $this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5) );
		$resources = [ $this->kjvmodel->plain_verse( $ref ) ] + $this->resourcemodel->getMain( $ref, false );
		
		$results['resources'] = array_values( array_filter( $resources ) );
		$log = [
			"verse" => $ref,
			"formatted_verse" => parseReferenceToText( $ref ),
			"ip" => $_SERVER["REMOTE_ADDR"],
			"user_agent" => $_SERVER['HTTP_USER_AGENT'] ?? null,
			"type" => "android",
		];
		$this->db->insert( "log", $log );
		$this->output->set_output( json_encode( $results ) );
	}
}