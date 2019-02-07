<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Verse extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model( "resourcemodel" );
		$this->load->model( "kjvmodel" );
		$this->load->model( "mapmodel" );
		$this->load->helper( "reference" );
		$this->load->helper( "history" );
		$this->load->helper( "url" );
	}

	function index( $query = null )
	{
		$history_ref = getLastVerse();
		$method = "direct";
		if( ! $query && $history_ref ) {
			$query = $history_ref;
			$method = "history";
		} elseif( ! $query ) {
			$query = "Matt_1.1";
			$method = "first_load";
		}
		
		if( $ref = shortTextToNumber( $query ) ) {
			$short_ref = $query;
		} elseif( $short_ref = parseTextToShort( urldecode( $query ) ) ) {
			$ref = shortTextToNumber( $short_ref );
		}
		
		$data["verse"] = $this->kjvmodel->html_verse( $ref );
		if( ! $data["verse"] ) show_404();
		saveLastVerse( $short_ref );
		$data["text_ref"] = parseReferenceToText( $ref );
		$data["short_ref"] = $short_ref;
		$data["navigation"] = $this->kjvmodel->nav( $ref );
		$data["main_resources"] = $this->resourcemodel->getMain( $ref, 8 );
		$data["sidebar_resources"] = array_filter( array_merge(
			[ $this->kjvmodel->getCrossReferences( $ref ) ],
			$this->mapmodel->get( $ref )
		) );
		$data["active_tab"] = "verses";
		$data["resource_count"] = $this->resourcemodel->countResources( $ref );
		
		$log = [
			"verse" => $ref,
			"formatted_verse" => $data["text_ref"],
			"ip" => $_SERVER["REMOTE_ADDR"],
			"user_agent" => $_SERVER['HTTP_USER_AGENT'] ?? null,
			"method" => $method,
		];
		$this->db->insert( "log", $log );
		
		$this->template->set( "title", $data["text_ref"] );
		$this->template->load( "template", "verse", $data );
	}
}