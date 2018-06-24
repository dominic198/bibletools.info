<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resourcemodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get( $ref )
	{
		if( is_numeric( $ref ) ) {
			$resources = $this->db->query( "SELECT *, coalesce(egw_quotes.content, resources.content) as content FROM resources LEFT JOIN resource_info ON resources.info_id = resource_info.id  LEFT JOIN egw_quotes ON resources.reference = egw_quotes.reference WHERE end >= $ref AND start <= $ref OR start = $ref" )
				->result_array();
			return array_map( function( $item ) {
				$item["page_ref"] = null;
				$page_ref = explode( " ", $item["reference"] );
				if( array_key_exists( 1, $page_ref ) ) {
					$item["page_ref"] = $page_ref[1];
				} elseif( array_key_exists( "page", $item ) ) {
					$item["page_ref"] = $item["page"];
				}
				return $item;
			}, $resources );
				
		}
	}

}