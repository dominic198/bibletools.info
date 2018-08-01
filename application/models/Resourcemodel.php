<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resourcemodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getMain( $ref, $web = true )
	{
		if( is_numeric( $ref ) ) {
			if( $web ) {
				$resources = $this->db->query( "SELECT *, coalesce(egw_quotes.content, resources.content) as content FROM resources LEFT JOIN resource_info ON resources.info_id = resource_info.id  LEFT JOIN egw_quotes ON resources.reference = egw_quotes.reference WHERE end >= $ref AND start <= $ref OR start = $ref AND resource_info.id != 6" )->result_array();
			} else {
				$resources = $this->db->query( "SELECT *, coalesce(egw_quotes.content, resources.content) as content FROM resources LEFT JOIN resource_info ON resources.info_id = resource_info.id  LEFT JOIN egw_quotes ON resources.reference = egw_quotes.reference WHERE end >= $ref AND start <= $ref OR start = $ref" )->result_array();
			}
			
			return array_map( function( $item ) use( $web ) {
				$item["source"] = $item["name"];
				$page_ref = explode( " ", $item["reference"] );
				if( ! empty( $page_ref[1] ) ) {
					$item["source"] .= ", " . $page_ref[1];
				} elseif( ! empty( $item["page"] ) ) {
					$item["source"] .= ", " . $item["page"];
				}
				if( ! $web ) { //Legacy Android support
					$item["title"] = $item["source"];
				}
				if( !empty( $item["reference"] ) ) {
					$item["content"] .= "<a href='https://m.egwwritings.org/search?query={$item['reference']}' target='_blank'>Read in context &raquo;</a>";
				}
				return $item;
			}, $resources );
				
		}
	}

}