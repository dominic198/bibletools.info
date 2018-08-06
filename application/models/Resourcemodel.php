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
			$resources = $this->db->query( "SELECT *, coalesce(egw_quotes.content, resources.content) as content FROM resources LEFT JOIN resource_info ON resources.info_id = resource_info.id  LEFT JOIN egw_quotes ON resources.reference = egw_quotes.reference WHERE end >= $ref AND start <= $ref OR start = $ref AND resource_info.id != 6666" )->result_array();
			
			return array_map( function( $item ) {
				$item["source"] = $item["name"];
				$page_ref = explode( " ", $item["reference"] );
				if( ! empty( $page_ref[1] ) ) {
					$item["source"] .= ", " . $page_ref[1];
				} elseif( ! empty( $item["page"] ) ) {
					$item["source"] .= ", " . $item["page"];
				}
				if( !empty( $item["reference"] ) ) {
					$item["content"] .= "<a href='https://m.egwwritings.org/search?query={$item['reference']}' target='_blank'>Read in context &raquo;</a>";
				}
				return $item;
			}, $resources );
				
		}
	}
	
	function getAndroid( $ref )
	{
		if( is_numeric( $ref ) ) {
			$resources = $this->db->query( "SELECT *, coalesce(egw_quotes.content, resources.content) as content FROM resources LEFT JOIN resource_info ON resources.info_id = resource_info.id  LEFT JOIN egw_quotes ON resources.reference = egw_quotes.reference WHERE end >= $ref AND start <= $ref OR start = $ref" )->result_array();
			
			return array_map( function( $item ) {
				if( $item["logo"] == "egw" ) {
					$item["title"] = $item["name"];
					$page_ref = explode( " ", $item["reference"] );
					if( ! empty( $page_ref[1] ) ) {
						$item["title"] .= ", " . $page_ref[1];
					} elseif( ! empty( $item["page"] ) ) {
						$item["title"] .= ", " . $item["page"];
					}
					$item["content"] .= "<a href='https://m.egwwritings.org/search?query={$item['reference']}' target='_blank'>Read in context &raquo;</a>";
					return [
						"title" => $item["title"],
						"content" => $item["content"],
						"reference" => $item["reference"],
						"start" => $item["start"],
						"end" => $item["end"],
					];
				} else {
					return [
						"title" => $item["author"] . " " . $item["name"],
						"content" => $item["content"],
						"start" => $item["start"],
						"end" => $item["end"],
					];
				}
				
			}, $resources );
				
		}
	}

}