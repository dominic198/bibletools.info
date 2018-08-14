<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mapmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get( $ref )
	{
		if( is_numeric( $ref ) ) {
			$query = "SELECT maps.filename, maps.title FROM map_reference as ref LEFT JOIN maps ON ref.map_id = maps.id WHERE ref.end >= $ref AND ref.start <= $ref";
			$maps = $this->db->query( $query )->result_array();
			$array = [];
			foreach( $maps as $map ) {
				$array[] = [
					"source" => $map["title"],
					"class" => "map",
					"content" => "<a href='/assets/img/maps/{$map["filename"]}'><img src='/assets/img/maps/{$map["filename"]}'/></a>",
				];
			}
			return $array;
		}
		return [];
	}
}