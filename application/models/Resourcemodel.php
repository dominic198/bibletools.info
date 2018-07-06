<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resourcemodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getMain( $ref )
	{
		if( is_numeric( $ref ) ) {
			$resources = $this->db->query( "SELECT *, coalesce(egw_quotes.content, resources.content) as content FROM resources LEFT JOIN resource_info ON resources.info_id = resource_info.id  LEFT JOIN egw_quotes ON resources.reference = egw_quotes.reference WHERE end >= $ref AND start <= $ref OR start = $ref" )
				->result_array();
			return array_map( function( $item ) {
				$item["source"] = $item["name"];
				$page_ref = explode( " ", $item["reference"] );
				if( ! empty( $page_ref[1] ) ) {
					$item["source"] .= ", " . $page_ref[1];
				} elseif( ! empty( $item["page"] ) ) {
					$item["source"] .= ", " . $item["page"];
				}
				return $item;
			}, $resources );
				
		}
	}

}