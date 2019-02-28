<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resourcemodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getMain( $ref, $limit = 50, $offset = 0 )
	{
		if( is_numeric( $ref ) ) {
			$resources = $this->db->query( "SELECT *, coalesce(egw_quotes.content, resources.content) as content, index.id as id, resource_info.name as source FROM `index` LEFT JOIN resources ON index.resource_id = resources.id LEFT JOIN resource_info ON resources.info_id = resource_info.id LEFT JOIN egw_quotes ON resources.reference = egw_quotes.reference WHERE index.verse = $ref ORDER BY order_group LIMIT $limit OFFSET $offset" )->result_array();
			
			return array_map( function( $item ) {
				$source = $item["source"];
				$page_ref = explode( " ", $item["reference"] );
				if( ! empty( $page_ref[1] ) ) {
					$source .= ", " . $page_ref[1];
				} elseif( ! empty( $item["page"] ) ) {
					$source .= ", " . $item["page"];
				}
				$content = $item["content"];
				if( !empty( $item["reference"] ) ) {
					$ref_parts = explode( "-", $item["reference"] );
					$url_safe_ref = $ref_parts[0];
					$content .= "<a href='https://m.egwwritings.org/search?query=$url_safe_ref' target='_blank'>Read in context &raquo;</a>";
				}
				$array = [
					"content" => $content,
					"id" => $item["id"],
					"sidebar" => $item["sidebar"],
					"source" => $source,
					"logo" => $item["logo"],
					"author" => $item["author"],
				];
				if( ! empty( $item["reference"] ) ) {
					$array["reference"] = $item["reference"];
				}
				return $array;
			}, $resources );
				
		}
	}
	
	function countResources( $ref )
	{
		return $this->db->select( "*" )
			->from( "index" )
			->where( "verse", $ref )
			->count_all_results();
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
						"name" => $item["name"],
					];
				} else {
					return [
						"title" => $item["author"] . " " . $item["name"],
						"content" => $item["content"],
					];
				}
				
			}, $resources );
				
		}
	}

}